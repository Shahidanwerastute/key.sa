<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body style="margin: 0;">
    <div style="width: 600px; padding: 50px 0; height: 800px; margin: 0 auto">
    <img width="600" src="{{custom::baseurl('public/uploads/'.$file)}}">
    </div>
</body>
</html>