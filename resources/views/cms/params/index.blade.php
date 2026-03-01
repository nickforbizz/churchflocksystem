@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Params </h4>
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
                <a href="#"> Params</a>
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


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex ">
                        <h4 class="card-title">List of Available Record(s)</h4>

                        <div class="ml-auto">
                            <a href="{{ route('valuelists.index') }}" class="btn btn-info btn-round " >
                                <i class="flaticon-add mr-2"></i>
                                View Valuelist
                            </a> 
    
                            <a href="{{ route('params.create') }}" class="btn btn-primary btn-round ml-auto" >
                                <i class="flaticon-add mr-2"></i>
                                Add Param
                            </a> 
                            <!-- @can('create param') --> 
                            <!-- @endcan -->

                        </div>
                    </div>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <table id="tb_params" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DataType</th>
                                    <th>Group</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th>IsPublic</th>
                                    <th>Created By</th>
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
        $('#tb_params').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('params.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'data_type'
                },
                {
                    data: 'group'
                },
                {
                    data: 'key',
                },
                {
                    data: 'value'
                },
                {
                    data: 'is_public'
                },
                {
                    data: 'created_by'
                },					
                {
                    data: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        // #tb_params

       
    });


    
</script>

@endpush