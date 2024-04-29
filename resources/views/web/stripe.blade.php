@extends('web.layouts.master')
@section('content')
    @include('web.layouts.partials.header')

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style type="text/css">
        .panel-title {
        display: inline;
        font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 61%;
        }
    </style> -->
    <style>
        #alertID{
            position:absolute;
            right:0;
            padding:10px;
            top:6px;
        }
    </style>


    <div class="container-fluid px-0">
        <div class="position-relative">
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
        <div class="strip-content-area">
            <div class="row">
                <div class="col-lg-3 col-md-3 mb-2">
                    <div class="card-img-holder">
                        <img src="{{asset('assets/images/Card.png')}}" class="w-100" alt="">
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">
                    <div class="panel">
                        <div class="panel-heading">
                            <h1>Payment Details</h1>
                        </div>
                        <div class="panel-body">
                            @if (Session::has('success'))
                                <div class="alert alert-success text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            @endif

                            <div class="subtitle">
                                <p>{{$Courses->course_title}}</p>
                            </div>

                            <div class="strip-price-section d-flex justify-content-between border-top border-bottom">
                                <p>Price</p>
                                <span>{{$currencySymbol}} {{$Courses->price}}</span>
                            </div>

                            <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ $getStripePublishKey  }}" id="payment-form">
                                @csrf
                                <input type="hidden" class="course_id" value="{{$Courses->id}}">
                                <input type="hidden" class="course_price" value="{{$Courses->price}}">

                                <div class="col-lg-12 mb-3">
                                    <label for="exampleInputEmail1">Name On Card</label>
                                    <input type="text" class="form-control account-name" minlength="4" name="account_name" placeholder="Enter Card Holder Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="exampleInputEmail1">Card Number</label>
                                    <input type="number" class="form-control card-number" name="card_number" placeholder="Enter Card Number"
                                           autocomplete='off' required>
                                </div>

                                <div class="row">
                                    <div class='col-lg-4 col-md-6 form-group cvc required mb-3'>
                                        <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                                        class='form-control card-cvc' name="card_cvc" placeholder='CVC'
                                                                                        type='number' required>
                                    </div>
                                    <div class='col-lg-4 col-md-6 form-group expiration required mb-3'>
                                        <label class='control-label'>Expiration Month</label>
                                        <select class='form-control card-expiry-month' name='expiration_month'>
                                            <option value=''>Select Month</option>
                                            <option value='01'>01</option>
                                            <option value='02'>02</option>
                                            <option value='03'>03</option>
                                            <option value='04'>04</option>
                                            <option value='05'>05</option>
                                            <option value='06'>06</option>
                                            <option value='07'>07</option>
                                            <option value='08'>08</option>
                                            <option value='09'>09</option>
                                            <option value='10'>10</option>
                                            <option value='11'>11</option>
                                            <option value='12'>12</option>
                                        </select>
                                    </div>
                                    @php
                                        $currentYear = date('Y');
                                        $endYear = $currentYear + 20;
                                    @endphp

                                    <div class='col-lg-4 col-md-6 form-group expiration required mb-3'>
                                        <label class='control-label'>Expiration Year</label>
                                        <select class='form-control card-expiry-year'>
                                            <option value=''>Select Year</option>
                                            @for ($year = $currentYear; $year <= $endYear; $year++)
                                                <option value={{$year}}>{{$year}}</option>
                                            @endfor
{{--                                            <option value='2023'>2023</option>--}}
{{--                                            <option value='2024'>2024</option>--}}
{{--                                            <option value='2025'>2025</option>--}}
{{--                                            <option value='2026'>2026</option>--}}
{{--                                            <option value='2027'>2027</option>--}}
                                        </select>
                                    </div>

                                </div>

                                <div class='form-row row'>
                                    <div class='col-md-12 error form-group' style="display:none" id="errorDiv">
                                        <div class='alert-danger alert'>Please correct the errors and try
                                            again.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <button class="btn btn-warning w-100 custom-btn p-2 strip-btn" type="submit">Pay Now</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script>
        const cardNumberInput = document.querySelector('.card-number');

        cardNumberInput.addEventListener('input', function() {
            const inputValue = this.value.trim();
            const minLength = 16;
            const maxLength = 16;

            if (inputValue.length < minLength || inputValue.length > maxLength) {
                this.setCustomValidity(`Card number must be ${minLength} digits long.`);
            } else {
                this.setCustomValidity('');
            }
        });

        const cvcInput = document.querySelector('.card-cvc');

        cvcInput.addEventListener('input', function() {
            const inputValue = this.value.trim();
            const minLength = 3;
            const maxLength = 3;

            if (inputValue.length < minLength || inputValue.length > maxLength) {
                this.setCustomValidity(`CVC number must be ${minLength} digits long.`);
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
    <script type="text/javascript">
        $(function() {

            var $form         = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $('#errorDiv').removeAttr('style');
                        e.preventDefault();
                    }
                });
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }
            });
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeAttr('style')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.append("<input type='hidden' name='card_expiry_month' value='" + $('.card-expiry-month').val() + "'/>");
                    $form.append("<input type='hidden' name='card_expiry_year' value='" + $('.card-expiry-year').val() + "'/>");
                    $form.append("<input type='hidden' name='card_cvc' value='" + $('.card-cvc').val() + "'/>");
                    $form.append("<input type='hidden' name='card_number' value='" + $('.card-number').val() + "'/>");
                    $form.append("<input type='hidden' name='account_name' value='" + $('.account-name').val() + "'/>");
                    $form.append("<input type='hidden' name='course_id' value='" + $('.course_id').val() + "'/>");
                    $form.append("<input type='hidden' name='course_price' value='" + $('.course_price').val() + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>
@endsection
