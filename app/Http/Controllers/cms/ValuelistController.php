<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValuelistRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Valuelist;


class ValuelistController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Valuelist_all', 60, function () {
            return Valuelist::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
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
                    $btn_edit = $btn_del = null;
                    if (auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $row->created_by) {
                        $btn_edit = '<a data-toggle="tooltip" 
                                        href="' . route('valuelists.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('valuelists.destroy', $row->id) . '`, `#tb_valuelists`)"
                                    data-original-title="Remove">
                                <i class="fa fa-times"></i>
                            </button>';
                    }
                    return $btn_edit . $btn_del;
                })
                ->rawColumns(['action','created_by', 'created_at'])
                ->make(true);
        }

        // render view
        return view('cms.valuelists.index');
    }

    /**
     * Search types for Select2 dropdown with tagging support
     */
    public function searchTypes(Request $request)
    {
        $search = $request->get('q', '');
        
        $query = Valuelist::select('type')
            ->distinct()
            ->orderBy('type', 'desc');
        
        if (!empty($search)) {
            $query->where('type', 'like', "%{$search}%");
        }
        
        $types = $query->limit(10)->pluck('type');
        
        $results = $types->map(function ($type) {
            return ['id' => $type, 'text' => $type];
        });
        
        return response()->json(['results' => $results]);
    }

    /**
     * Get the next index for a given type
     */
    public function getNextIndex(Request $request)
    {
        $type = $request->get('type', '');
        
        if (empty($type)) {
            return response()->json(['nextIndex' => 1]);
        }
        
        $maxIndex = Valuelist::where('type', $type)->max('index');
        $nextIndex = $maxIndex ? $maxIndex + 1 : 1;
        
        return response()->json(['nextIndex' => $nextIndex]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create valuelist'))) {
            return redirect()->route('valuelists.index')->with('error', 'You do not have permission to create valuelists.');
        }

        // Get distinct types for initial dropdown (10 types ordered desc)
        $types = Valuelist::select('type', 'id')
            ->distinct()
            ->orderBy('type', 'desc')
            ->limit(10)
            ->pluck('type');

            // dd($types);
        
        return view('cms.valuelists.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ValuelistRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create valuelist'))) {
            return redirect()->route('valuelists.index')->with('error', 'You do not have permission to create valuelists.');
        }

        if (!Valuelist::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Valuelist $valuelist)
    {
        return response()
            ->json($valuelist, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }
   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Valuelist $valuelist)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit valuelist'))) {
            return redirect()->route('valuelists.index')->with('error', 'You do not have permission to update valuelists.');
        }

        // Get distinct types for dropdown (10 types ordered desc)
        $types = Valuelist::select('type')
            ->distinct()
            ->orderBy('type', 'desc')
            ->limit(10)
            ->pluck('type');

        return view('cms.valuelists.create', compact('valuelist', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ValuelistRequest $request, Valuelist $valuelist)
    {
        // Check if the user has permission to update valuelists
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit valuelist'))) {
            return redirect()->route('valuelists.index')->with('error', 'You do not have permission to update valuelists.');
        }

        if (!$valuelist->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('valuelists.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Valuelist $valuelist)
    {
        // Check if the user has permission to delete valuelists
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete valuelist'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete valuelists.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($valuelist->delete()) {
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

   
