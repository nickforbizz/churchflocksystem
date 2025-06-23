@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Members </h4>
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
                <a href="#">Members </a>
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
                        <a href="{{ route('members.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="members-create"
                        action="@if(isset($member->id))  
                            {{ route('members.update', ['member' => $member->id]) }}
                            @else {{ route('members.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($member->id))
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
                                    <label for="name" class="placeholder">  Name </label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Full Name" name="name" value="{{ $member->name ?? '' }}" required />
                                    @error('name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                          
                        </div>





                        <div class="row">
                           

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="active"> Active </label>
                                    <select name="active" id="active" class="form-control">
                                        <option value="1" @if(isset($group->id)) {{ $group->active == '1' ? 'selected' : '' }} @endif> Yes </option>
                                        <option value="0" @if(isset($group->id)) {{ $group->active == '0' ? 'selected' : '' }} @endif> In Active </option>
                                    </select>
                                    @error('active') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
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