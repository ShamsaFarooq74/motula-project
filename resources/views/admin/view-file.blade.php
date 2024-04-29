@extends('admin_layouts.master')
@section('content')
@section('title', '- File')
<style>
    .file_card_vt {
        float: left;
        margin-right: 1%;
        position: relative;
        border: 0.5px solid #DADADA;
        /* margin-bottom: 20px; */
        margin-top: 30px;
        border-radius: 5px;
        /* overflow: hidden; */
        background: #f9f9f9;
    }

    .file_card_head_vt {
        float: left;
        width: 100%;
        padding: 10px 10px;
        margin-bottom: 0px;
        display: flex;
        align-items: center;
    }

    .title_name_vt .file_card_title {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: black;
        font-size: 13px;
    }

    .drop_down_vt {
        position: absolute;
        display: inline-block;
        top: 15px;
        right: 15px;
    }

    .vertical-btn {
        transform: rotate(90deg);
    }

    .thumbnail {
        width: 50px;
        height: auto;
        margin: 5px;
    }

    .cate {
        font-family: 'League Spartan';
        font-weight: 400;
        font-size: 20px;
        line-height: 18px;
        color: #C7C7C7;
    }

    .sub_cate {
        color: #57585A;
        font-family: 'League Spartan', sans-serif;
        font-size: 20px;
        font-weight: 400;
        line-height: 18px;
        letter-spacing: 0em;
        text-align: left;
    }

    .hed {
        Weight: 500;
    }
    .navbar_form_input {
  padding-right: 3px !important;
  padding-left: 3px !important;
  width: 260px !important;
  margin-bottom: 0px !important;
}
.form_div{
    display:flex; 
    justify-content:end; 
    align-items:center;
}
.filter_form{
    display: flex;
}
/* @media screen and (max-width: 1320px) {
    .detail-head-arrea{
        flex-direction: column;
    }
} */
@media screen and (min-width:720px) and (max-width: 1319px) {
    .detail-head-arrea{
        flex-direction: column;
    }
    .form_div{
        flex-direction: column;
        gap: 10px;
    }
}
@media screen and (min-width:267px) and (max-width: 719px) {
    .detail-head-arrea{
        flex-direction: column;
    }
    .form_div{
        flex-direction: column;
        gap: 10px;
    }
    .filter_form{
        flex-direction: column;
        gap:10px;
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
            <div class="detail-head-arrea">
                <div class="col-md-4">
                    <h1 style="Weight:500">All Files</h1>
                    <h1 class="cate">
                        <span class="hed">
                            {{ $getCatName->category_name ?? '' }}
                        </span>
                        @if (!empty($subCatName->sub_category_name))
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                            height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M192 128l128 128-128 128z"></path>
                        </svg>
                        <span class="hed">
                            {{ $subCatName->sub_category_name ?? '' }}
                        </span>
                        @endif
                        @if (!empty($sub_cat_child->child_name))
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                            height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M192 128l128 128-128 128z"></path>
                        </svg>
                        <span class="hed">
                            {{ $sub_cat_child->child_name ?? '' }}
                        </span>
                        @endif
                        @if (!empty($sub_cat_sub_child->sub_child_name))
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                            height="12px" width="12px" xmlns="http://www.w3.org/2000/svg">
                            <path d="M192 128l128 128-128 128z"></path>
                        </svg>
                        <span class="hed" style="color: #ff0000;">
                            {{ $sub_cat_sub_child->sub_child_name ?? '' }}
                        </span>
                        @endif
                    </h1>
                </div>
                <div class="col-md-8 form_div">
                    <form class="filter_form" method="GET" id="myFormType"
                                action="{{ route('view.file', ['category' => $getCatName->id, 'sub_category' => optional($subCatName)->id, 'child' => isset($sub_cat_child->id) ? $sub_cat_child->id :'0', 'sub_child' => isset($sub_cat_sub_child->id) ? $sub_cat_sub_child->id : '0']) }}">
                        <div class="navbar_form_input">
                                <div class="form-group position-relative caret-holder px-1">
                                    <select class="selectpicker w-100" data-live-search="true" aria-label=""
                                        name="file_type_id" id="file_type_id">
                                        <option value="all">Select File Type</option>
                                        @forelse ($file_type as $item)
                                            <option value="{{ $item->id }}" 
                                                {{ $selectedFileType  == $item->id ? 'selected' : '' }}>
                                                {{ ucwords($item->file_type) }}</option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                </div>
                        </div>
                        <div class="navbar_form_input">
                                <div class="form-group position-relative caret-holder px-1">
                                    <select class="selectpicker w-100" data-live-search="true" aria-label=""
                                        name="region_id" id="region_id">
                                        <option value="all">All</option>
                                        @forelse ($region as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $selectedRegion == $item->id ? 'selected' : '' }}>
                                                {{ ucwords($item->region_name) }}</option>
                                        @empty
                                            <option>No Record Found</option>
                                        @endforelse
                                    </select>
                                </div>
                        </div>
                    </form>
                    {{-- <div class="d-flex"> --}}
                        <button type="button" class="btn btn-warning custom-btn" data-bs-toggle="modal"
                            data-bs-target="#quizModal">New File</button>
                    {{-- </div> --}}
                </div>
            </div>
            <div class="table-holder_vt">
                @if (isset($files))
                    @foreach ($files as $content)
                    <div class="file_card_vt px-0 width_vt pointer">
                        <div class="file_card_head_vt">
                            <div class="title_name_vt ml-0 font-weight-bolder text-center"
                                id="dev_name_{{ $content->id }}" style="width:100px">
                                <a href="{{ asset('assets/subcategory/' . $content->file_path ) }}" target="_blank">
                                    <div class="content_image text-center mb-1">
                                        <img src="{{ asset('assets/images/files.png') }}" loading="lazy"
                                            alt="Content Image">
                                    </div>
                                    <div>
                                        <span class="file_card_title">
                                            {{ $content->file_path }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="">
                                <div class="drop_down_vt panda_vt" style="right:10px !important;">
                                    <button
                                        class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon vertical-btn"
                                        type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu header-menu p-0" aria-labelledby="dropdownMenuButton1">
                                      <li><a class="dropdown-item" href="#"><i class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                    <li><a class="dropdown-item text-danger"  href="{{ route('delete.file', ['id' => $content->id]) }}"><i class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div style="margin: 25px auto; text-align: center;">
                    <img src="{{ asset('assets/images/nofile.png') }}" class="img-fluid"
                        style="display: block; margin: 0 auto;" />
                </div>
                @endif
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
                            <input type="hidden" name="category_id" value="{{ $getCatName->id ?? '' }}"
                                id="category_id">
                            <input type="hidden" name="sub_category_id" value="{{ $subCatName->id ?? '' }}"
                                id="sub_category_id">
                            <input type="hidden" name="child_id" value="{{ $sub_cat_child->id ?? '' }}"
                                id="child_id">
                            <input type="hidden" name="sub_child_id" value="{{ $sub_cat_sub_child->id ?? '' }}"
                                id="sub_child_id">
                            <label for="basic-url" class="form-label">File Type</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label=""
                                    name="file_type_id">
                                    <option selected disabled>Select File Type</option>
                                    @foreach ($file_type as $item)
                                        <option value="{{ $item->id }}">{{ $item->file_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Region</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label=""
                                    name="region_id[]" multiple="multiple">
                                    <option disabled>Select Region</option>
                                    @foreach ($region as $item)
                                        <option value="{{ $item->id }}">{{ $item->region_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Files <span style="color: #ff0000;">(jpg, mp4,
                                    png, pdf, max: 250mb)</span></label>
                            <div class="mb-2 input-group">
                                <input type="file" name="image[]" id="myImage" class="form-control modal-input"
                                    multiple accept=".jpg, .mp4, .png, .pdf">
                                <div id="loader-container"></div>
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
</div>
<script type="text/javascript">
    document.getElementById('myImage').addEventListener('change', function(event) {
        const fileInput = event.target;
        const loaderContainer = document.getElementById('loader-container');
        loaderContainer.innerHTML = '';

        const selectedFiles = fileInput.files;
        const selectedImagesContainer = document.getElementById("selectedImages");
        selectedImagesContainer.innerHTML = "";

        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];

            const isImage = file.type.startsWith("image/");
            const isPDF = file.type === "application/pdf";

            if (isImage || isPDF) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    if (isImage) {
                        const imageElement = document.createElement("img");
                        imageElement.classList.add("thumbnail");
                        imageElement.src = e.target.result;
                        selectedImagesContainer.appendChild(imageElement);
                    } else if (isPDF) {
                        const pdfMessage = document.createElement("p");
                        pdfMessage.textContent = "PDF file: " + file.name;
                        selectedImagesContainer.appendChild(pdfMessage);
                    }
                    const progressBar = document.createElement("div");
                    progressBar.classList.add("progress-bar");
                    selectedImagesContainer.appendChild(progressBar);

                    const xhr = new XMLHttpRequest();
                    const formData = new FormData();
                    formData.append('file', file);

                    xhr.upload.addEventListener("progress", function(event) {
                        if (event.lengthComputable) {
                            const percentage = (event.loaded / event.total) * 100;
                            progressBar.style.width = percentage + "%";
                            progressBar.textContent = Math.round(percentage) + "%";
                        }
                    });

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            selectedImagesContainer.removeChild(progressBar);
                        }
                    };

                    xhr.open('POST', 'your-upload-endpoint', true);
                    xhr.send(formData);
                };

                if (isPDF) {
                    reader.readAsDataURL(file);
                } else if (isImage) {
                    reader.readAsDataURL(file);
                }
            }
        }
    });
    $('#file_type_id').change(function() {
        $('#myFormType').submit();
    });
    $('#region_id').change(function() {
        $('#myFormType').submit();
    });
    setTimeout(function() {
        $('#alertID').hide('slow')
    }, 2000);
</script>
@endsection
