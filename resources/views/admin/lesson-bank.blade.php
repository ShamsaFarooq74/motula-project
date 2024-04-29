@extends('admin_layouts.master')
@section('content')
@section('title', '- Modules')
<style>
    .course-alert {
        position: absolute;
        right: 0;
        top: 0;
    }

    .note-editor.fullscreen {
        background-color: #f2f2f2;
    }
   .file-span {
     color: var(--bg-color);
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
        padding-bottom: 8px;
        
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
                <table id="example" class="table table-striped bg-white w-100">
                    <thead class="tableOfhead">
                        <tr>
                            <th class="col">Sr</th>
                            <th class="col">Module Title</th>
                            <th style="height:auto; width: 0px;">Image</th>
                            <th class="col">Status</th>
                            <th class="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($getLessons as $key => $lesson)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $lesson->company_name }}</td>
                                <td style="text-align: center;">
                                    @if ($lesson->image != null)
                                        <img class="smaller-logo"
                                            src="{{ $lesson->image != null ? asset('assets/company/' . $lesson->image) : '' }}">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="{{ $lesson->is_active == '1' ? 'success_vt' : 'pending_vt' }}">
                                    {{ $lesson->is_active == '1' ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                            type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        </button>
                                        <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="#"
                                                    onclick="editLessons({{ $lesson->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                            <li><a class="dropdown-item text-danger"
                                                    href="{{ route('delete.lesson', ['id' => $lesson->id]) }}"><i
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">New Module</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.lesson') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="basic-url" class="form-label">Module Title</label>
                            <div class="input-group mb-2">
                                <input type="text" name="feature" value="{{ old('feature') }}"
                                    class="form-control modal-input" id="" placeholder="Enter Module Title">
                            </div>
                            <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" value="" class="form-control modal-input">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Open Externally</label>
                                <label class="switch">
                                    <input type="checkbox" name="openExternally" id="openExternally">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div id="appPackage" style="display:none">
                                <label for="basic-url" class="form-label">App Package</label>
                                <div class="input-group mb-2">
                                    <input type="text" name="appPackage" value="{{ old('appPackage') }}"
                                        class="form-control modal-input" id="appPackage" placeholder="Enter App Package">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" name="status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="lessonEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog right-modal_vt">
            <div class="modal-content right-modal-content_vt">
                <div class="modal-header right-modal-head">
                    <h5 class="modal-title" id="exampleModalLabel">Update Module</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('update.feature') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="company_id" id="edit_company_id">
                            <label for="basic-url" class="form-label">Module Title</label>
                            <div class="input-group mb-2">
                                <input type="text" id="edit_company_name" name="feature"
                                    class="form-control modal-input" placeholder="Enter Module Title">
                            </div>
                            <label for="basic-url" class="form-label">Icon Image</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" id="myImage" value=""
                                    class="form-control modal-input">
                                <img id="imageElementId" src="" class="blog-inner-img">
                            </div>
                             <div class="d-flex justify-content-between align-items-center mb-2">
                                 <label for="basic-url" class="form-label mb-0">Open Externally</label>
                                 <label class="switch">
                                     <input type="checkbox" name="edit_open_externally" id="edit_open_externally">
                                     <span class="slider round"></span>
                                 </label>
                             </div>
                             <div id="app_package_div" style="display:none">
                                 <label for="basic-url" class="form-label">App Package</label>
                                 <div class="input-group mb-2">
                                     <input type="text" name="edit_app_package"
                                         value="{{ old('appPackage') }}"
                                         class="form-control modal-input" id="edit_app_package"
                                         placeholder="Enter App Package">
                                 </div>
                             </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="basic-url" class="form-label mb-0">Active Status</label>
                                <label class="switch">
                                    <input type="checkbox" id="edit_status" name="status">
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
<script type="text/javascript">
  
    var customButton = `
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">New Module</button>
                </div>
        `;
    var customhead = `
        <div style="margin-right: auto;" class="py-2">
            <h3>Modules</h3>
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
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(2))'
                }
            },
            {
                extend: 'print',
                text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(2))' 
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                exportOptions: {
                    columns: ':not(:last-child):not(:last-child-1):not(:eq(2))'
                }
            }
        ]
    });
    $('#example_filter').before(customhead);
    $('.dt-buttons').after(customButton);
    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 2000);
  var openExternally = document.getElementById('openExternally');
    var appPackage = document.getElementById('appPackage');
        openExternally.addEventListener('change', function() {
        if (this.checked) {
            appPackage.style.display = 'block';
        } else {
            appPackage.style.display = 'none';
        }
    });
  function handleCheckboxChange(statusValue) {
  var app_package_div = document.getElementById('app_package_div');
  if (statusValue == '1') {
    app_package_div.style.display = 'block';
    } else {
    app_package_div.style.display = 'none';
    }
  }
   document.getElementById('edit_open_externally').onchange = function() {
     handleCheckboxChange(this.checked ? '1' : '0');
    };

    function editLessons(id) {
        $.ajax({
            url: "{{ route('edit.lesson') }}",
            type: "POST",
            data: {
                company_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    var model = new bootstrap.Modal(document.getElementById("lessonEdit"));
                    model.show();
                    document.getElementById('edit_company_id').value = response.data.id;
                    document.getElementById('edit_company_name').value = response.data.company_name;
                    document.getElementById('edit_app_package').value = response.data.app_package;
                    var imageSrc = data.image;
                    var imageElement = document.getElementById("imageElementId");
                    if (imageSrc) {
                        imageElement.src = 'assets/company/' + imageSrc;
                    } else {
                        imageElement.src = 'assets/company/motula.jfif';
                    }
                   
                    document.getElementById('edit_status').checked = response.data.is_active == '1' ? true : false;
                   
                   document.getElementById('edit_open_externally').checked = response.data.open_externally == '1' ? true :  false;
                   handleCheckboxChange(response.data.open_externally);
                    // document.getElementById('edit_open_externally').checked = response.data.open_externally == '1' ? true :  false;
                    // handleCheckboxChange(response.data.open_externally);
                //   if( response.data.open_externally == '1' ){
                //        app_packages.style.display = 'block';
                //   }else{
                //         app_packages.style.display = 'none';
                //   }
         
                }
            }
        })
    }


  
</script>
@endsection
