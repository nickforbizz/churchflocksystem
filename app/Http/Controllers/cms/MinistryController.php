<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Http\Requests\MinistryRequest;
use App\Models\MemberHasMinistry;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MinistryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Ministry_all', 60, function () {
            return Ministry::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return date_format($row->created_at, 'Y/m/d H:i');
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('members_count', function ($row) {
                    return $row->membersCount();
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('ministries.edit', $row->id) . '" 
                                        class="btn btn-link btn-primary btn-sm" 
                                        data-original-title="Edit Record">
                                    <i class="fa fa-edit"></i>
                                </a>';
                    }

                    if (auth()->user()->hasRole('superadmin')) {
                        $btn_del = '<button type="button" 
                                    data-toggle="tooltip" 
                                    title="" 
                                    class="btn btn-link btn-danger btn-sm" 
                                    onclick="delRecord(`' . $row->id . '`, `' . route('ministries.destroy', $row->id) . '`, `#tb_ministries`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action', 'created_by', 'members_count'])
                ->make(true);
        }

        // render view
        return view('cms.ministries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create ministry'))) {
            return redirect()->route('ministries.index')->with('error', 'You do not have permission to create ministries.');
        }
        
        return view('cms.ministries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MinistryRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create ministry'))) {
            return redirect()->route('ministries.index')->with('error', 'You do not have permission to create ministries.');
        }

        if (!Ministry::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ministry $ministry)
    {
        return response()
            ->json($ministry, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ministry $ministry)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit ministry'))) {
            return redirect()->route('ministries.index')->with('error', 'You do not have permission to update ministries.');
        }

        return view('cms.ministries.create', compact('ministry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MinistryRequest $request, Ministry $ministry)
    {
        // Check if the user has permission to update ministries
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit ministry'))) {
            return redirect()->route('ministries.index')->with('error', 'You do not have permission to update ministries.');
        }

        if (!$ministry->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('ministries.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ministry $ministry)
    {
        // Check if the user has permission to delete ministries
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete ministry'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete ministries.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($ministry->delete()) {
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
