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
                <a href="{{ route('announcements.index') }}"> Announcements </a>
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
                        <a href="{{ route('announcements.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="announcement-create"
                        action="@if(isset($announcement->id))  
                            {{ route('announcements.update', ['announcement' => $announcement->id]) }}
                            @else {{ route('announcements.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($announcement->id))
                        @method('PUT')
                        @endif


                        <div class="row">
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label for="title"> Title </label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" placeholder="Enter title" name="title" value="{{ old('title', $announcement->title ?? '') }}" required />
                                    @error('title') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="purpose"> Is Public </label>
                                    <select name="is_public" id="is_public" class="form-control @error('is_public') is-invalid @enderror">
                                        <option value="1" {{ old('is_public', $announcement->is_public ?? '') == '1' ? 'selected' : '' }}> Yes</option>
                                        <option value="0" {{ old('is_public', $announcement->is_public ?? '') == '0' ? 'selected' : '' }}> No </option>
                                    </select>
                                    @error('is_public') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="starts_at"> Starts At </label>
                                    <input id="starts_at" type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" name="starts_at" value="{{ old('starts_at', isset($announcement->starts_at) ? $announcement->starts_at->format('Y-m-d\TH:i') : '') }}" required />
                                    @error('starts_at') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="ends_at"> Ends At </label>
                                    <input id="ends_at" type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" name="ends_at" value="{{ old('ends_at', isset($announcement->ends_at) ? $announcement->ends_at->format('Y-m-d\TH:i') : '') }}" required />
                                    @error('ends_at') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!-- .row -->



                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description"> Description</label>
                                    <textarea name="description" id="description" placeholder="Enter Description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $announcement->description ?? '') }}</textarea>
                                    @error('description') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="body"> Body</label>
                                    <textarea name="body" id="body" placeholder="Enter body" class="form-control @error('body') is-invalid @enderror">{{ old('body', $announcement->body ?? '') }}</textarea>
                                    @error('body') <span class="text-danger">{{ $message }}</span>
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
        const form = $('#announcement-create');
        const startsAtInput = $('#starts_at');
        const endsAtInput = $('#ends_at');
        const submitBtn = $('.submit-form-btn');

        function validateDates() {
            const startsAtValue = startsAtInput.val();
            const endsAtValue = endsAtInput.val();

            // Only validate if both fields have values (required attribute handles empty)
            if (!startsAtValue || !endsAtValue) {
                return true;
            }

            const startsAtDate = new Date(startsAtValue);
            const endsAtDate = new Date(endsAtValue);

            if (endsAtDate <= startsAtDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'The "Ends At" date and time must be after the "Starts At" date and time.',
                });
                return false;
            }
            return true;
        }

        form.on('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
                e.stopPropagation(); // Stop propagation to prevent other handlers from running
                // Re-enable the submit button if it was disabled by the general submit-form-btn logic
                submitBtn.attr('disabled', false).html('Submit');
                return false;
            }
        });
    });
</script>

@endpush