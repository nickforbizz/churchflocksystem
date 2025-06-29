@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Reports </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
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
                <a href="#">Index</a>
            </li>
        </ul>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">System Reports</h4>
                        <div class="form-group d-flex align-items-center mb-0">
                            <label for="report_year" class="mr-2 mb-0">Year:</label>
                            <select class="form-control" id="report_year" name="year">
                                @foreach($allYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills nav-secondary" id="reportsTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="membership-tab" data-toggle="pill" href="#membership" role="tab" aria-controls="membership" aria-selected="true">Membership Growth</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="donations-tab" data-toggle="pill" href="#donations" role="tab" aria-controls="donations" aria-selected="false">Donations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="posts-tab" data-toggle="pill" href="#posts" role="tab" aria-controls="posts" aria-selected="false">Posts</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2 mb-3" id="reportsTabContent">
                        <div class="tab-pane fade show active" id="membership" role="tabpanel" aria-labelledby="membership-tab">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('reports.download.csv', ['type' => 'members', 'year' => $selectedYear]) }}" class="btn btn-primary btn-round">Download CSV</a>
                            </div>
                            <canvas id="membershipChart" height="100"></canvas>
                        </div>
                        <div class="tab-pane fade" id="donations" role="tabpanel" aria-labelledby="donations-tab">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('reports.download.csv', ['type' => 'donations', 'year' => $selectedYear]) }}" class="btn btn-primary btn-round">Download CSV</a>
                            </div>
                            <canvas id="donationsChart" height="100"></canvas>
                        </div>
                        <div class="tab-pane fade" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('reports.download.csv', ['type' => 'posts', 'year' => $selectedYear]) }}" class="btn btn-primary btn-round">Download CSV</a>
                            </div>
                            <canvas id="postsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- .page-inner -->

@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugin/chart.js/Chart.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#report_year').change(function() {
            var year = $(this).val();
            window.location.href = '{{ route("reports.index") }}?year=' + year;
        });

        const selectedYear = $('#report_year').val();

        // Load charts
        loadChart({!! json_encode($membersChartData) !!}, 'membershipChart', 'Membership Growth', 'New Members', selectedYear);
        loadChart({!! json_encode($donationsChartData) !!}, 'donationsChart', 'Donations', 'Total Amount ($)', selectedYear);
        loadChart({!! json_encode($postsChartData) !!}, 'postsChart', 'Posts', 'Post Count', selectedYear);
    });
 
    function loadChart(data, canvasId, title, label, selectedYear) {
        var labels = [];
        var values = [];
        for (var key in data) {
            labels.push(key);
            values.push(data[key]);
        }
    
        var ctx = document.getElementById(canvasId).getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: values,
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                }]
            },
            options: {
                title: {
                    display: true,
                    text: title + ' Report for ' + selectedYear
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
</script>

@endpush