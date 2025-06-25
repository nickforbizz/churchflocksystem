@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Donations & Givings </h4>
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
                <a href="#"> Donations & Givings</a>
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
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">List of Available Record(s)</h4>
                        @can('create event_attendance')
                        <a href="{{ route('donations.create') }}" class="btn btn-primary btn-round ml-auto" >
                            <i class="flaticon-add mr-2"></i>
                            Add Row
                        </a> 
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <table id="tb_donations" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Purpose</th>
                                    <th>Offering Date</th>
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
        $('#tb_donations').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('donations.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'member_id'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'method'
                },
                {
                    data: 'purpose'
                },
                {
                    data: 'date',
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
        // #tb_donations

       
    });


    
</script>

@endpush