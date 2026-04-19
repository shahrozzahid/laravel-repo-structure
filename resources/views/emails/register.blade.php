<!-- resources/views/emails/register.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, {{ $params['name'] }}!</h2>
    <p>Your account has been created successfully.</p>
    <p>Email: {{ $params['email'] }}</p>
    <p>Thank you for registering!</p>
</body>
</html>