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
                                        <option value="">Select an option</option>
                                        @php
                                        $selectedMemberId = old('member_id', $donation->member_id ?? '');
                                        $selectedMember = $selectedMemberId ? $members->firstWhere('id', (int) $selectedMemberId) : null;
                                        @endphp
                                        @if($selectedMember)
                                        <option value="{{ $selectedMember->id }}" selected>{{ $selectedMember->full_name }}</option>
                                        @endif
                                    </select>
                                    @error('member_id') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="amount"> Amount </label>
                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" name="amount" value="{{ old('amount', isset($donation->amount) ? $donation->amount->format('Y-m-d') : '') }}" required />
                                    @error('amount') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="purpose"> Purpose </label>
                                    <select name="purpose" id="purpose" class="form-control @error('purpose') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @forelse(App\Models\ValueList::getDropdownLower('givings_purpose') as $key => $value)
                                        <option value="{{ $key }}" {{ old('purpose', $donation->purpose ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @empty
                                        <option value="" disabled>Add items on ValueList</option>
                                        @endforelse
                                    </select>
                                    @error('purpose') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="method"> Method </label>
                                    <select name="method" id="method" class="form-control @error('method') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @forelse(App\Models\ValueList::getDropdownLower('payment_mode') as $key => $value)
                                        <option value="{{ $key }}" {{ old('method', $donation->method ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @empty
                                        <option value="" disabled>Add items on ValueList</option>
                                        @endforelse
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
                                    <label for="date" class="placeholder"> Date </label>
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
        initMemberSearchSelect2('#member_id', {
            url: "{{ route('members.search') }}"
        });
    });
</script>

@endpush