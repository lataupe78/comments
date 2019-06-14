<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div id="app">

        @include('front.partials._navbar')

        <main class="pb-4">
            @yield('content')

        {{--
            <example-component>
                <template #header>Component de test</template>
                testing vue component
            </example-component>
        --}}

        </main>
    </div>

    @yield('content_bottom')

    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('scripts_bottom')
</body>
</html>
