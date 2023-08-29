<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', "S3 B2B") }}</title>

    @routes
    @vite(['resources/js/app.js', "resources/js/pages/{$page['component']}.vue", 'resources/scss/app.scss'])
    @inertiaHead
</head>
<body class="antialiased">
@inertia
</body>
</html>
