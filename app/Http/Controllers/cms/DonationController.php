<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Http\Requests\DonationRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return datatable of the makes available
        $data = Cache::remember('Donation_all', 60, function () {
            return Donation::orderBy('created_at', 'desc')->get();
        });
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('member_id', function ($row) {
                    return $row->member->full_name ?? 'N/A';
                })                
                ->editColumn('created_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->editColumn('date', function ($row) {
                    if (is_null($row->date)) {
                        return 'N/A';
                    }
                    return date_format($row->date, 'Y/m/d');
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
                                        href="' . route('donations.edit', $row->id) . '" 
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
                                    onclick="delRecord(`' . $row->id . '`, `' . route('donations.destroy', $row->id) . '`, `#tb_donations`)"
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
        return view('cms.donations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create donation'))) {
            return redirect()->route('donations.index')->with('error', 'You do not have permission to create donations.');
        }

        $members = Cache::remember('Member_all', 60, function () {
            return Member::where('active', 1)->get();
        });

        if ($members->isEmpty()) {
            return redirect()->back()->with('error', 'No active members found. Please create a member first.');
        }
        
        return view('cms.donations.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DonationRequest $request)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('create donation'))) {
            return redirect()->route('donations.index')->with('error', 'You do not have permission to create donations.');
        }

        if (!Donation::create($request->validated())) {
            return redirect()->back()->with('error', 'Failed to create record. Please try again.');
        }

        return redirect()->back()->with('success', 'Record Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        return response()
            ->json($donation, 200, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit donation'))) {
            return redirect()->route('donations.index')->with('error', 'You do not have permission to update donations.');
        }


        $members = Cache::remember('Member_all', 60, function () {
            return Member::where('active', 1)->get();
        });

        if ($members->isEmpty()) {
            return redirect()->back()->with('error', 'No active members found. Please create a member first.');
        }

        return view('cms.donations.create', compact('donation', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DonationRequest $request, Donation $donation)
    {
        // Check if the user has permission to update donations
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('edit donation'))) {
            return redirect()->route('donations.index')->with('error', 'You do not have permission to update donations.');
        }

        if (!$donation->update($request->validated())) {
            return redirect()->back()->with('error', 'Failed to update record. Please try again.');
        }

        // Redirect the user to the user's profile page
        return redirect()
            ->route('donations.index')
            ->with('success', 'Record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        // Check if the user has permission to delete donations
        if ((!auth()->user()->hasAnyRole(['admin', 'superadmin']) || !auth()->user()->hasPermissionTo('delete donation'))) {
            return response()->json([
                'code' => -1,
                'msg' => 'You do not have permission to delete donations.'
            ], 403, ['JSON_PRETTY_PRINT' => JSON_PRETTY_PRINT]);
        }
        if ($donation->delete()) {
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
