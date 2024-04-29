<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@800&display=swap" rel="stylesheet">
        <title>Contact Us</title>
        <style>
            body{
	 	        margin: 0;
                font-family: 'Barlow', sans-serif;
                height: 100vh;
	        }
            a{
                text-decoration: none;
                cursor: pointer;
                color: #0092c2;
            }
            p{
                color: black;
                margin: 0;
                padding: 0;
            }
            h1,h2,h3,h4,h5,h6{
                margin: 0;
                padding: 0;
            }
            table{
                border-collapse: collapse;
            }
            .main{
                margin: 0 auto;
                background-color: #EAF0F3;
            }
            td{
                padding: 0;
            }
            h1{
                font-size: 20px;
                font-weight: 600;
                line-height: 24px;
                color: #000;
            }
            img{
                display: block;
                max-width: 100%;
            }
            .password-para{
                padding: 0 46px;
            }
            .password-para p{
                font-size: 15px;
                line-height: 19px;
                font-weight: 400;
                color: #3D3D3D;
                margin-bottom: 15px;
            }
            button{
                font-size: 10px;
                font-weight: 700;
                line-height: 14px;
                color: #FFFFFF;
                background: #3490EC;
                padding: 10px 40px;
                border: 1px solid #3490EC;
                border-radius: 50px;
                margin-bottom: 20px;
            }
            .thanku-para, .details_vt{
                padding: 0 46px;
            }
            .thanku-para p{
                font-size: 15px;
                line-height: 19px;
                font-weight: 400;
                color: #5E5E5E;
                margin-bottom: 40px;
            }
            .details_vt p{
                color: #7B7B7B;
                font-size: 15px;
                line-height: 19px;
                font-weight: 400;
            }
            .footer p{
                font-size: 12px;
                line-height: 16px;
                font-weight: 400;
                color: #000000;

            }
        </style>
    </head>
    <body>
        <table style="width: 100%;" height="100%">
            <tr>
                <td valign="middle">
                    <table class="main" width="600" align="center">
                        <tbody>
                            <tr>
                                <td align="center" style=" padding: 30px 0 10px;">
                                    <h1>Contact Us</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0px 50px 20px;">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table style="background-color: white;" style="width: 100%;">
                                                        <tr>
                                                            <td align="center" style="padding: 15px 0 20px;">
                                                                <h2><img src="{{ asset('assets/images/writting.png')}}" style="max-width: 10%;"></h2>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para">
                                                                <p>{{$text}}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="details_vt">
                                                                <p>Name</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para">
                                                                <p>{{$name}}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="details_vt">
                                                                <p>Email</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="password-para">
                                                                <p>{{$email}}</p>
                                                            </td>
                                                        </tr>
{{--                                                        <tr>--}}
{{--                                                            <td class="details_vt">--}}
{{--                                                                <p>Phone Number</p>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td class="password-para">--}}
{{--                                                                <p>{{$phone}}</p>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 30px;" align="center">
                                    <table style="width: 100%;">
                                        <tbody class="footer">
                                            <tr>
                                                <td align="center">
                                                    <h3><img src="{{asset('assets/images/'.$setting[21]['value'])}}" style="max-width: 8%;"></h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <p>Copyright © {{Date('Y')}}. All Rights Reserved.</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
