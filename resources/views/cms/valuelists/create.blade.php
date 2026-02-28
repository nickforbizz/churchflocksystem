@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Valuelist </h4>
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
                <a href="{{ route('valuelists.index') }}">Valuelist</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ isset($valuelist) ? 'Edit' : 'Create' }}</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add|Edit Record</h4>
                        <a href="{{ route('valuelists.index') }}" class="btn btn-primary btn-round ml-auto" >
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a> 
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="valuelists-create" action="{{ isset($valuelist) ? route('valuelists.update', $valuelist->id) : route('valuelists.store') }}" method="post">

                        @csrf
                        @if(isset($valuelist->id))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">-- Select or Enter New Type --</option>
                                        @isset($valuelist)
                                            <option value="{{ $valuelist->type }}" selected>{{ $valuelist->type }}</option>
                                        @endisset
                                        @foreach($types as $type)
                                            @if(!isset($valuelist) || $valuelist->type !== $type)
                                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" placeholder="Enter your value ..." name="value" value="{{ old('value', $valuelist->value ?? '') }}" required>
                                    @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>                                
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="index">Index <small class="text-muted">(Auto-calculated)</small></label>
                                    <input type="text" class="form-control @error('index') is-invalid @enderror" id="index" name="index" value="{{ old('index', $valuelist->index ?? '') }}" readonly required>
                                    @error('index')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>                                
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="active">Status</label>
                                    <select name="active" id="active" class="form-control @error('active') is-invalid @enderror">
                                        <option value="1" {{ old('active', $valuelist->active ?? '1') == '1' ? 'selected' : '' }}> Active</option>
                                        <option value="0" {{ old('active', $valuelist->active ?? '1') == '0' ? 'selected' : '' }}> Inactive </option>
                                    </select>
                                    @error('active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </form>
                    <!-- End form -->

                </div>
                <div class="card-action">
                    <button type="submit" form="valuelists-create" class="btn btn-success">Submit</button>
                    <a href="{{ route('valuelists.index') }}" class="btn btn-danger">Cancel</a>
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
        var isEditMode = {{ isset($valuelist) ? 'true' : 'false' }};
        var existingTypes = @json($types);
        
        // Initialize Select2 with tagging and AJAX search
        $('#type').select2({
            tags: true,
            placeholder: '-- Select or Enter New Type --',
            allowClear: true,
            ajax: {
                url: '{{ route("valuelists.types.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });

        // Handle type change to auto-calculate index
        $('#type').on('change', function() {
            var selectedType = $(this).val();
            
            if (!selectedType) {
                $('#index').val('');
                return;
            }

            // Check if this is a new tag (not in existing types from DB)
            var selectedData = $(this).select2('data')[0];
            var isNewType = selectedData && selectedData.newTag;
            
            // Also check if type doesn't exist in any loaded options
            if (!isNewType && existingTypes.indexOf(selectedType) === -1) {
                isNewType = true;
            }
            
            if (isNewType) {
                // New type, set index to 1
                $('#index').val(1);
            } else {
                // Existing type, get next index via AJAX
                $.ajax({
                    url: '{{ route("valuelists.types.nextIndex") }}',
                    type: 'GET',
                    data: { type: selectedType },
                    success: function(response) {
                        $('#index').val(response.nextIndex);
                    },
                    error: function() {
                        $('#index').val(1);
                    }
                });
            }
        });

        // For create mode: trigger change if type has old value to calculate index
        // For edit mode: don't auto-calculate to preserve existing index
        @if(old('type') && !isset($valuelist))
            $('#type').trigger('change');
        @endif
    });
</script>

@endpush