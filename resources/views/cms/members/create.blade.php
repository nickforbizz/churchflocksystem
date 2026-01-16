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
                        <a href="{{ route('members.index') }}" class="btn btn-sm btn-primary btn-round ml-auto">
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
                                    <label for="full_name" class="placeholder"> Full Name </label>
                                    <input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" placeholder="Enter Full Name" name="full_name" value="{{ old('full_name', $member->full_name ?? '') }}" required />
                                    @error('full_name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email" class="placeholder"> Email </label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" name="email" value="{{ old('email', $member->email ?? '') }}" required />
                                    @error('email') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>






                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone" class="placeholder"> Phone </label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Enter Phone" name="phone" value="{{ old('phone', $member->phone ?? '') }}" required />
                                    @error('phone') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="birth_date" class="placeholder"> Date of Birth </label>
                                    <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', isset($member->birth_date) ? $member->birth_date->format('Y-m-d') : '') }}" required />
                                    @error('birth_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>





                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="join_date" class="placeholder"> Join Date </label>
                                    <input id="join_date" type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" value="{{ old('join_date', isset($member->join_date) ? $member->join_date->format('Y-m-d') : '') }}" required />
                                    @error('join_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="marital_status"> Marital Status </label>
                                    <select name="marital_status" id="marital_status" class="form-control @error('marital_status') is-invalid @enderror">
                                        <option value="single" {{ old('marital_status', $member->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status', $member->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="other" {{ old('marital_status', $member->marital_status ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('marital_status') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>









                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="group_id">Group</label>
                                    <select name="group_id" id="group_id" class="form-control @error('group_id') is-invalid @enderror">
                                        <option value="">-- Select Group --</option>
                                        @forelse($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('group_id', $member->group_id ?? '') == $group->id ? 'selected' : '' }}> {{ $group->name }} </option>
                                        @empty
                                        <option disabled> -- No groups available -- </option>
                                        @endforelse
                                    </select>
                                    @error('group_id') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="ministries"> Ministries </label>
                                    <select name="ministries[]" id="ministry" multiple="multiple" class="form-control select2">
                                        @forelse($ministries as $ministry)
                                            <option value="{{ $ministry->name }}" @if(in_array($ministry->name, $member_ministries)) selected @endif > {{ $ministry->name }} </option>
                                        @empty
                                            <option selected disabled> -- No item -- </option> 
                                        @endforelse
                                    </select>
                                    <input type="checkbox" id="select2_checkAll" >Select All<br>

                                    
                                    @error('ministries') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <!-- .row -->

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="homecell_id">HomeCell</label>
                                    <select name="homecell_id" id="homecell_id" class="form-control @error('homecell_id') is-invalid @enderror">
                                        <option value="">-- Select HomeCell --</option>
                                        @forelse($homecells as $homecell)
                                        <option value="{{ $homecell->id }}" {{ old('homecell_id', $member->homecell_id ?? '') == $homecell->id ? 'selected' : '' }}> {{ $homecell->primary_cell }} </option>
                                        @empty
                                        <option disabled> -- No groups available -- </option>
                                        @endforelse
                                    </select>
                                    @error('homecell_id') <span class="text-danger">{{ $message }}</span>
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
        $("#select2_checkAll").click(function() {
				if ($("#select2_checkAll").is(':checked')) {
					$("#ministry > option").prop("selected", "selected");
					$("#ministry").trigger("change");
				} else {
					$("#ministry > option").removeAttr("selected");
					$("#ministry").val('').trigger("change");
				}
			});
    });
</script>

@endpush