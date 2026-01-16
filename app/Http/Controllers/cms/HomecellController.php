<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Homecell;
use App\Http\Requests\HomecellRequest;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomecellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Homecell_all', 60, function () {
            return Homecell::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    // check date is not null
                    if ($row->created_at) {
                        return date_format($row->created_at, 'Y/m/d H:i');
                    }
                    return 'N/A';
                })
                ->editColumn('date_joined', function ($row) {
                    if ($row->date_joined) {
                        return date_format($row->date_joined, 'Y/m/d H:i');
                    }
                    return 'N/A';
                })
                ->editColumn('date_officially_received', function ($row) {
                    if (!$row->date_officially_received) {
                        return 'N/A';
                    }
                    return date_format($row->date_officially_received, 'Y/m/d H:i');
                })
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('homecells.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('homecells.destroy', $row->id) . '`, `#tb_homecells`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action', 'created_by'])
                ->make(true);
        }

        // render view
        return view('cms.homecells.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create homecell'))) {
            return redirect()->route('homecells.index')->with('error', 'You do not have permission to create homecells.');
        }
        
        return view('cms.homecells.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HomecellRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create homecell'))) {
            return redirect()->route('homecells.index')->with('error', 'You do not have permission to create homecells.');
        }

        if (!Homecell::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Homecell $homecell)
    {
        return response()
            ->json($homecell, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Homecell $homecell)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit homecell'))) {
            return redirect()->route('homecells.index')->with('error', 'You do not have permission to update homecells.');
        }

        return view('cms.homecells.create', compact('homecell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HomecellRequest $request, Homecell $homecell)
    {
        // Check if the user has permission to update homecells
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit homecell'))) {
            return redirect()->route('homecells.index')->with('error', 'You do not have permission to update homecells.');
        }

        if (!$homecell->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('homecells.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homecell $homecell)
    {
        // Check if the user has permission to delete homecells
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete homecell'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete homecells.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($homecell->delete()) {
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
