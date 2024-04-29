@extends('web.layouts.master')
@section('content')
        <!-- Start Page Content here -->
        <div class="content">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="banner_sub_vt">
                        <div class="banner_sub_home">
                            <h2>Feedback</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Cate itme ?-->
            <div class="container mt-5 mb-3 mint_hight">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="feedback_vt">
                            <h3>Give Your Detail Feedback</h3>
                            <div class="form-group">
                                <textarea class="form-control" id="feedback" name = "feedback" placeholder="Add feedback.."
                                    id="exampleFormControlTextarea1" rows="8"></textarea>
                            </div><button type="submit" id="submit">Send Feedback</button>

                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">
            $("#submit").on('click',function(){
                var feedback = $("#feedback").val();
                console.log(feedback);
                $.ajax({
                    // url:"{{ route('send.feedback') }}",
                    url:"/sendfeedback",

                    method:"POST",
                    data:{
                        feedback: feedback,
                         _token: '{{ csrf_token() }}',
                    },
                    success:function(response){
                        if(response.message){
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            });
                            $("#feedback").val("");

                        }
                        else{
                            Swal.fire({
                                icon: 'error',
                                title: response.error,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            });

                        }
                    }
                })

            });
            // $("#submit").click(function(){
            //     var feedback = $("#feedback").val();
            //     $.ajax({
            //         url:"/sendfeedback",
            //         method:"POST",
            //         data:{
            //             feedback: feedback,
            //             _token: '{{ csrf_token() }}',

            //         },
            //         success:function(response){
            //             if(response.message){
            //                 alert(response.message);
            //             }
            //         }
            //     })

            // });

            </script>
@endsection
