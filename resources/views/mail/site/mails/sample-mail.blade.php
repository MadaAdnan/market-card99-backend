@component('mail::message')
# {{$title}}

{{$body}}

@component('mail::button', ['url' => $route])
إذهب لصفحة الموقع
@endcomponent

شكرا<br>
{{ config('app.name') }}
@endcomponent
