@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Attendance </h4>
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
                <a href="#">Attendance </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Create</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add|Edit Record</h4>
                        <a href="{{ route('eventAttendance.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="eventAttendance-create"
                        action="@if(isset($eventAttendance->id))  
                            {{ route('eventAttendance.update', ['eventAttendance' => $eventAttendance->id]) }}
                            @else {{ route('eventAttendance.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($eventAttendance->id))
                        @method('PUT')
                        <input type="hidden" name="created_by" value="{{ auth()->id() }}">
                        @endif


                        <div class="row">
                            <div class="col-sm-6">

                            </div>

                            <div class="col-sm-6">

                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="member_id">Member</label>
                                    <select name="member_id" id="member_id" class="form-control @error('member_id') is-invalid @enderror">
                                        <option value="">-- Select Member --</option>
                                        @forelse($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id', $eventAttendance->member_id ?? '') == $member->id ? 'selected' : '' }}> {{ $member->full_name }} </option>
                                        @empty
                                        <option disabled> -- No groups available -- </option>
                                        @endforelse
                                    </select>
                                    @error('member_id') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="event_id">Event</label>
                                    <select name="event_id" id="event_id" class="form-control @error('event_id') is-invalid @enderror">
                                        <option value="">-- Select Event --</option>
                                        @forelse($events as $event)
                                        <option value="{{ $event->id }}" {{ old('event_id', $eventAttendance->event_id ?? '') == $event->id ? 'selected' : '' }}> {{ $event->title }} </option>
                                        @empty
                                        <option disabled> -- No Events available -- </option>
                                        @endforelse
                                    </select>
                                    @error('event_id') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>






                        





                        <div class="row">                          

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="status">  Status </label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="present" {{ old('status', $eventAttendance->status ?? '') == 'present' ? 'selected' : '' }}> Present</option>
                                        <option value="absent" {{ old('status', $eventAttendance->status ?? '') == 'absent' ? 'selected' : '' }}> Absent </option>
                                        <option value="excused" {{ old('status', $eventAttendance->status ?? '') == 'excused' ? 'selected' : '' }}> Excused </option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="attendance_type">  Attendance Type </label>
                                    <select name="attendance_type" id="attendance_type" class="form-control @error('attendance_type') is-invalid @enderror">
                                        <option value="in-person" {{ old('attendance_type', $eventAttendance->attendance_type ?? '') == 'in-person' ? 'selected' : '' }}> In-person </option>
                                        <option value="online" {{ old('attendance_type', $eventAttendance->attendance_type ?? '') == 'online' ? 'selected' : '' }}> Online </option>
                                    </select>
                                    @error('attendance_type') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!-- .row -->

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="attendance_date" class="placeholder"> Attendance Date </label>
                                    <input id="attendance_date" type="date" class="form-control @error('attendance_date') is-invalid @enderror" name="attendance_date" value="{{ old('attendance_date', isset($eventAttendance->attendance_date) ? $eventAttendance->attendance_date->format('Y-m-d') : '') }}" required />
                                    @error('attendance_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="notes" class="placeholder"> Notes </label>
                                    <textarea name="notes" id="notes" placeholder="Enter notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $churchEvent->notes ?? '') }}</textarea>
                                    @error('notes') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!-- .row -->





                        <div class="card">
                            <div class="form-group">
                                <button class="btn btn-success btn-round submit-form-btn float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- End form -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- .page-inner -->

@endsection


@push('scripts')
<script>
    $(document).ready(function() {

    });
</script>

@endpush