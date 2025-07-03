@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Events </h4>
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
                <a href="#">{{ isset($churchEvent) ? 'Edit' : 'Create' }}</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">{{ isset($churchEvent) ? 'Edit Event' : 'Add Event' }}</h4>
                        <a href="{{ route('events.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="events-create" action="{{ isset($churchEvent) ? route('events.update', $churchEvent->id) : route('events.store') }}"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($churchEvent->id))
                        @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $churchEvent->title ?? '') }}" required />
                                    @error('title') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_date">Event Date</label>
                                    <input id="event_date" type="date" class="form-control @error('event_date') is-invalid @enderror" name="event_date" value="{{ old('event_date', isset($churchEvent->event_date) ? $churchEvent->event_date->format('Y-m-d') : '') }}" required />
                                    @error('event_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location', $churchEvent->location ?? '') }}" />
                                    @error('location') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $churchEvent->description ?? '') }}</textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="active">Status</label>
                                    <select name="active" id="active" class="form-control @error('active') is-invalid @enderror">
                                        <option value="1" {{ old('active', $churchEvent->active ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('active', $churchEvent->active ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('active') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- End form -->

                </div>
                <div class="card-action">
                    <button type="submit" form="events-create" class="btn btn-success">Submit</button>
                    <a href="{{ route('events.index') }}" class="btn btn-danger">Cancel</a>
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