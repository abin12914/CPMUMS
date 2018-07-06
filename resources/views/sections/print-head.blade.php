@if(config('settings.print_head_flag'))
    <div class="box-header visible-print-block text-center">
        <h3>{{ config('settings.company_name') }}</h3>
        <h4>{{ config('settings.company_address') }}</h4>
        <h5><u>{{ config('settings.company_phones') }}</u></h5>
    </div>
@endif
<div class="visible-print-block">
    <h4><u>@yield('title')</u></h4>
</div>