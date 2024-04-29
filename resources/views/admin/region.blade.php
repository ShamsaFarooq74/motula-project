@extends('admin_layouts.master')
@section('content')
@section('title', '- Region')
<style>
    .course-alert {
        position: absolute;
        right: 0;
        top: 0;
    }
    .smaller-logo {
        width: 50px; 
        height: auto;
    }
    .thumbnail {
        width: 50px; /* Adjust the width as needed */
        height: auto;
        margin: 5px;
    }
    #example_filter {
        padding-right: 5px;
    }

    .d-flex {
        padding-right: 0px;
        padding-left: 5px;
    }
    .dt-button {
        margin-top: 1px;
    }
    .dt-button {
        margin-top: 1px;
    }
    @media screen and (min-width: 360px) and (max-width: 480px) {
        .custom-btn {
            width: 100px;
        }
        .d-flex{
            flex-flow: wrap;
        }
    }
</style>
<div>
    <div class="lesson-page-content">
        <div class="course-alert">
            @if (session()->has('success'))
                <div class="alert alert-success" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger" id="alertID">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="lesson-wrapper">
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                            <th scope="col">Sr</th>
                            <th scope="col">Region</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($region as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->region_name }}
                                    </td>
                                    <td class="{{ $item->is_active == '1' ? 'success_vt' : 'pending_vt' }}">
                                        {{ $item->is_active == '1' ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="editRegion({{ $item->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger"
                                                        href="{{ route('delete.region', ['id' => $item->id]) }}"><i
                                                        class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
            </div>
        </div>
    </div>
    <div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">New Region</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.region') }}" method="POST">
                            @csrf
                            <label for="basic-url" class="form-label">Region Name</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control modal-input" name="region_name"
                                    id="region_name" placeholder="Enter Region Title">
                            </div>
                            <label for="basic-url" class="form-label">Sort Name</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control modal-input" name="sortname"
                                    id="sortname" placeholder="Enter Short Title">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status" id="status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add Region</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="EditfileModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">Update Region</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.region') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="edit_resion_id" id="edit_region_id">
                            <label for="basic-url" class="form-label">Region Name</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control modal-input" name="region_name"
                                    id="editregion_name" placeholder="Enter Region Title">
                            </div>
                            <label for="basic-url" class="form-label">Sort Name</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control modal-input" name="sortname"
                                    id="editsortname" placeholder="Enter Short Title">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status" id="edit_status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
     var customButton = `
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">New Region</button>
        `;
    var customhead = `
        <div style="margin-right: auto;">
                    <h3>Region Listing</h3>
        </div>
        `;
    var customCss = `
            <style>
                .dt-buttons .buttons-pdf,
                .dt-buttons .buttons-csv,
                .dt-buttons .buttons-print {
                    background-color: white !important;
                    color: black !important;
                }
            </style>
        `;

    $('head').append(customCss);
    $('#example').DataTable({
        dom: '<"d-flex justify-content-end"fB>rtip',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        },
        buttons: [{
                extend: 'csvHtml5',
                text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
            {
                extend: 'print',
                text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1)'
                }
            },
        ]
    });
    $('#example_filter').before(customhead);
    $('.dt-buttons').after(customButton);
    //   $('#example').DataTable({
    //         dom: 'Bfrtip',
    //         buttons: [{
    //                 extend: 'excelHtml5',
    //                 exportOptions: {
    //                     columns: 'th:not(:last-child)'
    //                 }
    //             },
    //             {
    //                 extend: 'pdfHtml5',
    //                 exportOptions: {
    //                     columns: 'th:not(:last-child)'
    //                 }
    //             },
    //             {
    //                 extend: 'csvHtml5',
    //                 exportOptions: {
    //                     columns: 'th:not(:last-child)'
    //                 }
    //             },
    //             'colvis'
    //         ]
    //     });
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);

        function editRegion(id) {
            $.ajax({
                url: "{{ route('edit.region') }}",
                type: "POST",
                data: {
                    region_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        var model = new bootstrap.Modal(document.getElementById("EditfileModal"));
                        model.show();
                        document.getElementById('edit_region_id').value = data.id;
                        document.getElementById('editregion_name').value = data.region_name;
                        document.getElementById('editsortname').value = data.sortname;
                        document.getElementById('edit_status').checked = data.is_active == '1' ? true : false;
                    }
                }
            })
        }
</script>
@endsection
