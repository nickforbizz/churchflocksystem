@extends('layouts.cms')

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                <h5 class="text-white op-7 mb-2">System view at a glance</h5>
            </div>
        </div>
    </div>
</div>
<div class="page-inner mt--5">
    <div class="row row-card-no-pd">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="flaticon-users text-warning"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Members</p>
                                <h4 class="card-title">{{ $totalMembers ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body ">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="flaticon-layers text-success"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Groups</p>
                                <h4 class="card-title">{{ $totalGroups ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="flaticon-hands text-danger"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Donations (Month)</p>
                                <h4 class="card-title">Ksh {{ number_format($donationsThisMonth ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="flaticon-calendar text-primary"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Upcoming Events</p>
                                <h4 class="card-title">{{ $upcomingEvents ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Membership Growth ({{ now()->year }})</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="membershipChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Donations by Purpose</div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="donationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recently Joined Members</div>
                </div>
                <div class="card-body">
                    <div class="card-list">
                        @forelse($recentMembers as $member)
                        <div class="item-list">
                            <div class="avatar">
                                <span class="avatar-title rounded-circle border border-white bg-primary">{{ substr($member->full_name, 0, 1) }}</span>
                            </div>
                            <div class="info-user ml-3">
                                <div class="username">{{ $member->full_name }}</div>
                                <div class="status">{{ $member->group->name ?? 'No Group' }}</div>
                            </div>
                            <a href="{{ route('members.index') }}" class="btn btn-icon btn-primary btn-round btn-xs">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                        @empty
                        <p class="text-center">No new members recently.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Donations</div>
                </div>
                <div class="card-body pb-0">
                    @forelse($recentDonations as $donation)
                    <div class="d-flex">
                        <div class="avatar">
                            <span class="avatar-title rounded-circle border border-white bg-success">{{ substr($donation->member->full_name ?? 'A', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 pt-1 ml-2">
                            <h6 class="fw-bold mb-1">{{ $donation->member->full_name ?? 'Anonymous' }}</h6>
                            <small class="text-muted">{{ $donation->purpose }} - {{ $donation->date->format('M d, Y') }}</small>
                        </div>
                        <div class="d-flex ml-auto align-items-center">
                            <h3 class="text-info fw-bold">Ksh {{ number_format($donation->amount, 2) }}</h3>
                        </div>
                    </div>
                    @if(!$loop->last)
                    <div class="separator-dashed"></div>
                    @endif
                    @empty
                    <p class="text-center p-3">No recent donations.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Chart JS -->
<script src="{{ asset('assets/js/plugin/chart.js/Chart.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var membershipChartCtx = document.getElementById('membershipChart').getContext('2d');
        var donationsChartCtx = document.getElementById('donationsChart').getContext('2d');

        // Membership Growth Chart
        var membershipData = {!! json_encode($membershipGrowthData ?? []) !!};
        var membershipChart = new Chart(membershipChartCtx, {
            type: 'line',
            data: {
                labels: Object.keys(membershipData),
                datasets: [{
                    label: "New Members",
                    borderColor: '#1d7af3',
                    pointBackgroundColor: 'rgba(29, 122, 243, 0.6)',
                    pointRadius: 0,
                    backgroundColor: 'rgba(29, 122, 243, 0.1)',
                    legendColor: '#1d7af3',
                    fill: true,
                    borderWidth: 2,
                    data: Object.values(membershipData)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10
                },
                layout: {
                    padding: {
                        left: 15,
                        right: 15,
                        top: 15,
                        bottom: 15
                    }
                }
            }
        });

        // Donations by Purpose Chart
        var donationsData = {!! json_encode($donationsByPurpose ?? []) !!};
        var donationsChart = new Chart(donationsChartCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: Object.values(donationsData),
                    backgroundColor: ['#ff9e27', '#2BB930', '#F25961', '#1d7af3', '#581d7a']
                }],
                labels: Object.keys(donationsData)
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                },
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 20,
                        bottom: 20
                    }
                }
            }
        });
    });
</script>
@endpush