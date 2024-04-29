@extends('admin_layouts.master')
@section('content')
@section('title', '- Questions')
<style>
    .course-alert{
        position: absolute;
        right: 0;
        top: 0;
    }
</style>
<div>
    <div class="lesson-page-content">
        <div class="course-alert">
            @if (session()->has('success'))
            <div class="alert alert-success" id="alertID">
                <a href="#" class="close" data-dismiss="alert"
                    aria-label="close"></a> {{ session('success') }}
            </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger" id="alertID">
                    <a href="#" class="close" data-dismiss="alert"
                        aria-label="close"></a> {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="lesson-wrapper">
            <div class="detail-head-arrea">
                <div>
                    <h1>Question Listing</h1>
                </div>
                <div class="d-flex">
                    <div class="dropdown">
                        <button type="button" class="btn btn-warning custom-btn" id="dropdownMenuButton12" data-bs-toggle="dropdown" aria-expanded="false">New Question</button>
                        <ul class="dropdown-menu header-menu p-2" aria-labelledby="dropdownMenuButton12">
                            <li class="lh-1"><a class="dropdown-item" href="{{route('Multiple.questions')}}">Multiple Choice Single<br> Answer</a></li>
                            <li class="lh-1"><a class="dropdown-item" href="{{route('drag.drop')}}">Drag & Drop</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-holder_vt">
                <table id="example" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">Question</th>
                        <th scope="col">Type</th>
                        <th scope="col">Quiz</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach($getQuestions as $key=>$question)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{strip_tags($question->quiz_query)}}</td>
                            <td>{{$question->quiz_type == "multiple_choice" ? 'Multiple Choice' : 'Drag & Drop'}}</td>
                            <td>{{$question->quizName}}</td>
                            <td class="{{$question->is_active == '1' ? 'success_vt' : 'pending_vt'}}">{{$question->is_active == '1' ? 'Active' : 'Inactive'}}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn header-btn-arrow dropdown-toggle p-0 table-action-icon" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="{{route('edit.question',['id'=>$question->id])}}"><i class="fontello icon-edit2 pr-10"></i>Edit</a></li>
                                        <li><a class="dropdown-item text-danger" href="{{route('delete.question',['id'=>$question->id])}}"><i class="fontello icon-trash-1 pr-10"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script type="text/javascript">   
       $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            // {
            //     extend: 'copyHtml5',
            //     exportOptions: {
            //         columns: [ 0, ':visible' ]
            //     }
            // },
            {
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
    } );
    setTimeout(function(){
        $('#alertID').hide('slow')
        }, 2000);
       
</script>
@endsection

