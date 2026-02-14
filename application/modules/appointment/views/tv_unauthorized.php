<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .error-icon {
            font-size: 6em;
            color: #e74c3c;
            margin-bottom: 30px;
        }

        .error-title {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error-message {
            font-size: 1.3em;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .error-code {
            font-size: 1em;
            color: #999;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body>

    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        <h1 class="error-title">
            <?php echo isset($title) ? $title : 'Unauthorized Access'; ?>
        </h1>
        <p class="error-message">
            <?php echo isset($message) ? $message : 'You do not have permission to access this TV display.'; ?>
        </p>
        <a href="<?php echo base_url(); ?>" class="back-link">
            <i class="fas fa-home"></i> Go to Home
        </a>
        <div class="error-code">
            Error Code: 403 - Forbidden
        </div>
    </div>

</body>

</html>