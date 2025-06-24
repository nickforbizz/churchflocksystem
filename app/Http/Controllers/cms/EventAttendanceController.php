<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventAttendanceRequest;
use App\Models\EventAttendance;
use App\Models\Member;
use App\Models\Event as ChurchEvent;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Cache;

class EventAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('EventAttendance_all', 60, function () {
            return EventAttendance::orderBy('created_at', 'desc')->get();
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
                ->editColumn('member_id', function ($row) {
                    return $row->member->full_name ?? 'N/A';
                })
                ->editColumn('event_id', function ($row) {
                    return $row->event->title ?? 'N/A';
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('eventAttendance.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('eventAttendance.destroy', $row->id) . '`, `#tb_eventAttendance`)"
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
        return view('cms.event_attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If you want to cache the members, you can uncomment the next line
        $members = Cache::remember('Member_all', 60, function () {
            return Member::where('active', 1)->get();
        });

        $events = Cache::remember('ChurchEvent_all', 60, function () {
            return ChurchEvent::where('active', 1)->get();
        });

        // Check if the user has permission to create eventAttendance
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create event_attendance'))) {
            return redirect()->route('eventAttendance.index')->with('error', 'You do not have permission to create eventAttendance.');
        }

        // updat the cache for Members
        Cache::forget('EventAttendance_all');

        // Render the create view with members
        if ($members->isEmpty()) {
            return redirect()->back()->with('error', 'No active members found. Please create a member first.');
        }

        if ($events->isEmpty()) {
            return redirect()->back()->with('error', 'No active events found. Please create a event first.');
        }



        return view('cms.event_attendance.create', compact('members', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventAttendanceRequest $request)
    {
        // Validate the request data
        if (!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create event_attendance')) {
            return redirect()->route('eventAttendance.index')->with('error', 'You do not have permission to create eventAttendance.');
        }


        if(!EventAttendance::create($request->validated())){
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(EventAttendance $eventAttendance)
    {
        return response()
            ->json($eventAttendance, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventAttendance $eventAttendance)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit event_attendance'))) {
            return redirect()->route('eventAttendance.index')->with('error', 'You do not have permission to edit event_attendance.');
        }


        $members = Cache::remember('Member_all', 60, function () {
            return Member::where('active', 1)->get();
        });

        $events = Cache::remember('ChurchEvent_all', 60, function () {
            return ChurchEvent::where('active', 1)->get();
        });


        return view('cms.event_attendance.create', compact('members', 'events', 'eventAttendance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventAttendanceRequest $request, EventAttendance $eventAttendance)
    {
        // Check if the user has permission to update members
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit event_attendance'))) {
            return redirect()->route('eventAttendance.index')->with('error', 'You do not have permission to update event_attendance.');
        }

        if(!$eventAttendance->update($request->validated())){
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Clear the cache for members
        Cache::forget('EventAttendance_all');

        // Optionally, you can clear the cache for the specific member
        Cache::forget('EventAttendance_' . $eventAttendance->id);

        // Clear the cache for ChurchEvent
        Cache::forget('ChurchEvent_all');

        // Clear the cache for Member
        Cache::forget('Member_all');

       

        // Redirect the user to the user's profile page
        return redirect()
            ->route('eventAttendance.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventAttendance $eventAttendance)
    {
        if ($eventAttendance->delete()) {
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
