@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => $userResp->getEmailVerificationUrl])
Button Text
@endcomponent

This link will expire in 24 hours.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
