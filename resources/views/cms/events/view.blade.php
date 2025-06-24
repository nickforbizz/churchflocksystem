@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Event Details </h4>
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
                <a href="{{ route('events.index') }}">Events</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">View</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('cms.helpers.partials.feedback')
        </div>

        {{-- Event Details Card --}}
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-header" style="background-image: url('{{ asset('assets/img/blogpost.jpg') }}')">
                    <div class="profile-picture">
                        <div class="avatar avatar-xl">
                             <i class="fa fa-calendar-alt fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="user-profile text-center">
                        <div class="name">{{ $event->title }}</div>
                        <div class="job">{{ $event->event_date->format('l, F j, Y') }}</div>
                        <div class="desc">{{ $event->description }}</div>
                        
                        <div class="view-profile">
                            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary btn-block">Edit Event</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row user-stats text-center">
                        <div class="col">
                            <div class="number">{{ $event->event_attendances->where('status', 'present')->count() }}</div>
                            <div class="title">Present</div>
                        </div>
                        <div class="col">
                            <div class="number">{{ $event->event_attendances->where('status', 'absent')->count() }}</div>
                            <div class="title">Absent</div>
                        </div>
                        <div class="col">
                            <div class="number">{{ $event->event_attendances->count() }}</div>
                            <div class="title">Total Marked</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance Details Card --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Event Attendance</h4>
                        @can('create event_attendance')
                        <button class="btn btn-primary btn-round ml-auto" data-toggle="modal" data-target="#addAttendanceModal">
                            <i class="fa fa-plus"></i>
                            Add Attendance
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tb_event_attendees" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Status</th>
                                    <th>Attendance Type</th>
                                    <th>Marked By</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-tbody">
                                @forelse($event->event_attendances as $attendance)
                                <tr id="attendance-row-{{$attendance->id}}">
                                    <td>{{ $attendance->member->full_name ?? 'N/A' }}</td>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <span class="badge badge-success">Present</span>
                                        @elseif($attendance->status == 'absent')
                                            <span class="badge badge-danger">Absent</span>
                                        @else
                                            <span class="badge badge-warning">Excused</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendance->attendance_type == 'in-person')
                                            <span class="badge badge-info">In-Person</span>
                                        @else
                                            <span class="badge badge-primary">Online</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                                    <td>{{ $attendance->notes }}</td>
                                </tr>
                                @empty
                                <tr id="no-attendance-row">
                                    <td colspan="5" class="text-center">No attendance records yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- .page-inner -->

<!-- Add Attendance Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">
                        Add</span>
                    <span class="fw-light">
                        Attendance
                    </span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="small">Mark attendance for a member for the event: <strong>{{ $event->title }}</strong></p>
                <form id="addAttendanceForm">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="attendance_date" value="{{ $event->event_date->format('Y-m-d') }}">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label for="member_id">Member</label>
                                <select name="member_id" id="member_id" class="form-control" required>
                                    <option value="">-- Select Member --</option>
                                    @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 pr-0">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Attendance Type</label>
                                <select name="attendance_type" class="form-control" required>
                                    <option value="in-person">In-Person</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" placeholder="Enter notes"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" id="submitAttendanceBtn" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')


<script>
    $(document).ready(function() {
        $('#submitAttendanceBtn').on('click', function(e) {
            e.preventDefault();
            
            var form = $('#addAttendanceForm');
            var btn = $(this);
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adding...');

            $.ajax({
                url: "{{ route('eventAttendance.store') }}",
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#addAttendanceModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });

                        // Add new row to table
                        var newRow = `
                            <tr id="attendance-row-${response.data.id}">
                                <td>${response.data.member.full_name}</td>
                                <td><span class="badge badge-${response.data.status === 'present' ? 'success' : (response.data.status === 'absent' ? 'danger' : 'warning')}">${response.data.status.charAt(0).toUpperCase() + response.data.status.slice(1)}</span></td>
                                <td><span class="badge badge-${response.data.attendance_type === 'in-person' ? 'info' : 'primary'}">${response.data.attendance_type.charAt(0).toUpperCase() + response.data.attendance_type.slice(1)}</span></td>
                                <td>${response.data.user.name}</td>
                                <td>${response.data.notes || ''}</td>
                            </tr>
                        `;
                        $('#no-attendance-row').remove();
                        $('#attendance-tbody').append(newRow);

                        // Remove member from modal dropdown
                        $('#member_id option[value="' + response.data.member_id + '"]').remove();
                        
                        // Reset form
                        form[0].reset();
                    } else {
                         Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'An unknown error occurred.',
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle validation or other errors
                    console.log('Error:', textStatus, errorThrown);
                    
                    var errors = jqXHR.responseJSON.errors;
                    var errorMessage = 'An error occurred. Please try again.';
                    if(errors){
                        errorMessage = Object.values(errors).join('\n');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                    });
                },
                complete: function() {
                    btn.attr('disabled', false).html('Add');
                }
            });
        });
    });


</script>

@endpush