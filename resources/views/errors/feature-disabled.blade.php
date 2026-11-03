<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feature Not Included in Your Plan - FieldMA</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f3f5f8;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            max-width: 520px;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }
        .icon {
            font-size: 54px;
            color: #d9534f;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 12px;
        }
        p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .badge {
            display: inline-block;
            background: #fdf2f2;
            color: #c53030;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 4px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-outline {
            background: transparent;
            color: #666;
            border: 1px solid #ccc;
            margin-left: 10px;
        }
        .btn-outline:hover {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">&#9888;</div>
        <h1>Feature Not Included in Your Plan</h1>
        <div class="badge">{{ $featureName ?? ucwords(str_replace(['_', '-'], ' ', $feature ?? 'Module')) }}</div>
        <p>Your company's current subscription plan does not include access to the <strong>{{ $featureName ?? $feature ?? 'this' }}</strong> module. Please contact your organization administrator or upgrade your subscription plan to unlock this feature.</p>
        <div>
            <a href="javascript:history.back()" class="btn btn-outline">Go Back</a>
        </div>
    </div>
</body>
</html>
