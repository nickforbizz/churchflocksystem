@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> HomeCell </h4>
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
                <a href="#">HomeCell </a>
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
                        <a href="{{ route('homecells.index') }}" class="btn btn-sm btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="homecells-create"
                        action="@if(isset($homecell->id))  
                            {{ route('homecells.update', ['homecell' => $homecell->id]) }}
                            @else {{ route('homecells.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($homecell->id))
                        @method('PUT')
                        @endif


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="primary_cell" class="placeholder"> Primary Cell </label>
                                    <input id="primary_cell" type="text" class="form-control @error('primary_cell') is-invalid @enderror" placeholder="Enter Primary Cell" name="primary_cell" value="{{ old('primary_cell', $homecell->primary_cell ?? '') }}" required />
                                    @error('primary_cell') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prayercell_leader" class="placeholder"> Leader of Prayer Cell </label>
                                    <input id="prayercell_leader" type="text" class="form-control @error('prayercell_leader') is-invalid @enderror" placeholder="Enter Leader of Prayer Cell" name="prayercell_leader" value="{{ old('prayercell_leader', $homecell->prayercell_leader ?? '') }}" required />
                                    @error('prayercell_leader') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_joined" class="placeholder"> Date Joined </label>
                                    <input id="date_joined" type="date" class="form-control @error('date_joined') is-invalid @enderror" placeholder="Enter Date Joined" name="date_joined" value="{{ old('date_joined', $homecell->date_joined ?? '') }}" required />
                                    @error('date_joined') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_officially_received" class="placeholder"> Date Officially Received </label>
                                    <input id="date_officially_received" type="date" class="form-control @error('date_officially_received') is-invalid @enderror" placeholder="Enter Date Officially Received" name="date_officially_received" value="{{ old('date_officially_received', $homecell->date_officially_received ?? '') }}" required />
                                    @error('date_officially_received') <span class="text-danger">{{ $message }}</span>
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