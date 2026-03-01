@extends('layouts.cms')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .trend-up { color: #28a745; }
    .trend-down { color: #dc3545; }
    .birthday-item {
        border-left: 3px solid #ffc107;
        padding-left: 10px;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
@php
    // Set defaults to prevent undefined errors
    $stats = $stats ?? [
        'total_members' => 0,
        'new_members_this_month' => 0,
        'new_members_last_month' => 0,
        'total_groups' => 0,
        'total_homecells' => 0,
        'upcoming_events' => 0,
        'total_donations_this_month' => 0,
        'avg_attendance_rate' => 0,
        'water_baptized_count' => 0,
        'spirit_filled_count' => 0,
        'gender_distribution' => [],
        'marital_status_distribution' => [],
    ];
    $charts = $charts ?? [
        'membership_growth' => [],
        'members_by_group' => [],
        'age_distribution' => [],
        'join_trend' => [],
    ];
@endphp
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-0">Dashboard</h4>
            <p class="text-muted">Welcome back! Here's what's happening with your church.</p>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <!-- Total Members -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Members</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_members']) }}</h3>
                            @php
                                $growth = $stats['new_members_this_month'] - $stats['new_members_last_month'];
                            @endphp
                            <small class="{{ $growth >= 0 ? 'trend-up' : 'trend-down' }}">
                                <i class="fa fa-{{ $growth >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($growth) }} from last month
                            </small>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10">
                            <i class="fa fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Members This Month -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">New This Month</h6>
                            <h3 class="mb-0">{{ number_format($stats['new_members_this_month']) }}</h3>
                            <small class="text-muted">Members joined</small>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10">
                            <i class="fa fa-user-plus fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Groups -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Groups</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_groups']) }}</h3>
                            <small class="text-muted">{{ $stats['total_homecells'] }} Homecells</small>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10">
                            <i class="flaticon-network fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Upcoming Events</h6>
                            <h3 class="mb-0">{{ number_format($stats['upcoming_events']) }}</h3>
                            <small class="text-muted">{{ $stats['events_this_month'] }} this month</small>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10">
                            <i class="flaticon-calendar fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Marital Status Chart -->
        <div class="col-md-6  mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Marital Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="maritalChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Gender Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="row mb-4">
        <!-- Members by Group -->
        <div class="col-xl-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Members by Group</h6>
                </div>
                <div class="card-body">
                    <canvas id="groupChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-xl-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Age Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards Row -->
    <div class="row mb-4">
        <!-- Spiritual Growth Stats -->
        <div class="col-xl-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="fa fa-heart mr-2"></i>Spiritual Growth</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Born Again</span>
                            <span>{{ $stats['born_again_count'] }}/{{ $stats['total_members'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php $bornAgainPct = $stats['total_members'] > 0 ? ($stats['born_again_count'] / $stats['total_members']) * 100 : 0; @endphp
                            <div class="progress-bar bg-success" style="width: {{ $bornAgainPct }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Water Baptized</span>
                            <span>{{ $stats['water_baptized_count'] }}/{{ $stats['total_members'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php $baptizedPct = $stats['total_members'] > 0 ? ($stats['water_baptized_count'] / $stats['total_members']) * 100 : 0; @endphp
                            <div class="progress-bar bg-info" style="width: {{ $baptizedPct }}%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Spirit Filled</span>
                            <span>{{ $stats['spirit_filled_count'] }}/{{ $stats['total_members'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php $spiritPct = $stats['total_members'] > 0 ? ($stats['spirit_filled_count'] / $stats['total_members']) * 100 : 0; @endphp
                            <div class="progress-bar bg-warning" style="width: {{ $spiritPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="col-xl-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fa fa-gift mr-2"></i>Upcoming Birthdays</h6>
                    <span class="badge bg-primary text-white" id="birthdayCount">-</span>
                </div>
                <div class="card-body" id="birthdayList">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Members -->
        <div class="col-xl-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="fa fa-clock-o mr-2"></i>Recent Members</h6>
                </div>
                <div class="card-body" id="recentMembersList">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Membership Growth Row -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-3">
            

			<div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Membership Growth</h6>
                </div>
                <div class="card-body">
                    <canvas id="membershipGrowthChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Join Trend (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="joinTrendChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart Data from Laravel
    const chartsData = @json($charts ?? []);
    const statsData = @json($stats ?? []);

    console.log('Charts Data:', chartsData);
    console.log('Stats Data:', statsData);

    // Membership Growth Chart
    if (chartsData.membership_growth && chartsData.membership_growth.length > 0) {
        new Chart(document.getElementById('membershipGrowthChart'), {
            type: 'line',
            data: {
                labels: chartsData.membership_growth.map(d => d.month),
                datasets: [{
                    label: 'Total Members',
                    data: chartsData.membership_growth.map(d => d.count),
                    borderColor: '#4472C4',
                    backgroundColor: 'rgba(68, 114, 196, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Gender Distribution Chart
    if (statsData.gender_distribution && Object.keys(statsData.gender_distribution).length > 0) {
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statsData.gender_distribution),
                datasets: [{
                    data: Object.values(statsData.gender_distribution),
                    backgroundColor: ['#4472C4', '#ED7D31', '#A5A5A5']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Members by Group Chart
    if (chartsData.members_by_group && chartsData.members_by_group.length > 0) {
        new Chart(document.getElementById('groupChart'), {
            type: 'bar',
            data: {
                labels: chartsData.members_by_group.map(d => d.name),
                datasets: [{
                    label: 'Members',
                    data: chartsData.members_by_group.map(d => d.members_count),
                    backgroundColor: '#4472C4'
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    }

    // Age Distribution Chart
    if (chartsData.age_distribution && Object.keys(chartsData.age_distribution).length > 0) {
        new Chart(document.getElementById('ageChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(chartsData.age_distribution),
                datasets: [{
                    label: 'Members',
                    data: Object.values(chartsData.age_distribution),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Marital Status Chart
    if (statsData.marital_status_distribution && Object.keys(statsData.marital_status_distribution).length > 0) {
        new Chart(document.getElementById('maritalChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(statsData.marital_status_distribution),
                datasets: [{
                    data: Object.values(statsData.marital_status_distribution),
                    backgroundColor: ['#4472C4', '#ED7D31', '#FFC000', '#70AD47', '#5B9BD5']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }

    // Join Trend Chart
    if (chartsData.join_trend && chartsData.join_trend.length > 0) {
        new Chart(document.getElementById('joinTrendChart'), {
            type: 'bar',
            data: {
                labels: chartsData.join_trend.map(d => d.month),
                datasets: [{
                    label: 'New Members',
                    data: chartsData.join_trend.map(d => d.count),
                    backgroundColor: '#70AD47'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Load Birthdays
    fetch('{{ route("dashboard.widget", "birthdays") }}')
        .then(response => response.json())
        .then(data => {
			console.log(data);
			
            const container = document.getElementById('birthdayList');
            document.getElementById('birthdayCount').textContent = data.length;
            
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted text-center mb-0">No birthdays this week</p>';
                return;
            }

            container.innerHTML = data.map(b => `
                <div class="birthday-item">
                    <strong>${b.name}</strong>
                    <div class="small text-muted">
                        ${b.date} (turning ${b.age})
                        ${b.days_until === 0 ? '<span class="badge bg-success text-white ms-1">Today!</span>' : 
                          b.days_until === 1 ? '<span class="badge bg-warning text-white ms-1">Tomorrow</span>' : 
                          `<span class="text-muted">in ${b.days_until} days</span>`}
                    </div>
                </div>
            `).join('');
        });

    // Load Recent Members
    fetch('{{ route("dashboard.widget", "recent_members") }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recentMembersList');
            
            if (data.length === 0) {
                container.innerHTML = '<p class="text-muted text-center mb-0">No recent members</p>';
                return;
            }

            container.innerHTML = data.map(m => `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong>${m.name}</strong>
                        <div class="small text-muted"> -- s${m.email}</div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-info text-white">#${m.member_number}</span>
                        <div class="small text-muted">${m.joined}</div>
                    </div>
                </div>
            `).join('');
        });
});
</script>
@endpush