@extends('emails.layout')
@section('content')
    <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
           style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <td align="center" valign="top">
            <!-- Top -->

            <table width="600" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                <tr>
                    <td class="td" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; width:600px; min-width:600px; Margin:0" width="600">
                        <!-- Header -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                    <div class="img-center" style="font-size:0pt; line-height:0pt; text-align:center"><a href="#" target="_blank"><img src="https://www.awayddings.com/_nuxt/img/awayddings-logo.fa617c7.png" border="0" width="50%" height="50%" alt="" /></a></div>

                                </td>
                            </tr>
                        </table>
                        <!-- END Header -->

                        <!-- Main -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 6px #fff solid;">
                            <tr>
                                <td>
                                    <!-- Head -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff">
                                        <tr>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" height="3" bgcolor="#fff">&nbsp;</td>
                                                                </tr>
                                                            </table>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="24" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#fff"></td>
                                                        <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
                                                        <td>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                            <div class="h2" style="color:#fc3333; font-family:Arial, serif; min-width:auto !important; font-size:42px; line-height:64px; text-align:center">
                                                                {{ $mailTitle ?? '' }}
                                                            </div>
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>


                                                            <div class="h3-2-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center;">{{ $mailSubTitle ?? '' }}</div>

                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                        </td>
                                                        <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
                                                        <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#fff"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- END Head -->

                                    <!-- Body -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                        <tr>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                            <td>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                <div class="h3-1-center" style="color:#000; font-family:Arial, serif; min-width:auto !important; font-size:16px; line-height:26px; text-align:center">{{ $mailBody ?? '' }}</div>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="20" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                @if ($mailBtnText !='')
                                                    <!-- Button -->
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td align="center">
                                                                <table width="210" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td align="center" bgcolor="#fff">
                                                                            <table border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="15"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="50" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
                                                                                    </td>
                                                                                    <td bgcolor="#fc3333">
                                                                                        <div class="text-btn" style="color:#fc3333; font-family:Arial, sans-serif; min-width:auto !important; font-size:16px; line-height:20px; text-align:center">
                                                                                            <a href="{{ $mailBtnUrl ?? '' }}" target="_blank" class="link-white" style="padding:20px;color:#ffffff; text-decoration:none"><span class="link-white" style="color:#fff; text-decoration:none">{{ $mailBtnText ?? ''}}</span></a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="15"></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                                <!-- END Button -->

                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                            </td>
                                            <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                        </tr>
                                    </table>
                                    <!-- END Body -->
                                </td>
                            </tr>
                        </table>
                        <!-- END Main -->

                        <!-- Footer -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                    <div class="text-footer" style="color:#666666; font-family:Arial, sans-serif; min-width:auto !important; font-size:12px; line-height:18px; text-align:center">
                                        Goa:- Rosary Apartments, Shop No – 11, Miramar, Goa–403001
                                        <br />
                                        Udaipur:- 34 Trimurti Complex, Near Vaishali Apartment, Manvakhera Road, Sector 4, Udaipur-313001
                                        <br />
                                        <a href="https://www.awayddings.com/" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">www.awayddings.com</span></a>
                                        <span class="mobile-block"><span class="hide-for-mobile">|</span></span>

                                        <a href="mailto:hello.kesari@awayddings.com" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">hello.kesari@awayddings.com</span></a>
                                        <span class="mobile-block"><span class="hide-for-mobile">|</span></span>
                                        Phone: <a href="tel:+919921003303" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">+91 9921003303</span></a>
                                    </div>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                </td>
                                <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                            </tr>
                        </table>
                        <!-- END Footer -->
                    </td>
                </tr>
            </table>
            <div class="wgmail" style="font-size:0pt; line-height:0pt; text-align:center"><img src="https://d1pgqke3goo8l6.cloudfront.net/oD2XPM6QQiajFKLdePkw_gmail_fix.gif" width="600" height="1" style="min-width:600px" alt="" border="0" /></div>
        </td>
    </table>
    <!--/100% body table-->
    </body>
@endsection
