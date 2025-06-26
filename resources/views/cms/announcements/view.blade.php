@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Announcements </h4>
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
                <a href="#"> Announcements </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#"> View </a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Announcement Details</h4>
                        <div class="card-tools">
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary btn-round">
                                <i class="flaticon-left-arrow-4 mr-2"></i>
                                Back
                            </a>
                            @can('send announcement to groups')
                            <button type="button" class="btn btn-info btn-round" data-toggle="modal" data-target="#sendToGroupsModal">
                                <i class="fa fa-paper-plane"></i> Send to Groups
                            </button>
                            @endcan
                            @can('edit announcement')
                            <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-warning btn-round">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            @endcan
                            @can('delete announcement')
                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-round" onclick="return confirm('Are you sure you want to delete this announcement?');">
                                    <i class="fa fa-times"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-primary">{{ $announcement->title }}</h2>
                            <p class="text-muted">
                                <strong>Created By:</strong> {{ $announcement->user->name ?? 'N/A' }}
                                <br>
                                <strong>Created At:</strong> {{ $announcement->created_at->format('Y-m-d H:i') }}
                            </p>
                            <hr>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Starts:</strong> <span class="badge badge-info">{{ $announcement->starts_at ? $announcement->starts_at->format('D, M j, Y g:i A') : 'N/A' }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ends:</strong> <span class="badge badge-warning">{{ $announcement->ends_at ? $announcement->ends_at->format('D, M j, Y g:i A') : 'N/A' }}</span></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Visibility:</strong> {!! $announcement->is_public ? '<span class="badge badge-success">Public</span>' : '<span class="badge badge-danger">Private</span>' !!}</p>
                        </div>
                    </div>

                    @if($announcement->description)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h4>Description</h4>
                            <div class="card-text bg-light p-3 rounded">
                                <p class="mb-0">{{ $announcement->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($announcement->body)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h4>Body</h4>
                            <div class="card-text bg-light p-3 rounded">
                                {!! nl2br(e($announcement->body)) !!} {{-- Use nl2br for new lines, e for escaping --}}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sent to Groups Card --}}
    @if($announcement->groups->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Distribution History</h4></div>
                <div class="card-body">
                    <p>This announcement has been sent to the following groups:</p>
                    @foreach($announcement->groups as $group)
                        <span class="badge badge-primary mr-1 mb-1" style="font-size: 14px;">{{ $group->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- .page-inner -->

<!-- Send to Groups Modal -->
<div class="modal fade" id="sendToGroupsModal" tabindex="-1" role="dialog" aria-labelledby="sendToGroupsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendToGroupsModalLabel">Send Announcement to Groups</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('announcements.sendToGroups', $announcement->id) }}" method="POST">
                @csrf
                <div class="modal-body">

                   

                    <div class="form-group">
                        <label for="groups" class="col-form-label"> Select Groups: </label>
                        <select name="group_ids[]" id="groups" class="form-control select2" multiple required @if($groups->isEmpty()) disabled @endif>
                            @forelse($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @empty
                            <option disabled>No active groups found. Please create one first.</option>
                            @endforelse
                        </select>
                    </div>


                    <div class="row">
                        <div class="col-sm-12">                           
                            <div class="form-group">
                                <label for="message" class="col-form-label">Message:</label>
                                <textarea class="form-control" id="message" rows="3" name="message" placeholder="Enter message" ></textarea>
                            </div>
                        </div>

                         
                    </div>



                    <div class="form-group">
                        <label>Send via:</label><br>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" id="send_email" name="send_via[]" value="email" checked>
                            <label class="form-check-label" for="send_email">Email</label>
                        </div>
                        <div class="form-check-inline">
                            <input class="form-check-input" type="checkbox" id="send_sms" name="send_via[]" value="sms">
                            <label class="form-check-label" for="send_sms">SMS (Requires SMS Gateway)</label>
                        </div>
                    </div>
                </div>
                <!-- .modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        $('#groups').select2({
            width: '100%',
            placeholder: 'Select Groups',
            allowClear: true,
            // theme: 'bootstrap4',
            // Important for modals
            dropdownParent: $('#sendToGroupsModal') 
        });
    });
</script>

@endpush