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
            <h2 class="card-title">Event Attendance </h2>
            <hr>
        </div>
    </div>




    <div class="row">
            <div class="col-md-4">
                <canvas id="attendanceScore"></canvas>
            </div>
    
            <div class="col-md-8 mt-4">
                <canvas id="attendanceStacked"></canvas>
            </div>
        </div>

    <div class="row">             
        <div class="col-sm-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <form method="GET" action="{{ route('events.downloadAttendanceCsv') }}" class="form-inline w-100">
                            <select name="year" class="form-control mr-2" required>
                                <option value="">Select Year</option>
                                @for($year = now()->year; $year >= now()->year - 4; $year--)
                                    <option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-primary btn-round ml-auto">
                                <i class="flaticon-download"></i>
                                Download Report
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tb_attendance" class=" table table-striped table-hover">
                            <!-- <table class="table table-bordered table-hover"> -->
                            <thead >
                                <tr>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Excused</th>
                                    <th>Attendance %</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance as $row)
                                @php
                                $total = $row->total_atendees ?? 0;
                                $present = $row->total_present ?? 0;
                                $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                                @endphp
                                <tr>
                                    <td>{{ $row->event_title }}</td>
                                    <td>{{ $row->event_date }}</td>
                                    <td>{{ $total }}</td>
                                    <td class="text-success">{{ $present }}</td>
                                    <td class="text-danger">{{ $row->total_absent ?? 0 }}</td>
                                    <td>{{ $row->total_excused ?? 0 }}</td>
                                    <td>{{ $percentage }}%</td>
                                    <td>
                                        @if($percentage >= 70)
                                        <span class="badge badge-success">Healthy</span>
                                        @elseif($percentage >= 40)
                                        <span class="badge badge-warning">Needs Attention</span>
                                        @else
                                        <span class="badge badge-danger">Critical</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
             
    </div>
    <!-- .row -->
     

    @endsection


    @push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        /**
     * Labels: Event titles
     */
    const labelsScore = {!! json_encode($attendance->pluck('event_title')) !!};


    const scores = {!! json_encode(
        $attendance->map(function($r){
            return $r->total_atendees > 0
                ? round(($r->total_present / $r->total_atendees) * 100, 1)
                : 0;
        })
    ) !!};

    new Chart(document.getElementById('attendanceScore'), {
        type: 'doughnut',
        data: {
            labels: labelsScore,
            datasets: [{
                label: 'Attendance (%)',
                data: scores
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: { max: 100, beginAtZero: true }
            },
            plugins: {
                datalabels: {
                    formatter: (value) => value + '%',
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        }
    });
    </script>
    <script>
    const labels = {!! json_encode($attendance->pluck('event_title')) !!};

    const presentData = {!! json_encode($attendance->pluck('total_present')->map(fn($v) => $v ?? 0)) !!};
    const absentData = {!! json_encode($attendance->pluck('total_absent')->map(fn($v) => $v ?? 0)) !!};
    const excusedData = {!! json_encode($attendance->pluck('total_excused')->map(fn($v) => $v ?? 0)) !!};

    new Chart(document.getElementById('attendanceStacked'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Present', data: presentData },
                { label: 'Absent', data: absentData },
                { label: 'Excused', data: excusedData }
            ]
        },
        // options: {
        //     responsive: true,
        //     scales: {
        //         x: { stacked: true },
        //         y: { stacked: true, beginAtZero: true } 
        //     }
        // },
        
		options: {
			responsive: true,
			maintainAspectRatio: false,
			legend: {
				display: false,
			},
			scales: {
				yAxes: [{
					ticks: {
						display: false //this will remove only the label
					},
					gridLines: {
						drawBorder: false,
						display: false
					}
				}],
				xAxes: [{
					gridLines: {
						drawBorder: false,
						display: false
					}
				}]
			},
		}
    });
    </script>
    <script>
        $(document).ready(function() {
            $('#tb_attendance').DataTable({
                processing: true,
                serverSide: true,
            });
            $('#posts_year').change(function() {
                var year = $(this).val();
                window.location.href = '{{ route("reports.index") }}?year=' + year;
            });

        });

        const post_selected_year = $('#posts_year').val();
        var post_data = {
            !!json_encode($postsChartData) !!
        };
        loadChart(post_data, 'postsChart', 'Post', post_selected_year)


        const user_selected_year = $('#users_year').val();
        var user_data = {
            !!json_encode($usersChartData) !!
        };
        loadChart(user_data, 'usersChart', 'User', user_selected_year)

        function loadChart(data, load_area, entity, selectedYear) {
            var labels = [];
            var values = [];
            for (var key in data) {
                labels.push(key);
                values.push(data[key]);
            }

            var ctx = document.getElementById(load_area);
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: entity + ' Count',
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
                        text: entity + ' Count by Month for Year ' + selectedYear
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