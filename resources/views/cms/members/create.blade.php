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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name" class="placeholder"> Full Name </label>
                                    <input id="full_name" type="text" class="form-control shadow @error('full_name') is-invalid @enderror" placeholder="Enter Full Name" name="full_name" value="{{ old('full_name', $member->full_name ?? '') }}" required />
                                    @error('full_name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="placeholder"> Email </label>
                                    <input id="email" type="email" class="form-control shadow @error('email') is-invalid @enderror" placeholder="Enter Email" name="email" value="{{ old('email', $member->email ?? '') }}" required />
                                    @error('email') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member_number" class="text-danger" > Member Number
                                        <a href="#" tabindex="0" class="ml-2" role="button" data-toggle="popover" data-trigger="focus" title="Member Numbers" data-html="true" data-content="Next: {{ $nextMemberNumber ?? 'N/A' }}<br>Skipped: {{ !empty($skipped_member_numbers) ? implode(', ', $skipped_member_numbers) : 'None' }}">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </label>
                                    <input id="member_number" type="text" class="form-control shadow @error('member_number') is-invalid @enderror" placeholder="Enter Member Number" name="member_number" value="{{ old('member_number', $member->member_number ?? $nextMemberNumber ?? '') }}" required />
                                    @error('member_number') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">This is a unique identifier for each member. It can be auto-generated or manually entered.</small>
                                </div>
                            </div>
                        </div>






                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="placeholder"> Phone </label>
                                    <input id="phone" type="text" class="form-control shadow @error('phone') is-invalid @enderror" placeholder="Enter Phone" name="phone" value="{{ old('phone', $member->phone ?? '') }}" required />
                                    @error('phone') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_date" class="placeholder"> Date of Birth </label>
                                    <input id="birth_date" type="date" class="form-control shadow @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date', isset($member->birth_date) ? $member->birth_date->format('Y-m-d') : '') }}" required />
                                    @error('birth_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender"> Gender </label>
                                    <select name="gender" id="gender" class="form-control shadow @error('gender') is-invalid @enderror">
                                        <option value="male" {{ old('gender', $member->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $member->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $member->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="marital_status"> Marital Status </label>
                                    <select name="marital_status" id="marital_status" class="form-control shadow @error('marital_status') is-invalid @enderror">
                                        <option value="single" {{ old('marital_status', $member->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('marital_status', $member->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="other" {{ old('marital_status', $member->marital_status ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('marital_status') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="official_join_date" class="placeholder"> Official Join Date </label>
                                    <input id="official_join_date" type="date" class="form-control @error('official_join_date') is-invalid @enderror" name="official_join_date" value="{{ old('official_join_date', isset($member->official_join_date) ? $member->official_join_date->format('Y-m-d') : '') }}" />
                                    @error('official_join_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="join_date" class="placeholder"> Join Date </label>
                                    <input id="join_date" type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" value="{{ old('join_date', isset($member->join_date) ? $member->join_date->format('Y-m-d') : '') }}" required />
                                    @error('join_date') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .row -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_of_kin" class="placeholder"> Next of Kin/Spouse </label>
                                    <input id="next_of_kin" type="text" class="form-control @error('next_of_kin') is-invalid @enderror" placeholder="Enter Next of Kin/Spouse Name" name="next_of_kin" value="{{ old('next_of_kin', $member->next_of_kin ?? '') }}" />
                                    @error('next_of_kin') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="next_of_kin_number" class="placeholder"> Next of Kin/Spouse Phone </label>
                                    <input id="next_of_kin_number" type="text" class="form-control @error('next_of_kin_number') is-invalid @enderror" placeholder="Enter Phone" name="next_of_kin_number" value="{{ old('next_of_kin_number', $member->next_of_kin_number ?? '') }}" />
                                    @error('next_of_kin_number') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .row -->
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="residency" class="placeholder"> Residency </label>
                                    <input id="residency" type="text" class="form-control shadow @error('residency') is-invalid @enderror" placeholder="Enter Residency" name="residency" value="{{ old('residency', $member->residency ?? '') }}" required />
                                    @error('residency') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation" class="placeholder"> Occupation </label>
                                    <input id="occupation" type="text" class="form-control shadow @error('occupation') is-invalid @enderror" placeholder="Enter Occupation" name="occupation" value="{{ old('occupation', $member->occupation ?? '') }}" required />
                                    @error('occupation') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal_address" class="placeholder"> Postal Address </label>
                                    <input id="postal_address" type="text" class="form-control shadow @error('postal_address') is-invalid @enderror" placeholder="Enter Postal Address" name="postal_address" value="{{ old('postal_address', $member->postal_address ?? '') }}" />
                                    @error('postal_address') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .row -->


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="born_again"> Born Again </label>
                                    <select name="born_again" id="born_again" class="form-control shadow @error('born_again') is-invalid @enderror">
                                        <option value="1" {{ old('born_again', $member->born_again ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('born_again', $member->born_again ?? '') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('born_again') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="spirit_filled_when" class="placeholder"> When Spirit Filled </label>
                                    <input id="spirit_filled_when" type="date" class="form-control shadow @error('spirit_filled_when') is-invalid @enderror" name="spirit_filled_when" value="{{ old('spirit_filled_when', isset($member->spirit_filled_when) ? $member->spirit_filled_when->format('Y-m-d') : '') }}" />
                                    @error('spirit_filled_when') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="water_immersed_when" class="placeholder"> When Water Filled </label>
                                    <input id="water_immersed_when" type="date" class="form-control shadow @error('water_immersed_when') is-invalid @enderror" name="water_immersed_when" value="{{ old('water_immersed_when', isset($member->water_immersed_when) ? $member->water_immersed_when->format('Y-m-d') : '') }}" />
                                    @error('water_immersed_when') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .row -->
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_church" class="placeholder"> Joining from Church? </label>
                                    <input id="from_church" type="text" class="form-control @error('from_church') is-invalid @enderror" placeholder="Enter Postal Address" name="from_church" value="{{ old('from_church', $member->from_church ?? '') }}" />
                                    @error('from_church') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_church_branch" class="placeholder"> Branch of that Church </label>
                                    <input id="from_church_branch" type="text" class="form-control @error('from_church_branch') is-invalid @enderror" placeholder="Enter Postal Address" name="from_church_branch" value="{{ old('from_church_branch', $member->from_church_branch ?? '') }}" />
                                    @error('from_church_branch') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_church_pastor" class="placeholder"> Pastor of that Church </label>
                                    <input id="from_church_pastor" type="text" class="form-control @error('from_church_pastor') is-invalid @enderror" placeholder="Enter Postal Address" name="from_church_pastor" value="{{ old('from_church_pastor', $member->from_church_pastor ?? '') }}" />
                                    @error('from_church_pastor') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_church_pastor_number" class="placeholder"> Pastor's Number </label>
                                    <input id="from_church_pastor_number" type="text" class="form-control @error('from_church_pastor_number') is-invalid @enderror" placeholder="Enter Postal Address" name="from_church_pastor_number" value="{{ old('from_church_pastor_number', $member->from_church_pastor_number ?? '') }}" />
                                    @error('from_church_pastor_number') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ministries"> Ministries </label>
                                    <select name="ministries[]" id="ministry" multiple="multiple" class="form-control select2">
                                        @forelse($ministries as $ministry)
                                        <option value="{{ $ministry->name }}" @if(in_array($ministry->name, $member_ministries)) selected @endif > {{ $ministry->name }} </option>
                                        @empty
                                        <option selected disabled> -- No item -- </option>
                                        @endforelse
                                    </select>
                                    <input type="checkbox" id="select2_checkAll">Select All<br>


                                    @error('ministries') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <!-- .row -->

                        <div class="row">
                            <div class="col-md-6">
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
        // initialize bootstrap popovers for member number info
        $('[data-toggle="popover"]').popover();
    });
</script>

@endpush