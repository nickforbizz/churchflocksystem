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
                <a href="#"> Members</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Index</a>
            </li>
        </ul>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <strong>Info!</strong> This section allows you to manage Church Members.
                <!-- links to groups -->
                <div class="float-right">
                    <div class="dropdown">
                        <a class="btn btn-info btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Quick Links
                        </a>

                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xl-left">
                            <a class="dropdown-item" href="{{ route('groups.index') }}"> View Groups </a>
                            <a class="dropdown-item" href="{{ route('ministries.index') }}"> View Ministries </a>
                            <a class="dropdown-item" href="{{ route('homecells.index') }}"> View HomeCells </a>
                            <a class="dropdown-item" href="{{ route('children.index') }}"> View Children </a>
                        </div>
                    </div>

            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">List of Available Record(s)</h4>
                        

                        @can('create member')
                        <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary btn-round ml-auto" >
                            <i class="flaticon-add mr-2"></i>
                            Add New Member
                        </a> 
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <table id="tb_members" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Names</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Join Date</th>
                                    <th> Group </th>
                                    <!-- <th>Created By</th> -->
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
        $('#tb_members').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('members.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'full_name',
                },
                {
                    data: 'phone'
                },
                {
                    data: 'email'
                },
                // {
                //     data: 'birth_date'
                // },
                // {
                //     data: 'marital_status'
                // },
                {
                    data: 'join_date'
                },
                {
                    data: 'group_id',
                },
                // {
                //     data: 'created_by'
                // },					
                {
                    data: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
        // #tb_members

       
    });


    
</script>

@endpush