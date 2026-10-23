<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workspace Access Expired - FieldMA</title>
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
            color: #f0ad4e;
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
            background: #fcf8e3;
            color: #8a6d3b;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 13px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⏱</div>
        <div class="badge">
            @if($reason === 'trial')
                Trial Expired
            @else
                Subscription Expired
            @endif
        </div>
        <h1>Workspace Access Expired</h1>
        <p>
            {{ $message ?? 'Your workspace subscription or trial period has ended.' }}
        </p>
        <p style="font-size: 14px; color: #888;">
            Please contact support or the platform administrator to renew or upgrade your plan.
        </p>
        <div class="footer">
            <strong>{{ $tenant->company_name ?? 'FieldMA' }}</strong> &bull; FieldMA SaaS
        </div>
    </div>
</body>
</html>
