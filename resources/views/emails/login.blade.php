<!-- resources/views/emails/login.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Login Detected</title>
</head>
<body>
    <h2>Hello, {{ $params['name'] }}!</h2>
    <p>A new login was detected on your account.</p>
    <p>Email: {{ $params['email'] }}</p>
    <p>Time: {{ $params['login_time'] }}</p>
    <p>If this was not you, please contact support immediately.</p>
</body>
</html>