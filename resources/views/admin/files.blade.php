@extends('admin_layouts.master')
@section('content')
@section('title', '- Files')
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
                            <th scope="col">Category</th>
                            <th scope="col">Sub Category Title</th>
                            <th scope="col">Child</th>
                            <th scope="col">Sub Child</th>
                            <th scope="col" style="text-align: center">Files</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach ($subcategory as $key => $sub_child)
                            @if($sub_child->child_id || $sub_child->sub_child_id)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $sub_child->category_name ?? 'N\A' }}</td>
                                <td>{{ $sub_child->sub_category_name ?? 'N\A' }}</td>
                                <td>{{ $sub_child->child_name ?? 'N\A' }}</td>
                                <td>{{ $sub_child->sub_child_name ?? 'N\A' }}</td>
                                <td style="text-align: center;">
                                    {{ $sub_child->fileCount ?? 'N/A' }}
                                    <img class="smaller-icon" src="{{ asset('assets/images/view.png') }}">
                                    <a href="{{ route('view.file', [ 'category' => $sub_child->category_id, 'sub_category' => $sub_child->sub_category_id, 'child' => $sub_child->child_id !=  null ?  $sub_child->child_id : '0' ,'sub_child' => $sub_child->sub_child_id != null ? $sub_child->sub_child_id : '0' ]) }}"
                                        style="margin-left: 5px;color:#EE1D23">View</a>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var customhead = `
        <div style="margin-right: auto;">
                    <h3>Files</h3>
        </div>
        `;
        $('#example').DataTable({
            dom: '<"d-flex justify-content-end"f>rtip',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
        });
        $('#example_filter').before(customhead);
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
    </script>
@endsection
