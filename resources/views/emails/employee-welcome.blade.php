<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Employee Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .credentials {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .credentials p {
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .warning {
            color: #ff9800;
            font-size: 12px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Employee Management System</h2>
            <p>Welcome to the Team!</p>
        </div>
        
        <div class="content">
            <h3>Hello {{ $name }}!</h3>
            
            <p>Your employee account has been created successfully by the administrator.</p>
            
            <div class="credentials">
                <h4>📋 Your Account Credentials:</h4>
                <p><strong>📧 Email:</strong> {{ $email }}</p>
                <p><strong>🔑 Password:</strong> {{ $password }}</p>
                <p><strong>🆔 Employee ID:</strong> {{ $employee_id }}</p>
                <p><strong>💼 Position:</strong> {{ $position }}</p>
                <p><strong>🏢 Department:</strong> {{ $department }}</p>
                <p><strong>💰 Salary:</strong> ${{ number_format($salary, 2) }}</p>
            </div>
            
            <p><strong>⚠️ Important:</strong> Please login and change your password immediately for security purposes.</p>
            
            <center>
                <a href="{{ url('/login') }}" class="button">🔐 Login to Your Account</a>
            </center>
            
            <p class="warning">⚠️ This is an automated message. Please do not reply to this email.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Employee Management System. All rights reserved.</p>
            <p>Developed by Group 4</p>
        </div>
    </div>
</body>
</html>