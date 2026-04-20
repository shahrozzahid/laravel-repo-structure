<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Our Platform</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <!-- Main Background Table -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- White Content Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #dddddd;">
                    
                    <!-- Header Section (Success Green) -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #28a745; text-align: center;">
                            <h2 style="margin: 0; color: #ffffff; font-size: 22px;">Registration Successful!</h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px; color: #333333; line-height: 1.6; font-size: 16px;">
                            <h3 style="margin-top: 0;">Welcome, {{ $params['name'] }}!</h3>
                            <p>We're excited to have you on board. Your account has been created successfully with the following details:</p>
                            
                            <!-- Nested Data Table -->
                            <table border="0" cellpadding="15" cellspacing="0" width="100%" style="background-color: #f0fdf4; border-left: 4px solid #28a745; margin-bottom: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 15px;"><strong>Registered Email:</strong><br>{{ $params['email'] }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-bottom: 0;">Thank you for registering! You can now log in and start exploring our features.</p>
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
