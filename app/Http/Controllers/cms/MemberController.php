<?php

namespace App\Http\Controllers\cms;

use App\Exports\MemberExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\Group;
use App\Models\Homecell;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Member::with(['group', 'user'])->orderBy('created_at', 'desc');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    $criteria = $request->input('quick_search_criteria');
                    $value = trim((string) $request->input('quick_search_value', ''));

                    if ($value === '' || empty($criteria)) {
                        return;
                    }

                    if ($criteria === 'phone') {
                        $query->where('phone', 'like', '%' . $value . '%');
                        return;
                    }

                    if ($criteria === 'member_number') {
                        $query->where('member_number', 'like', '%' . $value . '%');
                        return;
                    }

                    if ($criteria === 'national_id') {
                        if (Schema::hasColumn('members', 'national_id')) {
                            $query->where('national_id', 'like', '%' . $value . '%');
                            return;
                        }

                        if (Schema::hasColumn('members', 'id_number')) {
                            $query->where('id_number', 'like', '%' . $value . '%');
                            return;
                        }

                        $query->whereRaw('1 = 0');
                    }
                }, true)
                ->editColumn('member_number', function ($row) {
                    if (is_null($row->member_number)) {
                        return 'N/A';
                    }
                    return $row->member_number;
                })
                ->editColumn('full_name', function ($row) {
                    if (is_null($row->full_name)) {
                        return 'N/A';
                    }
                    return '<a href="' . route('members.show', $row->id) . '">' . $row->full_name . '</a>';
                })
                ->editColumn('join_date', function ($row) {
                    if (is_null($row->join_date)) {
                        return 'N/A';
                    }

                    return date_format($row->join_date, 'Y/m/d');
                })
                ->editColumn('birth_date', function ($row) {
                    if (is_null($row->birth_date)) {
                        return 'N/A';
                    }

                    return date_format($row->birth_date, 'Y/m/d');
                })
                ->editColumn('group_id', function ($row) {
                    return $row->group->name ?? 'N/A';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    if (is_null($row->created_at)) {
                        return 'N/A';
                    }

                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = $btn_view = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('members.edit', $row->id) . '" 
                                        class="btn btn-link btn-primary btn-lg" 
                                        data-original-title="Edit Record">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    }

                    $btn_view = '<a data-toggle="tooltip" 
                                        href="' . route('members.show', $row->id) . '" 
                                        class="btn btn-link btn-success btn-lg" 
                                        data-original-title="View Record">
                                    <i class="fa fa-eye"></i>
                                </a>';

                    if (auth()->user()->hasRole('superadmin')) {
                        $btn_del = '<button type="button" 
                                    data-toggle="tooltip" 
                                    title="" 
                                    class="btn btn-link btn-danger" 
                                    onclick="delRecord(`' . $row->id . '`, `' . route('members.destroy', $row->id) . '`, `#tb_members`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_view . $btn_del;
                })
                ->rawColumns(['action', 'full_name', 'member_number', 'join_date', 'birth_date'])
                ->make(true);
        }

        // render view
        return view('cms.members.index');
    }

    /**
     * Search members for Select2 AJAX dropdown.
     */
    public function search(Request $request)
    {
        $term = trim((string) $request->query('term', ''));
        $page = max((int) $request->query('page', 1), 1);
        $eventId = (int) $request->query('event_id', 0);

        $query = Member::query()->select(['id', 'full_name', 'member_number']);

        if ($eventId > 0) {
            $query->whereDoesntHave('event_attendances', function ($attendanceQuery) use ($eventId) {
                $attendanceQuery->where('event_id', $eventId);
            });
        }

        if ($term !== '') {
            $query->where(function ($q) use ($term) {
                $q->where('full_name', 'like', '%' . $term . '%')
                    ->orWhere('member_number', $term);
            });
        }

        $members = $query
            ->orderBy('full_name')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'data' => $members->items(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If you want to cache the groups, you can uncomment the next line
        $groups = Cache::remember('Groups_s', 60, function () {
            return Group::where('active', 1)->where('name', '!=', 'all')->get();
        });

        // homecells
        $homecells = Cache::remember('Homecell_all', 60, function () {
            return Homecell::where('active', 1)->get();
        });

        // ministries
        $ministries = Cache::remember('Ministry_all', 60, function () {
            return Ministry::where('active', 1)->get();
        });

        $member_ministries = [];

        // Check if the user has permission to create members
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create member'))) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to create members.');
        }

        // updat the cache for Members
        Cache::forget('Member_all');

        // compute next member number and any skipped numbers in the sequence
        $maxNumber = Member::max('member_number') ?? 0;
        $nextMemberNumber = $maxNumber + 1;
        $existingNumbers = Member::whereNotNull('member_number')->pluck('member_number')->unique()->sort()->toArray();
        $skipped_member_numbers = [];
        for ($i = 1; $i <= $maxNumber; $i++) {
            if (!in_array($i, $existingNumbers)) {
                $skipped_member_numbers[] = $i;
            }
        }

        // Render the create view with groups
        if ($groups->isEmpty()) {
            return redirect()->back()->with('error', 'No active groups found. Please create a group first.');
        }



        return view('cms.members.create', compact('groups', 'homecells', 'ministries', 'member_ministries', 'nextMemberNumber', 'skipped_member_numbers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberRequest $request)
    {
        // Validate the request data
        if (!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create member')) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to create members.');
        }

        $data = $request->validated();
        if (empty($data['member_number'])) {
            $data['member_number'] = (Member::max('member_number') ?? 0) + 1;
        }

        if (!Member::create($data)) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        // add ministries
        if ($request->has('ministries')) {
            $member = Member::latest()->first();
            $ministryIds = Ministry::whereIn('name', $request->input('ministries'))->pluck('id')->toArray();
            $member->ministries()->sync($ministryIds);
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {

        // if ajax request, return json
        if (request()->ajax()) {
            return response()->json($member, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }

        return view('cms.members.view', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit member'))) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to edit members.');
        }
        // Get groups from cache
        $groups = Cache::remember('Groups_s', 60, function () {
            return Group::where('active', 1)->where('name', '!=', 'all')->get();
        });

        // Get homecells from cache
        $homecells = Cache::remember('Homecell_all', 60, function () {
            return Homecell::where('active', 1)->get();
        });

        // ministries
        $ministries = Cache::remember('Ministry_all', 60, function () {
            return Ministry::where('active', 1)->get();
        });

        $member_ministries = [];

        $member_ministries = $member->ministries()->pluck('name')->toArray();

        // also compute next and skipped numbers for the view (useful when editing)
        $maxNumber = Member::max('member_number') ?? 0;
        $nextMemberNumber = $maxNumber + 1;
        $existingNumbers = Member::whereNotNull('member_number')->pluck('member_number')->unique()->sort()->toArray();
        $skipped_member_numbers = [];
        for ($i = 1; $i <= $maxNumber; $i++) {
            if (!in_array($i, $existingNumbers)) {
                $skipped_member_numbers[] = $i;
            }
        }

        return view('cms.members.create', compact('member', 'groups', 'homecells', 'ministries', 'member_ministries', 'nextMemberNumber', 'skipped_member_numbers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberRequest $request, Member $member)
    {
        // Check if the user has permission to update members
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit member'))) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to update members.');
        }

        if (!$member->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Add or sync ministries
        if ($request->has('ministries')) {
            $ministryIds = Ministry::whereIn('name', $request->input('ministries'))->pluck('id')->toArray();
            $member->ministries()->sync($ministryIds);
        } else {
            $member->ministries()->detach();
        }

        // Clear the cache for members
        Cache::forget('Member_all');

        // Optionally, you can clear the cache for the specific member
        Cache::forget('Member_' . $member->id);

        // Optionally, you can clear the cache for the group if needed
        Cache::forget('Group_' . $member->group_id);



        // Redirect the user to the user's profile page
        return redirect()
            ->route('members.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {

        // check permissions
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete member'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete members.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }

        if ($member->delete()) {
            return response()->json([
                'code' => 1,
                'msg' => 'Record deleted successfully'
            ], 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }

        return response()->json([
            'code' => -1,
            'msg' => 'Record did not delete'
        ], 422, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Export members to Excel.
     */
    public function export(Request $request)
    {
        // Check permissions
        if (!auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to export members.');
        }

        $groupIds = $request->input('group_ids', []);
        $groupNames = null;

        if (!empty($groupIds)) {
            $groupNames = \App\Models\Group::whereIn('id', $groupIds)->pluck('name')->implode(', ');
        }


        $filename = 'members_export_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new MemberExport($groupIds ?: null, $groupNames), $filename);
    }
}
