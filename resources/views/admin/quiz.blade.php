@extends('admin_layouts.master')
@section('content')
@section('title', '- File')
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
            <div class="detail-head-arrea">
                <div>
                    <h1>File Listing</h1>
                </div>
                <div class="d-flex">
                    <div>
                        <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">New File</button>
                    </div>
                </div>
            </div>
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                            <th scope="col">Sr</th>
                            <th scope="col">Sub Category</th>
                            <th scope="col">Country</th>
                            <th scope="col">Files</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @php
                        $displayedSubCategoryIds = [];
                        @endphp
                        
                        @foreach ($getfiles as $key => $item)
                            @if (!in_array($item->sub_category_id, $displayedSubCategoryIds))
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $item->subCatName->sub_category_name ?? '' }}
                                    </td>
                                    <td>{{ $item->countryName->country_name ?? '' }}</td>
                                    <td style="text-align: center;">
                                        <i class="fontello icon-document"></i> <!-- Add a file icon (modify the class as needed) -->
                                        {{ isset($counts[$item->sub_category_id]) ? $counts[$item->sub_category_id] : 'N/A' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="editQuiz({{ $item->id }})"><i
                                                        class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                                <li><a class="dropdown-item text-danger"
                                                        href="{{ route('delete.file', ['id' => $item->id]) }}"><i
                                                        class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                $displayedSubCategoryIds[] = $item->sub_category_id;
                                @endphp
                            @endif
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
                    <h5 class="modal-title" id="exampleModalLabel">New Files</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.files') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="basic-url" class="form-label">Sub Category</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label="" name="subcategory_id">
                                    <option selected disabled>Select Sub Category</option>
                                    @foreach ($getSubCategory as $item)
                                    <option value="{{$item->id}}">{{ $item->sub_category_name }}</option>      
                                    @endforeach     
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Country</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label="" name="country_id">
                                    <option selected disabled>Select Country</option>  
                                    @foreach ($country as $item)
                                    <option value="{{$item->country_id}}">{{ $item->country_name }}</option>      
                                    @endforeach 
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Files</label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image[]" id="myImage" class="form-control modal-input" multiple accept="*">
                            </div>
                            <div id="selectedImages"></div>
                            <div>
                                <button type="submit" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add Files</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Update File</h5>
                    <div class="close_vt" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fontello icon-cancel text-white"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="right-modal-content">
                        <form action="{{ route('save.files') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="edit_file_id" id="edit_file_id">
                            <label for="basic-url" class="form-label">Sub Category</label>
                            <div class="mb-2 input-group">
                                <select class="form-select modal-select mb-2" aria-label="" style="width:100%;"
                                    name="subcategory_id" id="SelectSubCategory">
                                    <option selected disabled>Select Sub Category</option>
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Region</label>
                            <div class="mb-2 input-group">
                                <select class="form-select modal-select mb-2" aria-label="" style="width:100%;"
                                    name="region_id" id="SelectCountry">
                                    <option selected disabled>Select Region</option>
                                </select>
                            </div>
                            <div class="mb-2 input-group">
                                <input type="file" name="image" id="myImage" value="" class="form-control modal-input">
                                <img id="imageElementId" src="" class="blog-inner-img" >
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
    <script type="text/javascript">
    document.getElementById("myImage").addEventListener("change", function(event) {
        const input = event.target;
        const selectedFiles = input.files;

        const selectedImagesContainer = document.getElementById("selectedImages");
        selectedImagesContainer.innerHTML = "";

        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageElement = document.createElement("img");
                    imageElement.classList.add("thumbnail");
                    imageElement.src = e.target.result;
                    selectedImagesContainer.appendChild(imageElement);
                };

                reader.readAsDataURL(file);
            }
        }
    });
        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                'colvis'
            ]
        });
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);

        function editQuiz(id) {
            $.ajax({
                url: "{{ route('edit.file') }}",
                type: "POST",
                data: {
                    file_id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        var subcategory = response.subCat;
                        var country = response.country;
                        console.log(data)
                        console.log(subcategory)
                        console.log(country)
                        var model = new bootstrap.Modal(document.getElementById("EditfileModal"));
                        model.show();
                        document.getElementById('edit_file_id').value = response.data.id;
                        $('#SelectSubCategory').select2({
                            dropdownParent: $('#EditfileModal')
                        });
                        $('#SelectSubCategory').html('')
                        html = '<option selected disabled>Select Sub Category</option>';
                        for (i = 0; i < subcategory.length; ++i) {
                            var selected = (data.sub_category_id == subcategory[i].id) ? 'selected' : '';
                            html +=
                                `<option  ${selected} value="${subcategory[i].id}">${subcategory[i].sub_category_name}</option>`;
                        }
                        $('#SelectSubCategory').append(html)
                        $('#SelectCountry').select2({
                            dropdownParent: $('#EditfileModal')
                        });
                        $('#SelectCountry').html('')
                        html = '<option selected disabled>Select Country</option>';
                        for (i = 0; i < country.length; ++i) {
                            var selected = (data.country_id == country[i].country_id) ? 'selected' : '';
                            html +=
                                `<option  ${selected} value="${country[i].country_id}">${country[i].country_name}</option>`;
                        }
                        $('#SelectCountry').append(html)
                        var imageSrc = data.files;
                        var imageElement = document.getElementById("imageElementId");
                        if (imageSrc) {
                            imageElement.src = 'assets/subcategory/' + imageSrc;
                        } else {
                            imageElement.src = 'assets/company/motula.jfif';
                        }
                    }
                }
            })
        }
    </script>

</div>
@endsection
