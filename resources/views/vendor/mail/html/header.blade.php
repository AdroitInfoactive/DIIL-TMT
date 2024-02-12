@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <div class="login-brand">
        <img src="{{ asset( config('settings.logo')) }}" alt="{{ config('settings.site_name') }}"
            class="shadow-light">
    </div>
{{-- @if (trim($slot) === 'Laravel')
<img src="{{ asset('http://127.0.0.1:8000/uploads/logos/media_65a8d20777d9b.svg') }}" class="logo" alt="{{ config('settings.site_name') }}"/>
@else
{{ $slot }}
@endif --}}
</a>
</td>
</tr>
