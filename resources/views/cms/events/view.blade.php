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
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
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
                                    <th>Member ID</th>
                                    <th>Member Name</th>
                                    <th>Status</th>
                                    <th>Attendance Type</th>
                                    <th>Marked By</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="attendance-tbody">
                                @forelse($event->event_attendances as $attendance)
                                <tr id="attendance-row-{{$attendance->id}}">
                                    <td>{{ $attendance->member->member_number ?? 'N/A' }}</td>
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
                                    <td>
                                       

                                        @can('edit event_attendance')
                                                <button class="btn btn-link btn-primary btn-sm edit-att" 
                                                    data-id="{{ $attendance->id }}" 
                                                    data-url="{{ route('eventAttendance.update', $attendance->id) }}" 
                                                    data-status="{{ $attendance->status }}" 
                                                    data-type="{{ $attendance->attendance_type }}" 
                                                    data-notes="{{ e($attendance->notes) }}"
                                                    data-member-id="{{ $attendance->member_id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                        @endcan

                                        @can('delete event_attendance')
                                            <button class="btn btn-link btn-danger btn-sm delete-att" data-id="{{ $attendance->id }}" data-url="{{ route('eventAttendance.destroy', $attendance->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endcan
                                    </td>
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
                                <label for="add_type">Add For</label>
                                <select name="add_type" id="add_type" class="form-control" required>
                                    <option value="member">Member</option>
                                    <option value="group">Group</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12" id="member-select-wrap">
                            <div class="form-group form-group-default">
                                <label for="member_id">Member</label> 
                                <select name="member_id" id="member_id" class="form-control select2" required>
                                    <option value="">Select an option</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12" id="group-select-wrap" style="display:none;">
                            <div class="form-group form-group-default">
                                <label for="group_id">Group</label>
                                <select name="group_id" id="group_id" class="form-control select2">
                                    <option value="">-- Select Group --</option>
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
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

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Edit</span>
                    <span class="fw-light"> Attendance</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAttendanceForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="attendance_id" id="edit_attendance_id" value="">
                        <input type="hidden" name="add_type" value="member">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="attendance_date" value="{{ $event->event_date->format('Y-m-d') }}">
                        <input type="hidden" name="member_id" id="edit_member_id" value="">
                    <div class="row">
                        <div class="col-md-6 pr-0">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select name="status" id="edit_status" class="form-control" required>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Attendance Type</label>
                                <select name="attendance_type" id="edit_attendance_type" class="form-control" required>
                                    <option value="in-person">In-Person</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Notes</label>
                                <textarea name="notes" id="edit_notes" class="form-control" placeholder="Enter notes"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" id="submitEditAttendanceBtn" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')


<script>
    $(document).ready(function() {
        initMemberSearchSelect2('#member_id', {
            url: "{{ route('members.search') }}",
            dropdownParent: '#addAttendanceModal',
            extraData: function() {
                return {
                    event_id: $('#addAttendanceForm input[name="event_id"]').val()
                };
            }
        });

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
                        // response.data can be a single attendance or an array (for group)
                        var items = Array.isArray(response.data) ? response.data : [response.data];
                        items.forEach(function(item) {
                            var newRow = `
                                <tr id="attendance-row-${item.id}">
                                    <td>${item.member.full_name}</td>
                                    <td><span class="badge badge-${item.status === 'present' ? 'success' : (item.status === 'absent' ? 'danger' : 'warning')}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span></td>
                                    <td><span class="badge badge-${item.attendance_type === 'in-person' ? 'info' : 'primary'}">${item.attendance_type.charAt(0).toUpperCase() + item.attendance_type.slice(1)}</span></td>
                                    <td>${item.user.name}</td>
                                    <td>${item.notes || ''}</td>
                                </tr>
                            `;
                            $('#no-attendance-row').remove();
                            $('#attendance-tbody').append(newRow);

                            // Remove member from modal dropdown if present
                            $('#member_id option[value="' + item.member_id + '"]').remove();
                        });

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

        // toggle member/group fields
        $('#add_type').on('change', function() {
            var val = $(this).val();
            if (val === 'group') {
                $('#member-select-wrap').hide();
                $('#member_id').prop('required', false);
                $('#group-select-wrap').show();
                $('#group_id').prop('required', true);
            } else {
                $('#group-select-wrap').hide();
                $('#group_id').prop('required', false);
                $('#member-select-wrap').show();
                $('#member_id').prop('required', true);
            }
        });

        // Edit attendance: open modal and populate
        $(document).on('click', '.edit-att', function() {
            var btn = $(this);
            var id = btn.data('id');
            var status = btn.data('status');
            var type = btn.data('type');
            var notes = btn.data('notes');
            var url = btn.data('url');
            var memberId = btn.data('member-id');

            $('#edit_attendance_id').val(id);
            $('#edit_status').val(status);
            $('#edit_attendance_type').val(type);
            $('#edit_notes').val(notes);
            $('#edit_member_id').val(memberId);
            $('#submitEditAttendanceBtn').data('url', url);
            $('#editAttendanceModal').modal('show');
        });

        // submit edit
        $('#submitEditAttendanceBtn').on('click', function(e) {
            e.preventDefault();
            var btn = $(this);
            var url = btn.data('url');
            var form = $('#editAttendanceForm');
            btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editAttendanceModal').modal('hide');
                        Swal.fire({icon: 'success', title: 'Saved', text: response.message});
                        var item = response.data;
                        var row = $('#attendance-row-' + item.id);
                        if (row.length) {
                            row.find('td').eq(1).html(item.status === 'present' ? '<span class="badge badge-success">Present</span>' : (item.status === 'absent' ? '<span class="badge badge-danger">Absent</span>' : '<span class="badge badge-warning">Excused</span>'));
                            row.find('td').eq(2).html(item.attendance_type === 'in-person' ? '<span class="badge badge-info">In-Person</span>' : '<span class="badge badge-primary">Online</span>');
                            row.find('td').eq(4).text(item.notes || '');
                        }
                    } else {
                        Swal.fire({icon: 'error', title: 'Error', text: response.message || 'Failed to save.'});
                    }
                },
                error: function(jqXHR) {
                    var errors = jqXHR.responseJSON && jqXHR.responseJSON.errors ? jqXHR.responseJSON.errors : null;
                    var msg = 'An error occurred.';
                    if (errors) msg = Object.values(errors).join('\n');
                    Swal.fire({icon: 'error', title: 'Oops', text: msg});
                },
                complete: function() {
                    btn.attr('disabled', false).html('Save');
                }
            });
        });

        // delete attendance
        $(document).on('click', '.delete-att', function() {
            var btn = $(this);
            var id = btn.data('id');
            var url = btn.data('url');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the attendance record.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then(function(result) {
                if (result.isConfirmed) {
                    btn.attr('disabled', true);
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {_method: 'DELETE', _token: '{{ csrf_token() }}'},
                        success: function(response) {
                            if (response.code && response.code === 1) {
                                $('#attendance-row-' + id).remove();
                                Swal.fire({icon: 'success', title: 'Deleted', text: response.msg});
                            } else {
                                Swal.fire({icon: 'error', title: 'Error', text: response.msg || 'Failed to delete.'});
                            }
                        },
                        error: function() {
                            Swal.fire({icon: 'error', title: 'Error', text: 'Request failed.'});
                        },
                        complete: function() {
                            btn.attr('disabled', false);
                        }
                    });
                }
            });
        });
    });


</script>

@endpush