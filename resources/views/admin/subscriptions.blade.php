@extends('admin_layouts.master')
@section('content')
@section('title', '- Subscription')
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
                    <h1>User Subscriptions</h1>
                </div>
                <div class="d-flex">
                </div>
            </div>
            <div class="table-holder_vt">
                <table id="Subscription" class="table table-striped bg-white pt-3 w-100">
                    <thead class="tableOfhead">
                        <tr>
                        <th scope="col">Sr</th>
                        <th scope="col">Course Plane</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Purchased Date</th>
                        <th scope="col">Card Number</th>
                        </tr>
                    </thead>
                    <tbody class="tableOfContent">
                        @foreach($users as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->course_title}}</td>
                            <td>{{$item->username}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->account_number}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $('#Subscription').DataTable( {
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
</script>
</div>
@endsection
