<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')
    {{-- Right Panel --}}
    <div id="right-panel" class="right-panel">
        {{-- Header --}}
        @include('layouts.partials.header')
        {{-- Konten Utama --}}
        <div class="content">
            @yield('content')
        </div>
        {{-- Footer --}}
        @include('layouts.partials.footer')
    </div>
    {{-- Script --}}
    @include('layouts.partials.scripts')
</body>
</html>