<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel CRUD Builder Project Link</title>
    <style>
        /* General reset */
        body, h2, p, a {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
        }

        /* Background Gradient */
        body {
            background: radial-gradient(ellipse at top, #151a33, #000);
            color: #fff;
            padding: 40px 0;
        }

        /* Table Styling */
        .email-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .email-header {
            background-color: #1f2838;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
            background-color: #2c3e50;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #fff;
            font-size: 24px;
            margin-bottom: 15px;
        }
        p {
            color: #ccc;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        a {
            color: #1abc9c;
            font-weight: bold;
            text-decoration: none;
            border: 1px solid #1abc9c;
            padding: 12px 24px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        a:hover {
            background-color: #1abc9c;
            color: #fff;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #bbb;
        }
    </style>
</head>
<body>
    <table class="email-container">
        <tr>
            <td class="email-header">
                <h2>GitHub Project Link</h2>
            </td>
        </tr>
        <tr>
            <td class="email-body">
                <p>Hello {{ $email }},</p>
                <p>Thank you for your interest! You can find the source code for the project on GitHub:</p>
                <p>
                    <a href="{{ $github_link }}" target="_blank">{{ $github_link }}</a>
                </p>
                <p>Best regards,<br>Thank You</p>
            </td>
        </tr>
    </table>
    <div class="footer">
        <p>Powered by Md Habibur Rahman. All rights reserved.</p>
    </div>
</body>
</html>
