@extends('admin_layouts.master')
@section('content')
@section('title', '- File Type')
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
    #example_filter{
        padding-right: 5px;
    }
    .d-flex{
        padding-right: 0px;
        padding-left: 5px;
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
                            <th scope="col">File Title</th>
                            <th style="height:auto; width: 0px;">Image</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($file_type as $key => $quiz)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $quiz->file_type }}</td>
                                <td style="text-align: center;">
                                    @if ($quiz->image != null)
                                    <img class="smaller-logo" src="{{ $quiz->image != null ? asset('assets/subcategory/' . $quiz->image) : '' }}">
                                    @else 
                                    <img class="smaller-logo" src="{{ asset('assets/images/default-user.png') }}">
                                    @endif
                                </td>
                                <td>
                                    {{ $quiz->priority }}
                                </td>
                                <td class="{{ $quiz->is_active == '1' ? 'success_vt' : 'pending_vt' }}">
                                    {{ $quiz->is_active == '1' ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                            type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="editFileType({{ $quiz->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('delete.file.type', ['id' => $quiz->id]) }}"><i
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
    <div class="modal fade" id="EditquizModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog right-modal_vt">
        <div class="modal-content right-modal-content_vt">
            <div class="modal-header right-modal-head">
                <h5 class="modal-title" id="exampleModalLabel">Update File Type</h5>
                <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fontello icon-cancel text-white"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="right-modal-content">
                    <form action="{{route('update.type')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="file_type_id" id="edit_file_type_id">
                        <label for="basic-url" class="form-label">File Type</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control modal-input" name="file_type"
                                id="edit_file_type" placeholder="Enter File Type">
                        </div>
                        <label for="basic-url" class="form-label">Priority</label>
                        <div class="input-group mb-2">
                            <input type="number" class="form-control modal-input" name="priority"
                                id="edit_priority" placeholder="Enter Priority">
                        </div>
                        <label for="basic-url" class="form-label">Icon Image</label>
                        <div class="mb-2 input-group">
                            <input type="file" name="image" id="myImage" value="" class="form-control modal-input">
                            <img id="imageElementId" src="" class="blog-inner-img" >
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="basic-url" class="form-label mb-0">Active Status</label>
                            <label class="switch">
                                <input type="checkbox" name="status" id="edit_status">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
    <div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">New File Type</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{route('save.type')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="basic-url" class="form-label">File Type</label>
                            <div class="input-group mb-2">
                                <input type="text" name="file_type" value="{{ old('file_type') }}"
                                    class="form-control modal-input" id="" placeholder="Enter File Type">
                            </div>
                            <label for="basic-url" class="form-label">Priority</label>
                            <div class="input-group mb-2">
                                <input type="number" name="priority" value="{{ old('priority') }}"
                                    class="form-control modal-input" id="" placeholder="Enter Priority">
                            </div>
                            <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" id="myImage" class="form-control modal-input">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            {{-- <div class="mb-4">
                                <label class="form-label" for="textarea">Description</label>
                                <textarea id="summernote" class="summernote" name="description" placeholder="Enter Description"></textarea>
                            </div> --}}
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add File</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
              var customButton = `
                <div class="d-flex">
                    <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">New File</button>
                </div>
        `;
        var customhead = `
        <div style="margin-right: auto;">
                    <h3>File Type</h3>
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
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                    exportOptions: {
                        columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                    }
                },
                {
                    extend: 'print',
                    text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                    exportOptions: {
                        columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                    exportOptions: {
                        columns: ':not(:last-child):not(:last-child-1):not(:eq(3))'
                    }
                }
            ]
        });
        $('#example_filter').before(customhead);
        $('.dt-buttons').after(customButton);
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);

        function editFileType(id) {
            $.ajax({
                url: "{{ route('edit.file.type') }}",
                type: "POST",
                data: {
                    file_type_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        var model = new bootstrap.Modal(document.getElementById("EditquizModal"));
                        model.show();
                        document.getElementById('edit_file_type_id').value = response.data.id;
                        document.getElementById('edit_file_type').value = response.data.file_type;
                        document.getElementById('edit_priority').value = response.data.priority;
                        var imageSrc = data.image;
                        var imageElement = document.getElementById("imageElementId");
                        if (imageSrc) {
                            imageElement.src = 'assets/subcategory/' + imageSrc;
                        } else {
                            imageElement.src = 'assets/company/motula.jfif';
                        }
                        document.getElementById('edit_status').checked = response.data.is_active == '1' ? true : false;
                    }
                }
            })
        }
    </script>
@endsection
