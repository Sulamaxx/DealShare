<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Warning Email</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>{{ $messageBody }}</p>

    <p>Thank you,<br>
    Admin Team</p>
</body>
</html>
