<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel=stylesheet href="{{ mix('css/app.css') }}" />
        <script>
            window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
        </script>
        <title>Vdent Admin Panel</title>
{{--        <meta name="viewport"--}}
{{--              content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">--}}
{{--        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"--}}
{{--              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"--}}
{{--              crossorigin="anonymous">--}}
{{--        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdbvue/lib/css/mdb.min.css">--}}
{{--        <script>--}}
{{--            window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};--}}
{{--        </script>--}}
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;700&display=swap');
            body {
                font-family: 'Noto Naskh Arabic', serif;
                direction: rtl!important;
            }
        </style>
    </head>
    <body class="antialiased">
    <div id=app class=wrapper>

        <header  id=appHeader class=app-header>
            @if(Auth::check())
                <sidebar :user="{{Auth::user()}}" :permission="{{ $permission }}" ></sidebar>
            @else
                <sidebar :user="false"></sidebar>
            @endif

        </header>
    </div>
    <script src="../js/app.js"></script>
    </body>
    <script>
        import sidebar from "../js/components/sidebar";
        export default {
            components: {sidebar}
        }
    </script>
</html>
