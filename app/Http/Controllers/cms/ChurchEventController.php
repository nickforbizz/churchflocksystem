<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChurchEventRequest;
use App\Models\Event as ChurchEvent;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Cache;

class ChurchEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('ChurchEvent_all', 60, function () {
            return ChurchEvent::orderBy('created_at', 'desc')->get();
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
                ->editColumn('event_date', function ($row) {
                    // check if event_date is null
                    if (is_null($row->event_date)) {
                        return 'N/A';
                    }
                    return date_format($row->event_date, 'Y/m/d');
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('Attending', function ($row) {
                    return $row->totalAttendance();
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = $btn_view = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('events.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('events.destroy', $row->id) . '`, `#tb_events`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }

                    $btn_view = '<a data-toggle="tooltip" 
                                    href="' . route('events.show', $row->id) . '" 
                                    class="btn btn-link btn-success btn-lg" 
                                    data-original-title="View Record">
                                <i class="fa fa-eye"></i>
                            </a>';


                    return $btn_edit . $btn_view . $btn_del;
                })
                ->rawColumns(['action', 'Attending'])
                ->make(true);
        }

        // render view
        return view('cms.events.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        // Check if the user has permission to create ChurchEvent
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create event'))) {
            return redirect()->route('events.index')->with('error', 'You do not have permission to create events.');
        }

        // updat the cache for ChurchEvent
        Cache::forget('ChurchEvent_all');




        return view('cms.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChurchEventRequest $request)
    {
        

        // Validate the request data
        if (!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create event')) {
            return redirect()->route('events.index')->with('error', 'You do not have permission to create events.');
        }

        if (!ChurchEvent::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        // Clear the cache for ChurchEvent after creation
        Cache::forget('ChurchEvent_all');

        return redirect()->route('events.index')->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ChurchEvent $event)
    {
        // Eager load relationships for both JSON and view responses
        $event->load(['user', 'event_attendances.member', 'event_attendances.user']);

        // Handle AJAX requests (e.g., from the calendar modal)
        if ($request->wantsJson()) {
            return response()->json($event);
        }

        // For the full view page, get members who are not yet in attendance for this event
        $attendingMemberIds = $event->event_attendances->pluck('member_id')->all();
        $members = \App\Models\Member::where('active', 1)
                         ->whereNotIn('id', $attendingMemberIds)
                         ->get(['id', 'full_name']);

        return view('cms.events.view', compact('event', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChurchEvent $event)
    {

        $churchEvent = $event;
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit event'))) {
            return redirect()->route('events.index')->with('error', 'You do not have permission to edit events.');
        }


        return view('cms.events.create', compact('churchEvent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChurchEventRequest $request, ChurchEvent $event)
    {
        // Check if the user has permission to update events
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit event'))) {
            return redirect()->route('events.index')->with('error', 'You do not have permission to update events.');
        }

        if (!$event->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Clear the cache for ChurchEvent
        Cache::forget('ChurchEvent_all');



        // Redirect the user to the user's profile page
        return redirect()
            ->route('events.index')
            ->with('success', 'Record updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchEvent $event)
    {
        // Check if the user has permission to delete events
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete event'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete events.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }

        
        if ($event->delete()) {
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




    public function showCalendar()
    {
        $selectedYear = request()->get('year', Carbon::now()->year);
        $currentYear = Carbon::now()->year;
        // Generate a range of years, e.g., 5 years before and 5 years after the current year
        $years = range($currentYear - 5, $currentYear + 5);

        return view('cms.events.calender', compact('selectedYear', 'years'));
    }

    public function calendarEvents()
    {
        // if the year is not provided, get full data
        $year = request()->query('year');
        $query = ChurchEvent::query();

        if ($year) {
            $query->whereYear('event_date', $year);
        }

        $events = $query->get()->map(function ($event) {
            return [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->event_date ? $event->event_date->toDateString() : null,
            'description' => $event->description,
            'location' => $event->location,
            'created_by' => $event->user->name ?? 'N/A',
            ];
        });

        return response()->json($events);
    }

    // Downloadable calendar events as CSV
    public function downloadAttendanceCsv()
    {
        dd('eed');
        $year = request('year', Carbon::now()->year);
        $events = ChurchEvent::whereYear('event_date', $year)->get();
        $filename = 'church_events_'.$year.'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function () use ($events) {
            $file = fopen('php://output', 'w');
            // Add the header of the CSV
            fputcsv($file, ['ID', 'Title', 'Description', 'Event Date', 'Location', 'Created By', 'Created At']);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->title,
                    $event->description,
                    $event->event_date ? $event->event_date->toDateString() : '',
                    $event->location,
                    $event->user->name ?? 'N/A',
                    $event->created_at ? $event->created_at->toDateTimeString() : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
