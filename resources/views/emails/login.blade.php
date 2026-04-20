<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Detected</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <!-- Main Background Table -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- White Content Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #dddddd;">
                    
                    <!-- Header Section -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #d9534f; text-align: center;">
                            <h2 style="margin: 0; color: #ffffff; font-size: 22px;">Security Alert</h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px; color: #333333; line-height: 1.6; font-size: 16px;">
                            <h3 style="margin-top: 0;">Hello, {{ $params['name'] }}!</h3>
                            <p>A new login was detected on your account. Please review the details below:</p>
                            
                            <!-- Nested Data Table -->
                            <table border="0" cellpadding="10" cellspacing="0" width="100%" style="background-color: #f9f9f9; border-left: 4px solid #d9534f; margin-bottom: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0;"><strong>Email:</strong> {{ $params['email'] }}</p>
                                        <p style="margin: 5px 0 0 0;"><strong>Time:</strong> {{ $params['login_time'] }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #d9534f; font-weight: bold; margin-bottom: 0;">If this was not you, please contact support immediately.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #eeeeee; text-align: center; color: #777777; font-size: 12px;">
                            <p style="margin: 0;">&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
