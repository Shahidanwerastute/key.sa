<?php if (!custom::rights(64, 'view')) { die; } ?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ custom::baseUrl('public/frontend/images/favicon.png?v=0.1') }}">

    <title>Key Car Rental | Server Logs</title>

    <!-- Style sheets-->
    <link href="{{ custom::baseUrl('public/vendor/log-viewer/app.css') }}" rel="stylesheet">
</head>

<body class="h-full px-3 lg:px-5 bg-gray-100 dark:bg-gray-900">
<div id="log-viewer" class="flex h-full max-h-screen max-w-full">
    <router-view></router-view>
</div>

<!-- Global LogViewer Object -->
<script>
    window.LogViewer = @json($logViewerScriptVariables);

    // Add additional headers for LogViewer requests like so:
    // window.LogViewer.headers['Authorization'] = 'Bearer xxxxxxx';
</script>
<script src="{{ custom::baseUrl('public/vendor/log-viewer/app.js') }}"></script>
</body>
</html>
