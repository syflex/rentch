@component('mail::message')
# Password changed notification

## You just change your password and access will only be with the new password


Thanks,<br>
{{ config('app.name') }}
@endcomponent