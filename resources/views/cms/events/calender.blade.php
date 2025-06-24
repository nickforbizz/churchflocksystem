@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Events </h4>
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
                <a href="#"> Events</a>
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
                    <div class="d-flex ">
                        <h4 class="card-title">List of Available Record(s)</h4>
                        <div class="ml-auto d-flex align-items-center"> {{-- Added d-flex and align-items-center --}}
                            {{-- Add year filter dropdown --}}
                            <div class="form-group mb-0 mr-3"> {{-- Added mb-0 for better alignment --}}
                                <label for="event_year" class="placeholder mb-0 mr-2">Filter by Year:</label> {{-- Added mb-0 and mr-2 --}}
                                <select id="event_year" class="form-control form-control-sm"> {{-- Added form-control-sm --}}
                                    @foreach($years as $yearOption)
                                        <option value="{{ $yearOption }}" {{ $selectedYear == $yearOption ? 'selected' : '' }}>
                                            {{ $yearOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @can('create event')
                            <a href="{{ route('events.create') }}" class="btn btn-primary btn-round ml-auto">
                                <i class="flaticon-add mr-2"></i>
                                Add Row
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">


                    <div class="container mt-4">
                        <h4>📅 Church Event Calendar</h4>
                        <div id="calendar" class="bg-white p-3 border rounded"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- .page-inner -->

<!-- Event Detail Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Event Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="modalEventTitle"></h4>
                <p><strong>Date:</strong> <span id="modalEventDate"></span></p>
                <p><strong>Location:</strong> <span id="modalEventLocation"></span></p>
                <p><strong>Created By:</strong> <span id="modalEventCreatedBy"></span></p>
                <p id="modalEventDescription"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var eventYearSelect = document.getElementById('event_year');
        var currentSelectedYear = eventYearSelect.value;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: currentSelectedYear + '-01-01', // Start calendar on the selected year
            themeSystem: 'bootstrap',
            bootstrapFontAwesome: false,
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "{{ route('calendar.events') }}",
                    method: 'GET',
                    data: {
                        year: currentSelectedYear
                    },
                    success: function(data) {
                        successCallback(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching calendar events:", textStatus, errorThrown);
                        failureCallback(errorThrown);
                    }
                });
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();

                var eventId = info.event.id;

                $.ajax({
                    url: '/events/' + eventId,
                    method: 'GET',
                    success: function(data) {
                        $('#modalEventTitle').text(data.title);
                        $('#modalEventDate').text(new Date(data.event_date).toLocaleDateString());
                        $('#modalEventLocation').text(data.location || 'N/A');
                        $('#modalEventCreatedBy').text(data.user ? data.user.name : 'N/A');
                        $('#modalEventDescription').text(data.description);
                        $('#eventDetailModal').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error fetching event details:", textStatus, errorThrown);
                        Swal.fire('Error', 'Could not load event details. Please try again.', 'error');
                    }
                });
            },
        });

        calendar.render();

        // Add event listener for year change
        eventYearSelect.addEventListener('change', function() {
            currentSelectedYear = this.value;
            calendar.gotoDate(currentSelectedYear + '-01-01'); // Go to the new year
            calendar.refetchEvents(); // Tell FullCalendar to re-fetch events
        });
    });
</script>

@endpush