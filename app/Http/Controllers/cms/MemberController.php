<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Models\Group;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Cache;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Member_all', 60, function () {
            return Member::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    if (is_null($row->created_at)) {
                        return 'N/A';
                    }

                    return date_format($row->created_at, 'Y/m/d H:i');
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
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('members.edit', $row->id) . '" 
                                        class="btn btn-link btn-primary btn-lg" 
                                        data-original-title="Edit Record">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    }

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
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // render view
        return view('cms.members.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If you want to cache the groups, you can uncomment the next line
        $groups = Cache::remember('Group_all', 60, function () {
            return Group::where('active', 1)->get();
        });

        // Check if the user has permission to create members
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create member'))) {
            return redirect()->route('members.index')->with('error', 'You do not have permission to create members.');
        }

        // updat the cache for Members
        Cache::forget('Member_all');

        // Render the create view with groups
        if ($groups->isEmpty()) {
            return redirect()->back()->with('error', 'No active groups found. Please create a group first.');
        }



        return view('cms.members.create', compact('groups'));
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


        if(!Member::create($request->validated())){
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return response()
            ->json($member, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
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
        $groups = Cache::remember('Group_all', 60, function () {
            return Group::where('active', 1)->get();
        });
        return view('cms.members.create', compact('member', 'groups'));
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

        if(!$member->update($request->validated())){
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
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
}
