@extends('emails.layout')
@section('content')
<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; background:#1e1e1e; -webkit-text-size-adjust:none">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#1e1e1e">
		<tr>
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

										<div class="img-center" style="font-size:0pt; line-height:0pt; text-align:center"><a href="#" target="_blank"><img src="{{asset('assets/images/logo.png')}}" border="0" width="203" height="100" alt="" /></a></div>
										
									</td>
								</tr>
							</table>
							<!-- END Header -->

							<!-- Main -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border: 6px #b20a2c solid; border-radius: 3%;">
								<tr>
									<td>
										<!-- Head -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#b20a2c">
											<tr>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td>
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" height="3" bgcolor="#b20a2c">&nbsp;</td>
																	</tr>
																</table>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="24" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

															</td>
														</tr>
													</table>
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
															<td>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

																<div class="h2" style="color:#ffffff; font-family:Georgia, serif; min-width:auto !important; font-size:60px; line-height:64px; text-align:center">
																	<em>{{ $mailTitle ?? '' }}</em>
																</div>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>


																<div class="h3-2-center" style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center; letter-spacing:5px">{{ $mailSubTitle ?? '' }}</div>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="35" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

															</td>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="10"></td>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
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

													<div class="h3-1-center" style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">{{ $mailBody ?? '' }}</div>
													<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="20" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

                                                    @if ($mailBtnText !='')
													<!-- Button -->
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td align="center">
																<table width="210" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td align="center" bgcolor="#b20a2c">
																			<table border="0" cellspacing="0" cellpadding="0">
																				<tr>
																					<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="15"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="50" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>
</td>
																					<td bgcolor="#b20a2c">
																						<div class="text-btn" style="color:#ffffff; font-family:Arial, sans-serif; min-width:auto !important; font-size:16px; line-height:20px; text-align:center">
																							<a href="{{ $mailBtnUrl ?? '' }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none"><span class="link-white" style="color:#ffffff; text-decoration:none">{{ $mailBtnText ?? ''}}</span></a>
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

										<!-- Foot -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#b20a2c">
											<tr>
												<td>
													<table width="50%" border="0" cellspacing="0" cellpadding="0" style="float: left;">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
															<td>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

																<div class="h3-1-center" style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">
																	<em>Follow Us</em>
																</div>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>


																<!-- Socials -->
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td align="center">
																			<table border="0" cellspacing="0" cellpadding="0">
																				<tr>
																					<td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="https://www.facebook.com/awayddings/" target="_blank"><img src="{{asset('assets/social-icon/facebook.png')}}" border="0" width="28" height="28" alt="" /></a></td>
	
																					<td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="https://www.linkedin.com/company/awayddings" target="_blank"><img src="{{asset('assets/social-icon/linkedin.png')}}" border="0" width="28" height="28" alt="" /></a></td>
																					<td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="https://www.instagram.com/awayddings" target="_blank"><img src="{{asset('assets/social-icon/instagram.png')}}" border="0" width="28" height="28" alt="" /></a></td>
																					
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
																<!-- END Socials -->
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

															</td>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
														</tr>
                                                    </table>
                                                    <table width="50%" border="0" cellspacing="0" cellpadding="0" style="float: right;">
														<tr>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
															<td>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="30" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

																<div class="h3-1-center" style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">
																	<em>Download Apps</em>
																</div>
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

								
																<!-- Socials -->
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td align="center">
																			<table border="0" cellspacing="0" cellpadding="0">
																				<tr>
																					<td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="{{asset('assets/social-icon/playstore.png')}}" border="0" width="28" height="28" alt="" /></a></td>
																					<td class="img-center" style="font-size:0pt; line-height:0pt; text-align:center" width="38"><a href="#" target="_blank"><img src="{{asset('assets/social-icon/mac-os.png')}}" border="0" width="28" height="28" alt="" /></a></td>
																					
																					
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
																<!-- END Socials -->
																<table width="100%" border="0" cellspacing="0" cellpadding="0" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%"><tr><td height="15" class="spacer" style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">&nbsp;</td></tr></table>

															</td>
															<td class="img" style="font-size:0pt; line-height:0pt; text-align:left" width="3" bgcolor="#b20a2c"></td>
														</tr>
													</table>
													
												</td>
											</tr>
										</table>
										<!-- END Foot -->
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
											Goa Address:- Rosary Apartments , Shop No – 11, Mir amar, Goa – 403 001
											<br />
											Udaipur Address:- 34 Trimurti Complex, Near Vaishali Apartment, Manvakhera Road, Sector 4 udaipur 313 001
											<br />
											<a href="https://www.awayddings.com/" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">www.awayddings.com</span></a>
											<span class="mobile-block"><span class="hide-for-mobile">|</span></span>
											<a href="mailto:hello@awayddings.com" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">hello@awayddings.com</span></a>
											<span class="mobile-block"><span class="hide-for-mobile">|</span></span>
											Phone: <a href="tel:+919921003303" target="_blank" class="link-1" style="color:#666666; text-decoration:none"><span class="link-1" style="color:#666666; text-decoration:none">+91 9921 003 303</span></a>
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
		</tr>
	</table>
</body>
@endsection