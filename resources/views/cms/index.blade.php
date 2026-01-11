@extends('layouts.cms')

@section('content')

<style>
	.circles-text{
		font-size: 18px !important;
	}
</style>

<div class="panel-header bg-primary-gradient">
	<div class="page-inner py-5">
		<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
			<div>
				<h2 class="text-white pb-2 fw-bold">Dashboard</h2>
				<h5 class="text-white op-7 mb-2">System view at a glance</h5>
			</div>
			<div class="ml-md-auto py-2 py-md-0">
				<a href="#" class="btn btn-white btn-border btn-round mr-2">Manage</a>
				<a href="#" class="btn btn-secondary btn-round">Add Customer</a>
			</div>
		</div>
	</div>
</div>
<div class="page-inner mt--5">
	<div class="row mt--2">
		<div class="col-md-6">
			<div class="card full-height">
				<div class="card-body">
					<div class="card-title">Events Attendance</div>
					<div class="card-category">Latest Events information about statistics in system</div>
					<div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
						@foreach($event_attendance->take(3) as $ea)
						<div class="px-2 pb-2 pb-md-0 text-center">
							<div id="circles-{{ $loop->iteration }}" data-percent="{{ $ea->participation_percentage }}"></div>
							<h6 class="mt-3 mb-0" style="font-size: small; text-transform: capitalize !important;"> {{ ucwords(strtolower($ea->title)) }} </h6>
						</div>
						@endforeach

					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card full-height">
				<div class="card-body">
					<div class="card-title">Numbers Spreak!</div> 
					<div class="row py-3">
						<div class="col-md-4 d-flex flex-column justify-content-around">
							<div class='card p-2'>
									<h3 class="fw-bold">{{ $event_attendance->count() }}</h3>
									<h6 class="fw-bold text-capitalize text-success op-8"> Events</h6>
							</div>
							<div class='card p-2'>
								<h3 class="fw-bold"> {{ $membership->sum('total_members') }} </h3>
								<h6 class="fw-bold text-capitalize text-danger op-8"> Members</h6>
							</div>
						</div>
						<div class="col-md-4 d-flex flex-column justify-content-aroudnd">
							<div class='card p-2'>
								<h3 class="fw-bold">{{ $donations_summary->count() }}</h3>
								<h6 class="fw-bold text-capitalize text-success op-8"> Donations</h6>
							</div>
							<div class='card p-2'>
								<h3 class="fw-bold">{{ $membership->count() }}</h3>
								<h6 class="fw-bold text-capitalize text-primary op-8"> Groups</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Event Attendances</div>
						<div class="card-tools">
							<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
								<span class="btn-label">
									<i class="fa fa-pencil"></i>
								</span>
								Export
							</a>
							<a href="#" class="btn btn-info btn-border btn-round btn-sm">
								<span class="btn-label">
									<i class="fa fa-print"></i>
								</span>
								Print
							</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<canvas id="attendanceScore"></canvas>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Group Membership</div>
						<div class="card-tools">
							<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
								<span class="btn-label">
									<i class="fa fa-pencil"></i>
								</span>
								Export
							</a>
							<a href="#" class="btn btn-info btn-border btn-round btn-sm">
								<span class="btn-label">
									<i class="fa fa-print"></i>
								</span>
								Print
							</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<canvas id="membershipChart"></canvas>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 mt-4">
			<div class="card">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Donations Summary</div>
						<div class="card-tools">
							<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
								<span class="btn-label">
									<i class="fa fa-pencil"></i>
								</span>
								Export
							</a>
							<a href="#" class="btn btn-info btn-border btn-round btn-sm">
								<span class="btn-label">
									<i class="fa fa-print"></i>
								</span>
								Print
							</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<canvas id="donations_summary"></canvas>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<div class="card-head-row">
						<div class="card-title">Member Statistics</div>
						<div class="card-tools">
							<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
								<span class="btn-label">
									<i class="fa fa-pencil"></i>
								</span>
								Export
							</a>
							<a href="#" class="btn btn-info btn-border btn-round btn-sm">
								<span class="btn-label">
									<i class="fa fa-print"></i>
								</span>
								Print
							</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="chart-container" style="min-height: 375px">
						<canvas id="membersChart"></canvas>
					</div>
					<div id="myChartLegend"></div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-primary">
				<div class="card-header">
					<div class="card-title">Donations </div>
					<div class="card-category">Total donations contributed</div>
				</div>
				<div class="card-body pb-0">
					<div class="mb-4 mt-2">
						<h1>Ksh {{ array_sum(array_map(fn($row) => $row->total_amount, $donations_summary->toArray())) }}</h1>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					<div class="card-title">Top Selling Products</div>
				</div>
				<div class="card-body pb-0">
					<div class="d-flex">
						<div class="avatar">
							<img src="../assets/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="flex-1 pt-1 ml-2">
							<h6 class="fw-bold mb-1">CSS</h6>
							<small class="text-muted">Cascading Style Sheets</small>
						</div>
						<div class="d-flex ml-auto align-items-center">
							<h3 class="text-info fw-bold">+$17</h3>
						</div>
					</div>
					<div class="separator-dashed"></div>
					<div class="d-flex">
						<div class="avatar">
							<img src="../assets/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="flex-1 pt-1 ml-2">
							<h6 class="fw-bold mb-1">J.CO Donuts</h6>
							<small class="text-muted">The Best Donuts</small>
						</div>
						<div class="d-flex ml-auto align-items-center">
							<h3 class="text-info fw-bold">+$300</h3>
						</div>
					</div>
					<div class="separator-dashed"></div>
					<div class="d-flex">
						<div class="avatar">
							<img src="../assets/img/logoproduct3.svg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="flex-1 pt-1 ml-2">
							<h6 class="fw-bold mb-1">Ready Pro</h6>
							<small class="text-muted">Bootstrap 4 Admin Dashboard</small>
						</div>
						<div class="d-flex ml-auto align-items-center">
							<h3 class="text-info fw-bold">+$350</h3>
						</div>
					</div>
					<div class="separator-dashed"></div>
					<div class="pull-in">
						<canvas id="topProductsChart"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-body">
					<div class="card-title fw-mediumbold">Recent Clients</div>
					<div class="card-list">
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/jm_denis.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">Jimmy Denis</div>
								<div class="status">Graphic Designer</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/chadengle.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">Chad</div>
								<div class="status">CEO Zeleaf</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/talha.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">Talha</div>
								<div class="status">Front End Designer</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/mlane.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">John Doe</div>
								<div class="status">Back End Developer</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/talha.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">Talha</div>
								<div class="status">Front End Designer</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
						<div class="item-list">
							<div class="avatar">
								<img src="../assets/img/jm_denis.jpg" alt="..." class="avatar-img rounded-circle">
							</div>
							<div class="info-user ml-3">
								<div class="username">Jimmy Denis</div>
								<div class="status">Graphic Designer</div>
							</div>
							<button class="btn btn-icon btn-primary btn-round btn-xs">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card full-height">
				<div class="card-header">
					<div class="card-title">Feed Activity</div>
				</div>
				<div class="card-body">
					<ol class="activity-feed">
						<li class="feed-item feed-item-secondary">
							<time class="date" datetime="9-25">Sep 25</time>
							<span class="text">Responded to need <a href="#">"Volunteer opportunity"</a></span>
						</li>
						<li class="feed-item feed-item-success">
							<time class="date" datetime="9-24">Sep 24</time>
							<span class="text">Added an interest <a href="#">"Volunteer Activities"</a></span>
						</li>
						<li class="feed-item feed-item-info">
							<time class="date" datetime="9-23">Sep 23</time>
							<span class="text">Joined the group <a href="single-group.php">"Boardsmanship Forum"</a></span>
						</li>
						<li class="feed-item feed-item-warning">
							<time class="date" datetime="9-21">Sep 21</time>
							<span class="text">Responded to need <a href="#">"In-Kind Opportunity"</a></span>
						</li>
						<li class="feed-item feed-item-danger">
							<time class="date" datetime="9-18">Sep 18</time>
							<span class="text">Created need <a href="#">"Volunteer Opportunity"</a></span>
						</li>
						<li class="feed-item">
							<time class="date" datetime="9-17">Sep 17</time>
							<span class="text">Attending the event <a href="single-event.php">"Some New Event"</a></span>
						</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<!-- Chart JS -->
<script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>
<!-- Chart Circle -->
<script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
	/**
	 * Labels: Event titles
	 */
	const labelsScore = {!!json_encode($attendance->pluck('event_title')) !!};

	const labelsGrpMember = {!!json_encode($membership->pluck('group_name')) !!};


	const scores = {!!json_encode($attendance->map(function($r) {
				return $r->total_atendees > 0 ?
					round(($r->total_present / $r->total_atendees) * 100, 1) :
					0;
			})) !!};


	const memberScores = {!!json_encode($membership->map(function($r) {
		return $r->total_members > 0 ?
			round(($r->active_members / $r->total_members) * 100, 1) : 0;
	})) !!};


	drawDoughnutChart('attendanceScore', labelsScore, scores, 'Attendance Score');
	drawDoughnutChart('membershipChart', labelsGrpMember, memberScores, 'Group Membership Score');


	function drawDoughnutChart(elementId, labels, scores, title='Attendance Score') {

		new Chart(document.getElementById(elementId), {
			title,
			type: 'doughnut',
			data: {
				labels: labels,
				datasets: [{
					label: 'Attendance (%)',
					data: scores
				}]
			},
			options: {
				indexAxis: 'y',
				scales: {
					x: {
						max: 100,
						beginAtZero: true
					}
				}
			}
		});
	}
</script>


<script>
	const dateLabels = {!! json_encode( $donations_summary->pluck('donation_date')->unique()->values()) !!};
	const dateTotals = {!! json_encode($donations_summary->groupBy('donation_date')->map(fn($rows) => $rows->sum('total_amount'))->values()) !!};
	const donationData  = {
		labels: dateLabels,
		datasets: [
			{ label: 'Total Donations', data: dateTotals }
		]
	};
	drawBarChart('donations_summary', donationData);
	
	const membersLabels = {!! json_encode( $membership->pluck('group_name')) !!};
	const totalMembersData = {!! json_encode($membership->pluck('total_members')->map(fn($v) => $v ?? 0)) !!};
	const activeMembersData = {!! json_encode($membership->pluck('active_members')->map(fn($v) => $v ?? 0)) !!};
	const memberData  = {
		labels: membersLabels,
		datasets: [
			{ label: 'Total Members', data: totalMembersData },
			{ label: 'Active Members', data: activeMembersData }
		]
	};
	drawBarChart('membersChart', memberData);
	function drawBarChart(elementId, data) {
		new Chart(document.getElementById(elementId).getContext('2d'), {
			type: 'bar',
			data,
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
	}
</script>


<script>
	let circleevent1 = document.getElementById('circles-1');
	let circleevent2 = document.getElementById('circles-2');
	let circleevent3 = document.getElementById('circles-3');

	let percent1 = circleevent1.getAttribute('data-percent');
	let percent2 = circleevent2.getAttribute('data-percent');
	let percent3 = circleevent3.getAttribute('data-percent');

	drawCirclePercentage('circles-1', percent1, '#FF9E27');
	drawCirclePercentage('circles-2', percent2, '#2BB930');
	drawCirclePercentage('circles-3', percent3, '#F25961');
	function drawCirclePercentage(elementId, percent, myColor='') {
		Circles.create({
			id: elementId,
			radius: 45,
			value: percent,
			maxValue: 100,
			width: 7,
			text: parseInt(percent)+'%',
			colors: ['#f1f1f1', myColor || '#2BB930'],
			duration: 400,
			wrpClass: 'circles-wrp',
			textClass: 'circles-text',
			styleWrapper: true,
			styleText: true
		})
	}


</script>
@endpush