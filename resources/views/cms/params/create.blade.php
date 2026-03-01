@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Params </h4>
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
                <a href="{{ route('params.index') }}">Params</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ isset($param) ? 'Edit' : 'Create' }}</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add|Edit Record</h4>
                        <a href="{{ route('params.index') }}" class="btn btn-primary btn-round ml-auto" >
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a> 
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="params-create" action="{{ isset($param) ? route('params.update', $param->id) : route('params.store') }}" method="post">

                        @csrf
                        @if(isset($param->id))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group">Group</label>
                                    <input type="text" class="form-control @error('group') is-invalid @enderror" id="group" name="group" value="{{ old('group', $param->group ?? '') }}" required>
                                    @error('group')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="key">Key</label>
                                    <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $param->key ?? '') }}" required>
                                    @error('key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_type">Data Type</label>
                                    <select name="data_type" id="data_type" class="form-control @error('data_type') is-invalid @enderror">
                                        <option value="string" {{ old('data_type', $param->data_type ?? 'string') == 'string' ? 'selected' : '' }}>String</option>
                                        <option value="integer" {{ old('data_type', $param->data_type ?? '') == 'integer' ? 'selected' : '' }}>Integer</option>
                                        <option value="boolean" {{ old('data_type', $param->data_type ?? '') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                        <option value="json" {{ old('data_type', $param->data_type ?? '') == 'json' ? 'selected' : '' }}>JSON</option>
                                    </select>
                                    @error('data_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_public">Visibility</label>
                                    <select name="is_public" id="is_public" class="form-control @error('is_public') is-invalid @enderror">
                                        <option value="1" {{ old('is_public', $param->is_public ?? '1') == '1' ? 'selected' : '' }}>Public</option>
                                        <option value="0" {{ old('is_public', $param->is_public ?? '1') == '0' ? 'selected' : '' }}>Private</option>
                                    </select>
                                    @error('is_public')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="value">Value</label>
                            <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $param->value ?? '') }}">
                            @error('value') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $param->description ?? '') }}</textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                    <!-- End form -->

                </div>
                <div class="card-action">
                    <button type="submit" form="params-create" class="btn btn-success">Submit</button>
                    <a href="{{ route('params.index') }}" class="btn btn-danger">Cancel</a>
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
