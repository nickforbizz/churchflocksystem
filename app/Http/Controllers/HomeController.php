<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Cache::put('name', auth()->user(), 1000);
        // dd(
        //     Cache::get('name')
        // );
        return view('home');
    }


    /**
     * Show the application cms.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cms()
    {
         $event_attendance = DB::table('vw_event_participation_rate')->get();
         $attendance = DB::table('vw_event_attendance_summary')->get();
         $membership = DB::table('vw_group_membership_summary')->get();
         $donations = DB::table('vw_donations_monthly_trend')->get();
         $donations_summary = DB::table('vw_donations_summary')->get();

        //  dd($event_attendance);
        return view('cms.index', compact('attendance', 'membership', 'donations', 'donations_summary', 'event_attendance'));
    }
}
