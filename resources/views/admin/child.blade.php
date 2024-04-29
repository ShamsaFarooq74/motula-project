@extends('admin_layouts.master')
@section('content')
@section('title', '- Product Type')
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

    @media screen and (min-width: 360px) and (max-width: 480px) {
        .custom-btn {
            width: 100px;
        }

        .d-flex {
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
                            <th scope="col">Pillar Family </th>
                            <th scope="col">Pillars</th>
                            <th scope="col">Product Type</th>
                            <th scope="col" style="text-align: center">Priority</th>
                            <th style="height:auto; width: 0px;">Image</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($sub_cat_child as $key => $quiz)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $quiz->subCategoryName }}</td>
                                <td>{{ $quiz->getCategoryName ?? '' }}</td>
                                <td>{{ $quiz->child_name ?? '' }}</td>
                                <td style="text-align: center">{{ $quiz->priority }}</td>
                                <td style="text-align: center;">
                                    @if ($quiz->image != null)
                                        <img class="smaller-logo"
                                            src="{{ $quiz->image != null ? asset('assets/subcategory/' . $quiz->image) : '' }}">
                                    @else
                                        <img class="smaller-logo" src="{{ asset('assets/images/default-user.png') }}">
                                    @endif
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
                                                    onclick="editChild({{ $quiz->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('delete.child', ['id' => $quiz->id]) }}"><i
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
    <div class="modal fade" id="EditquizModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">Update Child</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('update.subcat.child') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="child_id" id="edit_child_id">
                            <label for="basic-url" class="form-label">Pillars</label>
                            <div class="mb-2 input-group">
                                <select class="form-select modal-select mb-2" aria-label="" style="width:100%;"
                                    name="category_id" id="edit_category_id" onchange="myFunction1()">
                                    <option selected disabled>Select Pillars</option>
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Pillar Faimly</label>
                            <div class="mb-2 input-group">
                                <select class="form-select modal-select mb-2" aria-label="" style="width:100%;"
                                    name="sub_category_id" id="edit_sub_category_id">
                                    <option selected disabled>Select Sub Category</option>
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Product Type</label>
                            <div class="input-group mb-2">
                                <input type="text" name="child_name" value="{{ old('child_name') }}"
                                    class="form-control modal-input" id="childInput" placeholder="Enter Product Type Name"
                                    required min="1">
                            </div>
                            <label for="basic-url" class="form-label">Priority</label>
                            <div class="input-group mb-2">
                                <input type="number" name="priority" value="{{ old('priority') }}"
                                    class="form-control modal-input" id="priorityInput"
                                    placeholder="Enter Category Priority" required min="1">
                            </div>
                            <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" id="myImage" value=""
                                    class="form-control modal-input">
                                <img id="imageElementId" src="" class="blog-inner-img">
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
                    <h5 class="modal-title" id="exampleModalLabel">New Product Type</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.subcat.child') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <label for="basic-url" class="form-label">Pillars</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label=""
                                    id="category_id" name="category_id" onchange="myFunction()">
                                    <option selected disabled>Select Pillars</option>
                                    @foreach ($getCategories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == old('category_id') ? 'selected' : '' }}>
                                            {{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Pillar Family </label>
                            <div>
                                <select id="sub_category_id" name="sub_category_id" class="form-control"
                                    style="appearance: none;">
                                    <option selected disabled>Select Pillar Family</option>
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Sub Pillar</label>
                            <div class="input-group mb-2">
                                <input type="text" name="child_name" class="form-control modal-input"
                                    id="child" placeholder="Enter Sub Pillar Title">
                            </div>
                            <label for="basic-url" class="form-label">Priority</label>
                            <div class="input-group mb-2">
                                <input type="number" name="priority" class="form-control modal-input"
                                    id="edit_price" placeholder="Enter Bucket Priority">
                            </div>
                             <label for="basic-url" class="form-label">Region</label>
                             <div>
                                 <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label=""
                                     name="region_id[]" multiple="multiple">
                                     <option disabled>Select Region</option>
                                     @foreach($region as $item)
                                         <option value="{{ $item->id }}">{{ $item->region_name }}</option>
                                     @endforeach
                                 </select>
                             </div>
                            <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" id="myImage"
                                    class="form-control modal-input">
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
                                    data-bs-target="#exampleModal">Add Child</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {});
        var customButton = `
                <div class="d-flex">
                    <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">New Product Type</button>
                </div>
        `;
        var customhead = `
        <div style="margin-right: auto;">
                    <h3>Product Type</h3>
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

        function editChild(id) {
            $.ajax({
                url: "{{ route('edit.sub_cat.child') }}",
                type: "POST",
                data: {
                    child_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        var category = response.category;
                        var sub_category = response.sub_category;
                        var model = new bootstrap.Modal(document.getElementById("EditquizModal"));
                        model.show();
                        document.getElementById('edit_child_id').value = response.data.id;
                        document.getElementById('childInput').value = response.data.child_name;
                        document.getElementById('priorityInput').value = response.data.priority;
                        $('#edit_category_id').select2({
                            dropdownParent: $('#EditquizModal')
                        });
                        $('#edit_category_id').html('')
                        html = '<option selected disabled>Select Pillars</option>';
                        for (i = 0; i < category.length; ++i) {
                            var selected = (data.category_id == category[i].id) ? 'selected' : '';
                            html +=
                                `<option  ${selected} value="${category[i].id}">${category[i].category_name}</option>`;
                        }
                        $('#edit_category_id').append(html)
                        $('#edit_sub_category_id').select2({
                            dropdownParent: $('#EditquizModal')
                        });
                        $('#edit_sub_category_id').html('')
                        html = '<option selected disabled>Select Sub Pillars</option>';
                        for (i = 0; i < sub_category.length; ++i) {
                            var selected = (data.sub_category_id == sub_category[i].id) ? 'selected' : '';
                            html +=
                                `<option  ${selected} value="${sub_category[i].id}">${sub_category[i].sub_category_name}</option>`;
                        }
                        $('#edit_sub_category_id').append(html)
                        var imageSrc = data.image;
                        var imageElement = document.getElementById("imageElementId");
                        if (imageSrc) {
                            imageElement.src = 'assets/subcategory/' + imageSrc;
                        } else {
                            imageElement.src = 'assets/company/motula.jfif';
                        }
                        document.getElementById('edit_status').checked = response.data.is_active == '1' ? true :
                            false;
                    }
                }
            })
        }
        function myFunction() {
            var e = document.getElementById("category_id");
            var value = e.value;
            var text = e.options[e.selectedIndex].value;
            $.ajax({
                url: "{{ route('get.sub_categories') }}",
                type: 'GET',
                data: {
                    'category_id': text,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response['success'] == true) {
                        var result = response['data'];
                        $('#sub_category_id').select2({
                            dropdownParent: $('#quizModal')
                        });
                        $('#sub_category_id').html('')
                        html = '<option selected disabled>Select Sub Pillars</option>';
                        for (i = 0; i < result.length; ++i) {
                            html += `<option id="sub_category_option" value="${result[i].id}">${result[i].sub_category_name}</option>`;
                        }
                        $('#sub_category_id').append(html)

                    }
                }
            });
        }
    

        function myFunction1() {
            var e = document.getElementById("edit_category_id");
            var value = e.value;
            var text = e.options[e.selectedIndex].value;
            $.ajax({
                url: "{{ route('get.sub_categories') }}",
                type: 'GET',
                data: {
                    'category_id': text,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response['success'] == true) {
                        var result = response['data'];
                        console.log(result)
                        $('#edit_sub_category_id').select2({
                            dropdownParent: $('#EditquizModal')
                        });
                        $('#edit_sub_category_id').html('')
                        html = '<option selected disabled>Select Sub Category</option>';
                        for (i = 0; i < result.length; ++i) {
                            html += `<option value="${result[i].id}">${result[i].sub_category_name}</option>`;
                        }
                        $('#edit_sub_category_id').append(html)

                    }
                }
            });

        }
    </script>
@endsection
