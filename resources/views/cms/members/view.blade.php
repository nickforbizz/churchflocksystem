@extends('layouts.cms')

@push('styles')
<style>
    .info-group {
        margin-bottom: 0.75rem;
    }

    .info-label {
        font-weight: 600;
        color: #666;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.5rem;
    }

    .info-text {
        color: #2e3338;
        font-size: 1rem;
        margin: 0;
        padding: 0.5rem 0.75rem;
        background-color: #f8f9fa;
        border-left: 3px solid #3f51b5;
        border-radius: 3px;
    }

    .info-text a {
        color: #3f51b5;
        text-decoration: none;
    }

    .info-text a:hover {
        text-decoration: underline;
    }

    .card-subtitle {
        border-bottom: 2px solid #e3e6f0;
        padding-bottom: 0.75rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.7rem;
    }

    hr {
        margin: 2rem 0;
        border-top: 1px solid #e3e6f0;
    }
</style>
@endpush


@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Member Details</h4>
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
                <a href="{{ route('members.index') }}">Members</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">View</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">{{ $member->full_name ?? 'Member' }}</h4>
                        <a href="{{ route('members.index') }}" class="btn btn-sm btn-primary btn-round ml-auto">
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Personal Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Full Name</label>
                                    <p class="info-text">{{ $member->full_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Member Number</label>
                                    <p class="info-text">{{ $member->member_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Email</label>
                                    <p class="info-text">
                                        <a href="mailto:{{ $member->email }}">{{ $member->email ?? 'N/A' }}</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Phone</label>
                                    <p class="info-text">
                                        <a href="tel:{{ $member->phone }}">{{ $member->phone ?? 'N/A' }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Date of Birth</label>
                                    <p class="info-text">
                                        @if($member->birth_date)
                                            {{ $member->birth_date->format('Y-m-d') }}
                                            <span class="badge badge-info ml-2">{{ $member->birth_date->age }} years</span>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Gender</label>
                                    <p class="info-text">{{ ucfirst($member->gender ?? 'N/A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Marital Status</label>
                                    <p class="info-text">{{ ucfirst($member->marital_status ?? 'N/A') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Occupation</label>
                                    <p class="info-text">{{ $member->occupation ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Family Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Family Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Spouse/Next of Kin</label>
                                    <p class="info-text">{{ $member->spouse ?? $member->next_of_kin ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Spouse/Next of Kin Phone</label>
                                    <p class="info-text">
                                        @if($member->spouse_number || $member->next_of_kin_number)
                                            <a href="tel:{{ $member->spouse_number ?? $member->next_of_kin_number }}">
                                                {{ $member->spouse_number ?? $member->next_of_kin_number }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Contact & Residence Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Contact & Residence Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Postal Address</label>
                                    <p class="info-text">{{ $member->postal_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Residency</label>
                                    <p class="info-text">{{ $member->residency ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Church Membership Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Church Membership</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Group</label>
                                    <p class="info-text">
                                        <span class="badge badge-primary">{{ $member->group->name ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Homecell</label>
                                    <p class="info-text">{{ $member->homecell->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Join Date</label>
                                    <p class="info-text">
                                        @if($member->join_date)
                                            {{ $member->join_date->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Official Join Date</label>
                                    <p class="info-text">
                                        @if($member->official_join_date)
                                            {{ $member->official_join_date->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Spiritual Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Spiritual Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Born Again</label>
                                    <p class="info-text">
                                        @if($member->born_again)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">When Spirit Filled </label>
                                    <p class="info-text">
                                        @if($member->spirit_filled_when)
                                            {{ $member->spirit_filled_when->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">When Water Immersed </label>
                                    <p class="info-text">
                                        @if($member->water_immersed_when)
                                            {{ $member->water_immersed_when->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Status</label>
                                    <p class="info-text">
                                        @if($member->active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Previous Church Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Previous Church Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Joining From Church</label>
                                    <p class="info-text">{{ $member->from_church ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Church Branch </label>
                                    <p class="info-text">{{ $member->from_church_branch ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Pastor </label>
                                    <p class="info-text">{{ $member->from_church_pastor ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Pastor Phone</label>
                                    <p class="info-text">
                                        @if($member->from_church_pastor_number)
                                            <a href="tel:{{ $member->from_church_pastor_number }}">
                                                {{ $member->from_church_pastor_number }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Ministries Section -->
                    @if($member->ministries->count() > 0)
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">Ministries</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    @foreach($member->ministries as $ministry)
                                        <span class="badge badge-info mr-2 mb-2">{{ $ministry->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <!-- System Information Section -->
                    <div class="mb-4">
                        <h5 class="card-subtitle mb-3" style="color: #2e3338; font-weight: 600;">System Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Created By</label>
                                    <p class="info-text">{{ $member->user->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Created At</label>
                                    <p class="info-text">
                                        @if($member->created_at)
                                            {{ $member->created_at->format('Y-m-d H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Last Updated</label>
                                    <p class="info-text">
                                        @if($member->updated_at)
                                            {{ $member->updated_at->format('Y-m-d H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-group">
                                    <label class="info-label">Member ID</label>
                                    <p class="info-text">{{ $member->id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons Section -->
                    <div class="mt-4">
                        @if(auth()->user()->hasAnyRole('superadmin|admin|editor') || auth()->id() == $member->created_by)
                            <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary">
                                <i class="fa fa-edit mr-2"></i>
                                Edit Member
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('superadmin'))
                            <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this member?')) { delMember('{{ $member->id }}', '{{ route('members.destroy', $member->id) }}'); }">
                                <i class="fa fa-trash mr-2"></i>
                                Delete Member
                            </button>
                        @endif

                        <a href="{{ route('members.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left mr-2"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
</div>
<!-- .page-inner -->
@endsection


@push('scripts')
<script>
    function delMember(id, route) {
        $.ajax({
            url: route,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.code == 1) {
                    alert(response.msg);
                    window.location.href = "{{ route('members.index') }}";
                } else {
                    alert(response.msg);
                }
            },
            error: function(error) {
                alert('An error occurred while deleting the member.');
                console.error(error);
            }
        });
    }

    $(document).ready(function() {
        // Add any additional initialization here
    });
</script>
@endpush