<?php

namespace App\Http\Controllers\cms;

use App\Exports\GroupExport;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Group_all', 60, function () {
            return Group::withCount('members')->orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->editColumn('name', function ($row) {
                    // return name with a red badge showing the count of members in the group, if group is all, show total active members count
                    if (strtolower($row->name) === 'all') {
                        $membersCount = Cache::remember('active_members_count', 60, function () {
                            return \App\Models\Member::where('active', 1)->count();
                        });
                    } else {
                        $membersCount = $row->members_count ?? 0;
                    }
                    $badge = '<span class="badge badge-sm badge-pill badge-danger ml-2 notification" style="font-size: 0.75rem; padding: .25em .4em;">' . $membersCount . '</span>';
                    return e($row->name ?? 'N/A') . ' ' . $badge;
                })
                ->editColumn('created_by', function ($row) {
                    return e($row->user->name ?? 'N/A');
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('groups.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('groups.destroy', $row->id) . '`, `#tb_groups`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action', 'name', 'created_at'])
                ->make(true);
        }

        // render view
        return view('cms.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create group'))) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to create groups.');
        }
        
        return view('cms.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create group'))) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to create groups.');
        }

        if (!Group::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return response()
            ->json($group, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit group'))) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to update groups.');
        }

        return view('cms.groups.create', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, Group $group)
    {
        // Check if the user has permission to update groups
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit group'))) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to update groups.');
        }

        if (!$group->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('groups.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(group $group)
    {
        // Check if the user has permission to delete groups
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete group'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete groups.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($group->delete()) {
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
     * Get list of active groups for dropdowns/select elements.
     */
    public function list()
    {
        $groups = Cache::remember('Groups_list', 60, function () {
            return Group::where('active', 1)
                ->whereRaw('LOWER(name) != ?', ['all'])
                ->has('members')
                ->orderBy('name', 'asc')
                ->get(['id', 'name']);
        });

        return response()->json(['data' => $groups]);
    }

    /**
     * Export groups to Excel.
     */
    public function export()
    {
        // Check permissions
        if (!auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            return redirect()->route('groups.index')->with('error', 'You do not have permission to export groups.');
        }

        $filename = 'groups_export_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new GroupExport(), $filename);
    }
}
