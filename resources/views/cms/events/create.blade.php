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
                <a href="#">Events </a>
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
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="events-create"
                        action="@if(isset($churchEvent->id))  
                            {{ route('events.update', ['event' => $churchEvent->id]) }}
                            @else {{ route('events.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($churchEvent->id))
                        @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title"> Title </label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Enter Title" name="title" value="{{ old('title', $churchEvent->title ?? '') }}" required />
                                    @error('title') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="event_date" class="placeholder"> Event Date </label>
                                    <input id="event_date" type="date" class="form-control @error('event_date') is-invalid @enderror" name="event_date" value="{{ old('event_date', isset($member->event_date) ? $member->event_date->format('Y-m-d') : '') }}" required />
                                    @error('event_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description" class="placeholder"> Description</label>
                                    <textarea name="description" id="description" placeholder="Enter Description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $churchEvent->description ?? '') }}</textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="active"> Active </label>
                                    <select name="active" id="active" class="form-control @error('active') is-invalid @enderror">
                                        <option value="1" {{ old('active', $churchEvent->active ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('active', $churchEvent->active ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('active') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- .form-group -->
                            </div>
                        </div>


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