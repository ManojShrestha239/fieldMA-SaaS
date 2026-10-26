<meta charset="utf-8" />
@php
    $currentTenant = function_exists('tenant') ? tenant() : null;
    $titleName = $currentTenant ? $currentTenant->company_name : config('app.company_name') ?? 'FieldMA';
    $themeColor = $currentTenant ? $currentTenant->getThemeColor() : '#315DA9';
    $favIcon = $currentTenant ? $currentTenant->getFaviconUrl() : asset('assets/pages/img/logo-fieldma.png');
@endphp
<title>{{ $titleName }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<meta content="" name="author" />
<!-- BEGIN LAYOUT FIRST STYLES -->
<link href="//fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/global/plugins/bootstrap/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link rel="stylesheet" href="{{ URL::asset('assets/global/css/components.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/global/css/plugins.min.css') }}">

<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link rel="stylesheet" href="{{ URL::asset('assets/layouts/layout5/css/layout.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/layouts/layout5/css/custom.min.css') }}">
<!-- END THEME LAYOUT STYLES -->
<link rel="shortcut icon" href="{{ $favIcon }}" />
<link rel="icon" href="{{ $favIcon }}" />
<style>
    :root {
        --primary-theme-color: {{ $themeColor }};
        --nav-text-color: #315DA9;
    }

    .navActive {
        color: {{ $themeColor }} !important;
        border-bottom: 2px solid {{ $themeColor }} !important;
        font-weight: 600 !important;
    }

    .quickNav,
    #quickNav,
    .active {
        color: white !important;
        background: {{ $themeColor }} !important;
    }

    /* Tenant dynamic branding color */
    .navigation .nav.navbar-nav>li>a:hover,
    .dropdown-menu-v2>li>a:hover {
        color: #ffffff !important
    }

    .btn.blue:not(.btn-outline),
    .btn-primary {
        background-color: {{ $themeColor }} !important;
        border-color: {{ $themeColor }} !important;
    }

    .portlet.blue>.portlet-title,
    .portlet.box.blue {
        border-color: {{ $themeColor }} !important;
    }

    .portlet.box.blue>.portlet-title {
        background-color: {{ $themeColor }} !important;
    }
</style>
