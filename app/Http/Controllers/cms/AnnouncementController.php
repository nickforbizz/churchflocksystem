<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use App\Models\Announcement;
use App\Models\Group; // Added for sendToGroups
use App\Http\Requests\AnnouncementRequest;
use DataTables;
use App\Jobs\SendAnnouncementToGroupMembers; // Added for sendToGroups

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Announcement_all', 60, function () {
            return Announcement::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()              
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->editColumn('title', function ($row) {
                    return Str::limit($row->title, 10, '...');
                })
                ->editColumn('starts_at', function ($row) {
                    if (is_null($row->starts_at)) {
                        return 'N/A';
                    }
                    return date_format($row->starts_at, 'Y/m/d H:i');
                })
                ->editColumn('ends_at', function ($row) {
                    if (is_null($row->ends_at)) {
                        return 'N/A';
                    }
                    return date_format($row->ends_at, 'Y/m/d H:i');
                })
                ->editColumn('created_at', function ($row) {
                    if (is_null($row->created_at)) {
                        return 'N/A';
                    }
                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('announcements.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('announcements.destroy', $row->id) . '`, `#tb_announcements`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    $btn_view = '<a data-toggle="tooltip" 
                                    href="' . route('announcements.show', $row->id) . '" 
                                    class="btn btn-link btn-success btn-lg" 
                                    data-original-title="View Record">
                                <i class="fa fa-eye"></i>
                            </a>';


                    return $btn_edit . $btn_view . $btn_del;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // render view
        return view('cms.announcements.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create announcement'))) {
            return redirect()->route('announcements.index')->with('error', 'You do not have permission to create announcements.');
        }

        
        return view('cms.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnnouncementRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create announcement'))) {
            return redirect()->route('announcements.index')->with('error', 'You do not have permission to create announcements.');
        }

        if (!Announcement::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Announcement $announcement)
    {
        // Eager load relationships for both JSON and view responses
        $announcement->load(['user', 'groups']);

        if ($request->wantsJson()) {
            return response()
            ->json($announcement, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }

        // Fetch active groups for the "Send to Groups" modal.
        // It's better to pass this from the controller than to query in the view.
        $groups = Group::where('active', 1)->orderBy('name')->get();

        return view('cms.announcements.view', compact('announcement', 'groups'));

        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit announcement'))) {
            return redirect()->route('announcements.index')->with('error', 'You do not have permission to update announcements.');
        }



        return view('cms.announcements.create', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnnouncementRequest $request, Announcement $announcement)
    {
        // Check if the user has permission to update announcements
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit announcement'))) {
            return redirect()->route('announcements.index')->with('error', 'You do not have permission to update announcements.');
        }

        if (!$announcement->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('announcements.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Check if the user has permission to delete announcements
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete announcement'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete announcements.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($announcement->delete()) {
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
     * Send the specified announcement to selected groups.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Announcement $announcement
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendToGroups(Request $request, Announcement $announcement)
    {
        // Check permission
        if (!auth()->user()->hasPermissionTo('send announcement to groups')) {
            return redirect()->back()->with('error', 'You do not have permission to send announcements to groups.');
        }

        $request->validate([
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'exists:groups,id',
            'send_via' => 'required|array|min:1',
            'send_via.*' => 'in:email,sms',
        ]);

        $groupIds = $request->input('group_ids');
        $sendVia = $request->input('send_via');
        $message = $request->input('message');

        $syncData = array_fill_keys($groupIds, ['created_by' => auth()->id()]);
        // Sync the groups with the announcement, providing the extra pivot data.
        // This assumes you have a pivot table (e.g., announcement_group) with a 'created_by' column.
        $announcement->groups()->sync($syncData);

        dispatch(new SendAnnouncementToGroupMembers($announcement, $groupIds, $sendVia, $message))
            ->onQueue('announcements') // Specify the queue name if you have a specific queue for announcements
            ->delay(now()->addSeconds(5)); // Optional: delay the job by 5 seconds

        return redirect()->back()->with('success', 'Announcement sending process initiated. Members will receive notifications shortly.');
    }
}
