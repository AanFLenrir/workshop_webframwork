<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.style-global')
    @stack('styles')
</head>

<body>
<div class="container-scroller">

    {{-- NAVBAR --}}
    @include('layouts.navbar')

    <div class="container-fluid page-body-wrapper">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        {{-- MAIN PANEL --}}
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>

            {{-- FOOTER --}}
            @include('layouts.footer')
        </div>

    </div>
</div>

@include('layouts.js-global')
@stack('scripts')

</body>
</html>