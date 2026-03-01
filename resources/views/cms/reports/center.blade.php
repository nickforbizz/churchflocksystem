@extends('layouts.cms')

@section('content')
<div class="page-inner">
    {{-- Page Header --}}
    <div class="page-header">
        <h4 class="page-title">Report Center</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('cms') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Reports</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Report Center</a>
            </li>
        </ul>
    </div>

    {{-- Summary Stats Cards --}}
    <div class="row" id="summary-stats">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Members</p>
                                <h4 class="card-title" id="stat-total-members">-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">New This Month</p>
                                <h4 class="card-title" id="stat-new-members">-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Donations (Month)</p>
                                <h4 class="card-title" id="stat-donations">-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Growth Rate</p>
                                <h4 class="card-title" id="stat-growth">-</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Sidebar: Report Types & Filters --}}
        <div class="col-md-4">
            {{-- Report Type Selection --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-file-export mr-2"></i> Select Report Type
                    </div>
                </div>
                <div class="card-body">
                    <form id="export-form" method="GET" action="{{ route('report-center.export') }}" target="_blank">
                        <div class="form-group">
                            <select class="form-control" name="report_type" id="report_type_select">
                                @foreach($exportTypes as $categoryKey => $category)
                                    <optgroup label="{{ $category['label'] }}">
                                        @foreach($category['exports'] as $export)
                                            <option value="{{ $export['key'] }}" 
                                                data-filters="{{ json_encode($export['available_filters']) }}"
                                                data-description="{{ $export['description'] }}"
                                                {{ $loop->parent->first && $loop->first ? 'selected' : '' }}>
                                                {{ $export['label'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-2" id="report-description">
                                {{ $exportTypes[array_key_first($exportTypes)]['exports'][0]['description'] ?? '' }}
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Filters Panel --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-filter mr-2"></i> Filters
                    </div>
                    <button class="btn btn-sm btn-link ml-auto" type="button" id="clear-filters">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                </div>
                <div class="card-body">
                    {{-- Date Range Filter --}}
                    <div class="form-group filter-section" data-filter="date_range">
                        <label class="form-label">Date Range</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" 
                                    name="date_from" id="date_from" form="export-form" placeholder="From">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control form-control-sm" 
                                    name="date_to" id="date_to" form="export-form" placeholder="To">
                            </div>
                        </div>
                    </div>

                    {{-- Year/Month Filter --}}
                    <div class="form-group filter-section" data-filter="month">
                        <label class="form-label">Period</label>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-control form-control-sm" name="year" id="filter_year" form="export-form">
                                    <option value="">All Years</option>
                                    @foreach($filterOptions['years'] as $year)
                                        <option value="{{ $year['value'] }}">{{ $year['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-control form-control-sm" name="month" id="filter_month" form="export-form">
                                    <option value="">All Months</option>
                                    @foreach($filterOptions['months'] as $month)
                                        <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Groups Filter --}}
                    <div class="form-group filter-section" data-filter="groups">
                        <label class="form-label">Groups</label>
                        <select class="form-control select2-multiple" name="group_ids[]" id="filter_groups" 
                            form="export-form" multiple>
                            @foreach($filterOptions['groups'] as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Homecells Filter --}}
                    <div class="form-group filter-section" data-filter="homecells">
                        <label class="form-label">Homecells</label>
                        <select class="form-control select2-multiple" name="homecell_ids[]" id="filter_homecells" 
                            form="export-form" multiple>
                            @foreach($filterOptions['homecells'] as $homecell)
                                <option value="{{ $homecell->id }}">{{ $homecell->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Ministries Filter --}}
                    <div class="form-group filter-section" data-filter="ministries">
                        <label class="form-label">Ministries</label>
                        <select class="form-control select2-multiple" name="ministry_ids[]" id="filter_ministries" 
                            form="export-form" multiple>
                            @foreach($filterOptions['ministries'] as $ministry)
                                <option value="{{ $ministry->id }}">{{ $ministry->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Events Filter --}}
                    <div class="form-group filter-section" data-filter="events">
                        <label class="form-label">Events</label>
                        <select class="form-control select2-multiple" name="event_ids[]" id="filter_events" 
                            form="export-form" multiple>
                            @foreach($filterOptions['events'] as $event)
                                <option value="{{ $event->id }}">{{ $event->title }} ({{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gender Filter --}}
                    <div class="form-group filter-section" data-filter="gender">
                        <label class="form-label">Gender</label>
                        <select class="form-control form-control-sm" name="gender" id="filter_gender" form="export-form">
                            <option value="">All Genders</option>
                            @foreach($filterOptions['genders'] as $gender)
                                <option value="{{ $gender['value'] }}">{{ $gender['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Marital Status Filter --}}
                    <div class="form-group filter-section" data-filter="marital_status">
                        <label class="form-label">Marital Status</label>
                        <select class="form-control form-control-sm" name="marital_status" id="filter_marital" form="export-form">
                            <option value="">All</option>
                            @foreach($filterOptions['marital_statuses'] as $status)
                                <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Donation Purpose Filter --}}
                    <div class="form-group filter-section" data-filter="purpose">
                        <label class="form-label">Donation Purpose</label>
                        <select class="form-control form-control-sm" name="donation_purpose" id="filter_purpose" form="export-form">
                            <option value="">All Purposes</option>
                            @foreach($filterOptions['donation_purposes'] as $purpose)
                                <option value="{{ $purpose['value'] }}">{{ $purpose['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Donation Method Filter --}}
                    <div class="form-group filter-section" data-filter="method">
                        <label class="form-label">Payment Method</label>
                        <select class="form-control form-control-sm" name="donation_method" id="filter_method" form="export-form">
                            <option value="">All Methods</option>
                            @foreach($filterOptions['donation_methods'] as $method)
                                <option value="{{ $method['value'] }}">{{ $method['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Amount Range Filter --}}
                    <div class="form-group filter-section" data-filter="amount">
                        <label class="form-label">Amount Range</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                    name="amount_min" id="amount_min" form="export-form" placeholder="Min">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                    name="amount_max" id="amount_max" form="export-form" placeholder="Max">
                            </div>
                        </div>
                    </div>

                    {{-- Age Range Filter --}}
                    <div class="form-group filter-section" data-filter="age">
                        <label class="form-label">Age Range</label>
                        <div class="row">
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                    name="age_min" id="age_min" form="export-form" placeholder="Min" min="0">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control form-control-sm" 
                                    name="age_max" id="age_max" form="export-form" placeholder="Max" max="120">
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Status Filter --}}
                    <div class="form-group filter-section" data-filter="status">
                        <label class="form-label">Attendance Status</label>
                        <select class="form-control form-control-sm" name="attendance_status" id="filter_status" form="export-form">
                            <option value="">All Statuses</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>

                    {{-- Attendance Type Filter --}}
                    <div class="form-group filter-section" data-filter="type">
                        <label class="form-label">Attendance Type</label>
                        <select class="form-control form-control-sm" name="attendance_type" id="filter_type" form="export-form">
                            <option value="">All Types</option>
                            <option value="in-person">In-Person</option>
                            <option value="online">Online</option>
                        </select>
                    </div>

                    {{-- Active Only Toggle --}}
                    <div class="form-group filter-section" data-filter="active">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="filter_active" 
                                name="active_only" value="1" form="export-form" checked>
                            <label class="custom-control-label" for="filter_active">Active Records Only</label>
                        </div>
                    </div>

                    {{-- Export Button --}}
                    <div class="mt-4">
                        <button type="submit" form="export-form" class="btn btn-primary btn-block">
                            <i class="fas fa-download mr-2"></i> Download Report
                        </button>
                        <button type="button" id="preview-btn" class="btn btn-outline-secondary btn-block mt-2">
                            <i class="fas fa-chart-bar mr-2"></i> Preview Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Charts & Preview --}}
        <div class="col-md-8">
            {{-- Chart Tabs --}}
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills" id="chart-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="membership-tab" data-toggle="pill" href="#membership-charts" role="tab">
                                <i class="fas fa-users mr-1"></i> Membership
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="donations-tab" data-toggle="pill" href="#donation-charts" role="tab">
                                <i class="fas fa-money-bill-wave mr-1"></i> Donations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attendance-tab" data-toggle="pill" href="#attendance-charts" role="tab">
                                <i class="fas fa-calendar-check mr-1"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="demographics-tab" data-toggle="pill" href="#demographics-charts" role="tab">
                                <i class="fas fa-chart-pie mr-1"></i> Demographics
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="chart-content">
                        {{-- Membership Charts --}}
                        <div class="tab-pane fade show active" id="membership-charts" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Membership Growth Trend</h6>
                                    <div id="chart-membership-growth" style="height: 300px;"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Members by Group</h6>
                                    <div id="chart-members-by-group" style="height: 250px;"></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Spiritual Growth Progress</h6>
                                    <div id="chart-spiritual-growth" style="height: 250px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Donation Charts --}}
                        <div class="tab-pane fade" id="donation-charts" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Donation Trends</h6>
                                    <div id="chart-donation-trend" style="height: 300px;"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">By Purpose</h6>
                                    <div id="chart-donations-by-purpose" style="height: 250px;"></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">By Payment Method</h6>
                                    <div id="chart-donations-by-method" style="height: 250px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Charts --}}
                        <div class="tab-pane fade" id="attendance-charts" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Weekly Attendance Trend</h6>
                                    <div id="chart-attendance-trend" style="height: 300px;"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Attendance Summary</h6>
                                    <div id="chart-attendance-summary" style="height: 250px;"></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Attendance Statistics</h6>
                                    <div id="attendance-stats" class="p-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Records:</span>
                                            <strong id="att-total">-</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Present:</span>
                                            <strong class="text-success" id="att-present">-</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Absent:</span>
                                            <strong class="text-danger" id="att-absent">-</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Excused:</span>
                                            <strong class="text-warning" id="att-excused">-</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span>Attendance Rate:</span>
                                            <strong class="text-primary" id="att-rate">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Demographics Charts --}}
                        <div class="tab-pane fade" id="demographics-charts" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Gender Distribution</h6>
                                    <div id="chart-gender" style="height: 280px;"></div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Marital Status</h6>
                                    <div id="chart-marital" style="height: 280px;"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">Age Distribution</h6>
                                    <div id="chart-age" style="height: 280px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-bolt mr-2"></i> Quick Reports
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'members', 'active_only' => 1]) }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-users d-block mb-2" style="font-size: 24px;"></i>
                                All Active Members
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'birthdays', 'month' => now()->month]) }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-birthday-cake d-block mb-2" style="font-size: 24px;"></i>
                                This Month's Birthdays
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'donations', 'date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-hand-holding-usd d-block mb-2" style="font-size: 24px;"></i>
                                Monthly Donations
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'member-demographics']) }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-chart-pie d-block mb-2" style="font-size: 24px;"></i>
                                Demographics Summary
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'groups']) }}" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-layer-group d-block mb-2" style="font-size: 24px;"></i>
                                All Groups
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('report-center.export', ['report_type' => 'donation-summary', 'date_from' => now()->startOfYear()->format('Y-m-d'), 'date_to' => now()->format('Y-m-d')]) }}" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-file-invoice-dollar d-block mb-2" style="font-size: 24px;"></i>
                                YTD Donation Summary
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    #report_type_select {
        font-weight: 500;
    }
    #report_type_select optgroup {
        font-weight: 600;
        color: #4472C4;
    }
    .filter-section {
        transition: all 0.3s ease;
    }
    .filter-section.hidden {
        display: none;
    }
    .card-stats .col-icon {
        flex: 0 0 auto;
        width: 60px;
    }
    .bubble-shadow-small {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .icon-primary { background: linear-gradient(135deg, #4472C4 0%, #2f5496 100%); color: white; }
    .icon-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; }
    .icon-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); color: white; }
    .icon-warning { background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%); color: white; }
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 32px;
        font-size: 0.875rem;
    }
    .nav-pills .nav-link {
        border-radius: 20px;
        padding: 8px 16px;
        margin-right: 5px;
    }
    .nav-pills .nav-link.active {
        background-color: #4472C4;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for multi-select dropdowns
    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select options...',
        allowClear: true,
        width: '100%'
    });

    // Filter visibility based on report type
    const filterMapping = {
        'groups': ['groups'],
        'homecells': ['homecells'],
        'ministries': ['ministries'],
        'events': ['events'],
        'gender': ['gender'],
        'marital_status': ['marital_status'],
        'age': ['age'],
        'active': ['active'],
        'date_range': ['date_range'],
        'month': ['month'],
        'purpose': ['purpose'],
        'method': ['method'],
        'amount': ['amount'],
        'member': ['member'],
        'status': ['status'],
        'type': ['type'],
        'upcoming_days': ['upcoming_days']
    };

    function updateFilterVisibility() {
        const selectedOption = $('#report_type_select option:selected');
        const availableFilters = selectedOption.data('filters') || [];
        const description = selectedOption.data('description') || '';
        
        // Update description text
        $('#report-description').text(description);
        
        $('.filter-section').each(function() {
            const filterType = $(this).data('filter');
            if (availableFilters.includes(filterType) || filterType === 'active') {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    }

    $('#report_type_select').change(updateFilterVisibility);
    updateFilterVisibility();

    // Clear all filters
    $('#clear-filters').click(function() {
        $('#export-form')[0].reset();
        $('.select2-multiple').val(null).trigger('change');
        $('#filter_active').prop('checked', true);
    });

    // Preview button - update charts with current filters
    $('#preview-btn').click(function() {
        loadAllCharts();
    });

    // Chart instances
    let charts = {};

    // Load summary stats
    function loadSummaryStats() {
        $.get('{{ route("report-center.chart-data") }}', { chart_type: 'summary_stats' }, function(response) {
            if (response.success) {
                const data = response.data;
                $('#stat-total-members').text(numberFormat(data.total_members));
                $('#stat-new-members').text(numberFormat(data.new_members_this_month));
                $('#stat-donations').text('KES ' + numberFormat(data.donations_this_month));
                
                const growth = data.member_growth_rate;
                const growthIcon = growth >= 0 ? '↑' : '↓';
                const growthClass = growth >= 0 ? 'text-success' : 'text-danger';
                $('#stat-growth').html(`<span class="${growthClass}">${growthIcon} ${Math.abs(growth)}%</span>`);
            }
        });
    }

    // Load membership growth chart
    function loadMembershipGrowthChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'membership_growth' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: [{
                        name: 'Total Members',
                        data: response.data.map(d => d.count)
                    }],
                    chart: { type: 'area', height: 300, toolbar: { show: false } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 }
                    },
                    xaxis: { categories: response.data.map(d => d.month_short) },
                    colors: ['#4472C4'],
                    tooltip: { y: { formatter: val => numberFormat(val) + ' members' } }
                };
                renderChart('chart-membership-growth', options);
            }
        });
    }

    // Load members by group chart
    function loadMembersByGroupChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'members_by_group' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: [{ name: 'Members', data: response.data.map(d => d.members_count) }],
                    chart: { type: 'bar', height: 250, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
                    xaxis: { categories: response.data.map(d => d.name) },
                    colors: ['#36A2EB'],
                    dataLabels: { enabled: true }
                };
                renderChart('chart-members-by-group', options);
            }
        });
    }

    // Load spiritual growth chart
    function loadSpiritualGrowthChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'spiritual_growth' }), function(response) {
            if (response.success) {
                const data = response.data;
                const options = {
                    series: [data.born_again.percentage, data.water_baptized.percentage, data.spirit_filled.percentage],
                    chart: { type: 'radialBar', height: 250 },
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                name: { fontSize: '12px' },
                                value: { fontSize: '14px', formatter: val => val + '%' },
                                total: {
                                    show: true, label: 'Total',
                                    formatter: () => data.total_members
                                }
                            }
                        }
                    },
                    labels: ['Born Again', 'Baptized', 'Spirit Filled'],
                    colors: ['#4BC0C0', '#36A2EB', '#9966FF']
                };
                renderChart('chart-spiritual-growth', options);
            }
        });
    }

    // Load donation trend chart
    function loadDonationTrendChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'donation_trend' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: [{
                        name: 'Donations',
                        data: response.data.map(d => d.total)
                    }],
                    chart: { type: 'area', height: 300, toolbar: { show: false } },
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3 }
                    },
                    xaxis: { categories: response.data.map(d => d.month_short) },
                    yaxis: { labels: { formatter: val => 'KES ' + numberFormat(val) } },
                    colors: ['#28a745'],
                    tooltip: { y: { formatter: val => 'KES ' + numberFormat(val) } }
                };
                renderChart('chart-donation-trend', options);
            }
        });
    }

    // Load donations by purpose chart
    function loadDonationsByPurposeChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'donations_by_purpose' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: response.data.map(d => d.total),
                    chart: { type: 'donut', height: 250 },
                    labels: response.data.map(d => d.label),
                    colors: response.data.map(d => d.color),
                    legend: { position: 'bottom' },
                    tooltip: { y: { formatter: val => 'KES ' + numberFormat(val) } }
                };
                renderChart('chart-donations-by-purpose', options);
            }
        });
    }

    // Load donations by method chart
    function loadDonationsByMethodChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'donations_by_method' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: response.data.map(d => d.total),
                    chart: { type: 'pie', height: 250 },
                    labels: response.data.map(d => d.label),
                    colors: response.data.map(d => d.color),
                    legend: { position: 'bottom' },
                    tooltip: { y: { formatter: val => 'KES ' + numberFormat(val) } }
                };
                renderChart('chart-donations-by-method', options);
            }
        });
    }

    // Load attendance trend chart
    function loadAttendanceTrendChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'attendance_trend' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: [
                        { name: 'Present', data: response.data.map(d => d.present) },
                        { name: 'Absent', data: response.data.map(d => d.absent) }
                    ],
                    chart: { type: 'bar', height: 300, stacked: true, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: false, borderRadius: 4 } },
                    xaxis: { categories: response.data.map(d => d.week) },
                    colors: ['#28a745', '#dc3545'],
                    legend: { position: 'top' }
                };
                renderChart('chart-attendance-trend', options);
            }
        });
    }

    // Load attendance summary
    function loadAttendanceSummary() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'attendance_summary' }), function(response) {
            if (response.success) {
                const data = response.data;
                $('#att-total').text(numberFormat(data.total));
                $('#att-present').text(numberFormat(data.present));
                $('#att-absent').text(numberFormat(data.absent));
                $('#att-excused').text(numberFormat(data.excused));
                $('#att-rate').text(data.attendance_rate + '%');

                const options = {
                    series: [data.present, data.absent, data.excused],
                    chart: { type: 'donut', height: 250 },
                    labels: ['Present', 'Absent', 'Excused'],
                    colors: ['#28a745', '#dc3545', '#ffc107'],
                    legend: { position: 'bottom' }
                };
                renderChart('chart-attendance-summary', options);
            }
        });
    }

    // Load gender distribution chart
    function loadGenderChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'gender_distribution' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: response.data.map(d => d.count),
                    chart: { type: 'donut', height: 280 },
                    labels: response.data.map(d => d.label),
                    colors: response.data.map(d => d.color),
                    legend: { position: 'bottom' }
                };
                renderChart('chart-gender', options);
            }
        });
    }

    // Load marital status chart
    function loadMaritalChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'marital_status' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: response.data.map(d => d.count),
                    chart: { type: 'pie', height: 280 },
                    labels: response.data.map(d => d.label),
                    colors: response.data.map(d => d.color),
                    legend: { position: 'bottom' }
                };
                renderChart('chart-marital', options);
            }
        });
    }

    // Load age distribution chart
    function loadAgeChart() {
        $.get('{{ route("report-center.chart-data") }}', getFilters({ chart_type: 'age_distribution' }), function(response) {
            if (response.success && response.data.length > 0) {
                const options = {
                    series: [{ name: 'Members', data: response.data.map(d => d.count) }],
                    chart: { type: 'bar', height: 280, toolbar: { show: false } },
                    plotOptions: { bar: { borderRadius: 4, distributed: true } },
                    xaxis: { categories: response.data.map(d => d.label) },
                    colors: response.data.map(d => d.color),
                    legend: { show: false },
                    dataLabels: { enabled: true }
                };
                renderChart('chart-age', options);
            }
        });
    }

    // Helper to render/update charts
    function renderChart(elementId, options) {
        const el = document.querySelector('#' + elementId);
        if (!el) return;

        if (charts[elementId]) {
            charts[elementId].updateOptions(options);
        } else {
            charts[elementId] = new ApexCharts(el, options);
            charts[elementId].render();
        }
    }

    // Helper to get current filter values
    function getFilters(extra = {}) {
        const formData = $('#export-form').serializeArray();
        const filters = {};
        formData.forEach(item => {
            if (item.name.endsWith('[]')) {
                const key = item.name.slice(0, -2);
                if (!filters[key]) filters[key] = [];
                filters[key].push(item.value);
            } else if (item.value) {
                filters[item.name] = item.value;
            }
        });
        return { ...filters, ...extra };
    }

    // Helper for number formatting
    function numberFormat(num) {
        if (num === null || num === undefined) return '0';
        return new Intl.NumberFormat().format(num);
    }

    // Load all charts
    function loadAllCharts() {
        loadSummaryStats();
        loadMembershipGrowthChart();
        loadMembersByGroupChart();
        loadSpiritualGrowthChart();
        loadDonationTrendChart();
        loadDonationsByPurposeChart();
        loadDonationsByMethodChart();
        loadAttendanceTrendChart();
        loadAttendanceSummary();
        loadGenderChart();
        loadMaritalChart();
        loadAgeChart();
    }

    // Initial load
    loadAllCharts();

    // Reload charts when tab is switched
    $('#chart-tabs a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
        // Redraw charts to fix rendering issues on hidden tabs
        Object.values(charts).forEach(chart => {
            if (chart && typeof chart.render === 'function') {
                setTimeout(() => chart.render(), 100);
            }
        });
    });
});
</script>
@endpush
