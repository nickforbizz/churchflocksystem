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
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">List of Available Record(s)</h4>

                        <div class="ml-auto">
                            @can('create member')
                            <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary btn-round">
                                <i class="flaticon-add mr-2"></i>
                                Add New Member
                            </a>
                            @endcan
                            @hasanyrole('admin|superadmin')
                            <button type="button" class="btn btn-sm btn-success btn-round" data-toggle="modal" data-target="#exportMembersModal">
                                <i class="fa fa-download mr-2"></i>
                                Export to Excel
                            </button>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-8 col-md-10 col-12">
                            <label class="mb-2 font-weight-bold">Quick Search</label>
                            <div class="input-group input-group-sm quick-search-group">

                                <select class="custom-select mr-2" id="quick_search_criteria" name="quick_search_criteria">
                                    <option value="">Choose...</option>
                                    <option value="phone">Phone Number</option>
                                    <option value="national_id">National ID</option>
                                    <option value="member_number">Member Number</option>
                                </select>
                                <input type="text" class="form-control mr-2" id="quick_search_value" name="quick_search_value" placeholder="Enter search value...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary form-control" type="button" id="btn_member_quick_search" disabled>
                                        <!-- icon -->
                                        <i class="flaticon-search mr-1"></i>
                                        Search
                                    </button>
                                    <button class="btn btn-outline-secondary form-control" type="button" id="btn_member_quick_clear">
                                        <i class="flaticon-close mr-1"></i>
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        @include('cms.helpers.partials.feedback')
                        <div id="members_table_wrapper" class="table-wrapper">
                            <div class="table-overlay">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <table id="tb_members" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Names</th>
                                        <th>Member ID</th>
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
</div>
<!-- .page-inner -->

<!-- Export Members Modal -->
@hasanyrole('admin|superadmin')
<div class="modal fade" id="exportMembersModal" tabindex="-1" role="dialog" aria-labelledby="exportMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportMembersModalLabel"><i class="fa fa-download mr-2"></i> Export Members to Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="exportMembersForm" action="{{ route('members.export') }}" target="_blank" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="export_filter">Export Filter</label>
                        <select class="form-control" id="export_filter" name="export_filter">
                            <option value="all">All Members</option>
                            <option value="groups">By Group(s)</option>
                        </select>
                    </div>
                    <div class="form-group" id="group_selection_wrapper" style="display: none;">
                        <label for="export_groups">Select Group(s)</label>
                        <select class="form-control select2" id="export_groups" name="group_ids[]" multiple="multiple">
                            {{-- Groups will be loaded via AJAX --}}
                        </select>
                        <small class="form-text text-muted">Select one or more groups to export their members only.</small>
                        <small class="form-text text-danger text-muted">Only groups with member(s) will be available for selection.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-download mr-2"></i> Download Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endhasanyrole

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .table-wrapper {
        position: relative;
        transition: opacity .2s ease;
    }

    .table-wrapper.table-loading {
        opacity: .55;
        pointer-events: none;
    }

    .table-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9;
    }

    .table-wrapper.table-loading .table-overlay {
        display: flex;
    }

    @media (max-width: 767.98px) {
        .quick-search-group {
            display: flex;
            flex-wrap: wrap;
        }

        .quick-search-group .input-group-prepend,
        .quick-search-group .custom-select,
        .quick-search-group .form-control,
        .quick-search-group .input-group-append {
            width: 100%;
            margin-bottom: .35rem;
        }

        .quick-search-group .input-group-append {
            display: flex;
        }

        .quick-search-group .input-group-append .btn {
            flex: 1 1 auto;
        }
    }
</style>
@endpush


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>
    $(document).ready(function() {
        const $criteria = $('#quick_search_criteria');
        const $value = $('#quick_search_value');
        const $searchBtn = $('#btn_member_quick_search');
        const $tableWrapper = $('#members_table_wrapper');
        let autoResettingFilters = false;

        const notify = {
            success: function(message) {
                if (window.toastr) {
                    toastr.success(message);
                    return;
                }
                console.log(message);
            },
            info: function(message) {
                if (window.toastr) {
                    toastr.info(message);
                    return;
                }
                console.info(message);
            },
            warning: function(message) {
                if (window.toastr) {
                    toastr.warning(message);
                    return;
                }
                console.warn(message);
            },
            error: function(message) {
                if (window.toastr) {
                    toastr.error(message);
                    return;
                }
                console.error(message);
            }
        };

        if (window.toastr) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                newestOnTop: true,
                timeOut: 3500
            };
        }

        function hasQuickSearchValue() {
            return $criteria.val() && $.trim($value.val()) !== '';
        }

        function updateSearchButtonState() {
            $searchBtn.prop('disabled', !hasQuickSearchValue());
        }

        function resetQuickSearch(showMessage = true) {
            autoResettingFilters = true;
            $criteria.val('');
            $value.val('');
            updateSearchButtonState();

            if (showMessage) {
                notify.info('Search has been cleared. Showing all members.');
            }

            membersTable.ajax.reload(function() {
                autoResettingFilters = false;
            });
        }

        $.fn.dataTable.ext.errMode = 'none';

        const membersTable = $('#tb_members').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('members.index') }}",
                data: function(d) {
                    d.quick_search_criteria = $criteria.val();
                    d.quick_search_value = $.trim($value.val());
                },
                error: function() {
                    if (!autoResettingFilters) {
                        notify.error('Quick search failed. Showing full list instead.');
                        resetQuickSearch(false);
                    }
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'full_name',
                },
                {
                    data: 'member_number',
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
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [7, 'desc']
            ]
        });

        $('#tb_members').on('processing.dt', function(e, settings, processing) {
            $tableWrapper.toggleClass('table-loading', processing);
        });

        $criteria.on('change', updateSearchButtonState);
        $value.on('input', updateSearchButtonState);
        updateSearchButtonState();

        $('#btn_member_quick_search').on('click', function() {
            if (!hasQuickSearchValue()) {
                notify.warning('Select a search criteria and enter a value first.');
                return;
            }

            membersTable.ajax.reload();
        });

        $('#quick_search_value').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#btn_member_quick_search').trigger('click');
            }
        });

        $('#btn_member_quick_clear').on('click', function() {
            resetQuickSearch(false);
        });

        membersTable.on('xhr.dt', function(e, settings, json) {
            if (autoResettingFilters || !hasQuickSearchValue() || !json) {
                return;
            }

            if (Number(json.recordsFiltered) === 0) {
                notify.warning('No matching members found. Showing full list.');
                resetQuickSearch(false);
            }
        });

        // Export Modal Functionality
        var groupsLoaded = false;
        
        $('#export_filter').on('change', function() {
            var filter = $(this).val();
            if (filter === 'groups') {
                $('#group_selection_wrapper').slideDown();
                // Load groups if not already loaded
                if (!groupsLoaded) {
                    loadGroups();
                }
            } else {
                $('#group_selection_wrapper').slideUp();
            }
        });

        function loadGroups() {
            $.ajax({
                url: '{{ route("groups.list") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var $select = $('#export_groups');
                    $select.empty();
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(group) {
                            $select.append(new Option(group.name, group.id, false, false));
                        });
                    }
                    
                    // Initialize Select2
                    $select.select2({
                        placeholder: 'Select groups...',
                        allowClear: true,
                        dropdownParent: $('#exportMembersModal')
                    });
                    
                    groupsLoaded = true;
                },
                error: function() {
                    notify.error('Failed to load groups. Please try again.');
                }
            });
        }

        $('#exportMembersForm').on('submit', function(e) {
            var filter = $('#export_filter').val();
            
            if (filter === 'groups') {
                var selectedGroups = $('#export_groups').val();
                if (!selectedGroups || selectedGroups.length === 0) {
                    e.preventDefault();
                    notify.warning('Please select at least one group.');
                    return false;
                }
            }
            
            // Close modal after submit
            setTimeout(function() {
                $('#exportMembersModal').modal('hide');
                notify.success('Download started...');
            }, 100);
        });

        // Reset modal on close
        $('#exportMembersModal').on('hidden.bs.modal', function() {
            $('#export_filter').val('all').trigger('change');
            $('#export_groups').val(null).trigger('change');
        });

    });
</script>

@endpush