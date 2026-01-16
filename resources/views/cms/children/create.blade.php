@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Child </h4>
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
                <a href="#">Child </a>
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
                        <a href="{{ route('children.index') }}" class="btn btn-sm btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="children-create"
                        action="@if(isset($child->id))  
                            {{ route('children.update', ['child' => $child->id]) }}
                            @else {{ route('children.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($child->id))
                        @method('PUT')
                        @endif


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name" class="placeholder"> Name </label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Full Name" name="name" value="{{ old('name', $child->name ?? '') }}" required />
                                    @error('name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .row -->





                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="active"> Active </label>
                                    <select name="active" id="active" class="form-control @error('active') is-invalid @enderror">
                                        <option value="1" {{ old('active', $group->active ?? '') == '1' ? 'selected' : '' }}> Yes </option>
                                        <option value="0" {{ old('active', $group->active ?? '') == '0' ? 'selected' : '' }}> In Active </option>
                                    </select>
                                    @error('active') <span class="text-danger">{{ $message }}</span>
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