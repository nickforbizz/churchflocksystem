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
                <a href="{{ route('donations.index') }}"> Donations & Givings </a>
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
                        <a href="{{ route('donations.index') }}" class="btn btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="donation-create"
                        action="@if(isset($donation->id))  
                            {{ route('donations.update', ['donation' => $donation->id]) }}
                            @else {{ route('donations.store' ) }} @endif"
                        method="post"
                        enctype="multipart/form-data">

                        @csrf
                        @if(isset($donation->id))
                        @method('PUT')
                        <input type="hidden" name="created_by" value="{{ auth()->id() }}">
                        @endif


                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="member_id">Member</label>
                                    <select name="member_id" id="member_id" class="form-control @error('member_id') is-invalid @enderror">
                                        <option value="">-- Select Member --</option>
                                        @forelse($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id', $donation->member_id ?? '') == $member->id ? 'selected' : '' }}> {{ $member->full_name }} </option>
                                        @empty
                                        <option disabled> -- No members available -- </option>
                                        @endforelse
                                    </select>
                                    @error('member_id') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="amount" > Amount </label>
                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" name="amount" value="{{ old('amount', isset($donation->amount) ? $donation->amount->format('Y-m-d') : '') }}" required />
                                    @error('amount') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="row">                          

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="purpose">  Purpose </label>
                                    <select name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror">
                                        <option value="Tithe" {{ old('purpose', $donation->purpose ?? '') == 'Tithe' ? 'selected' : '' }}> Tithe</option>
                                        <option value="Offering" {{ old('purpose', $donation->purpose ?? '') == 'Offering' ? 'selected' : '' }}> Offering </option>
                                        <option value="Thanksgiving" {{ old('purpose', $donation->purpose ?? '') == 'Thanksgiving' ? 'selected' : '' }}> Thanksgiving </option>
                                        <option value="Pledge" {{ old('purpose', $donation->purpose ?? '') == 'Pledge' ? 'selected' : '' }}> Pledge </option>
                                        <option value="Other" {{ old('purpose', $donation->purpose ?? '') == 'Other' ? 'selected' : '' }}> Other </option>
                                    </select>
                                    @error('purpose') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="method">  Method </label>
                                    <select name="method" id="method" class="form-control @error('method') is-invalid @enderror">
                                        <option value="Cash" {{ old('method', $donation->method ?? '') == 'Cash' ? 'selected' : '' }}> Cash</option>
                                        <option value="M-Pesa" {{ old('method', $donation->method ?? '') == 'M-Pesa' ? 'selected' : '' }}> M-Pesa </option>
                                        <option value="Bank" {{ old('method', $donation->method ?? '') == 'Bank' ? 'selected' : '' }}> Bank </option>
                                        <option value="Other" {{ old('method', $donation->method ?? '') == 'Other' ? 'selected' : '' }}> Other </option>
                                    </select>
                                    @error('method') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!-- .row -->

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="date" class="placeholder">  Date </label>
                                    <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date', isset($donation->date) ? $donation->date->format('Y-m-d') : '') }}" required />
                                    @error('date') <span class="text-danger">{{ $message }}</span>
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