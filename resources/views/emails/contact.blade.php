<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #888;
            text-align: center;
        }

        a.button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        a.button:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hola!!</h2>

        <p>Esto es un test</p>

        <p>{{ $data['message'] }}</p>

        <div class="footer">
            &copy; {{ date('Y') }} Arratek Informática
        </div>
    </div>
</body>
</html>
