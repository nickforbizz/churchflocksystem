@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Valuelist </h4>
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
                <a href="#"> Valuelist</a>
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
                            <a href="{{ route('params.index') }}" class="btn btn-secondary btn-round " >
                                <i class="flaticon-back mr-2"></i>
                                Back to Params
                            </a> 
    
                            <a href="{{ route('valuelists.create') }}" class="btn btn-primary btn-round ml-auto" >
                                <i class="flaticon-add mr-2"></i>
                                Add Valuelist
                            </a> 
                            <!-- @can('create valuelist') --> 
                            <!-- @endcan -->

                        </div>
                    </div>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <table id="tb_valuelists" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Index</th>
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
        $('#tb_valuelists').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('valuelists.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'type'
                },
                {
                    data: 'value'
                },
                {
                    data: 'index'
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
                    orderable: true,
                    searchable: true
                },
            ]
        });
        // #tb_valuelists

       
    });


    
</script>

@endpush