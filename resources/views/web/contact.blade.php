@extends('web.layouts.master')
@section('content')
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="banner_sub_vt">
                <div class="banner_sub_home">
                    <h2>Contact Us</h2>
                </div>
            </div>
        </div>
</div>
    <!-- end page title -->

    <!-- Start How Cartify Works ?-->
    <div class="container py-3 p_p_bg mint_hight">
        <div class="row">
        <div class="col-md-3"></div>
            <div class="col-md-6 pt-5">
                <div class="form-group mb-3">
                    <input type="email" id="example-email" name="example-email" class="form-control" placeholder="Your Email address">
                </div>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="example-textarea" rows="3" placeholder="A quick message"></textarea>
                </div>
                <div class="form-group feedback_vt">
                    <button type="button" id="submiitt">Send </button>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>


</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $("#submiitt").click(function() {
        var email = $("#example-email").val();
        var text = $("#example-textarea").val();


        console.log(email, text);
        if (text === '') {
            Swal.fire({
                icon: 'error',
                title: 'Please Enter The Contact Details',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        } else if (email === '') {
            Swal.fire({
                icon: 'error',
                title: 'Please Enter The Contact Details',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        } else {


            $.ajax({
                url: "{{route('contact.detail')}}",
                method: 'POST',
                data: {
                    email: email,
                    text: text,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.message) {
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
                        $("#example-email").val("");
                        $("#example-textarea").val("");

                    } else {
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
                        $("#example-email").val("");
                        $("#example-textarea").val("");

                    }

                }

            })
        }
    })
</script>
@endsection
