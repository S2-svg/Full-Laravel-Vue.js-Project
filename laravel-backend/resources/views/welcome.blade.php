<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — GlobalStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 50%, #1e1e2f 100%);
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        .welcome-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        .welcome-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(102, 126, 234, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 50%, rgba(118, 75, 162, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 4rem 3rem;
            max-width: 560px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.3);
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
        }

        .brand-text {
            font-weight: 800;
            font-size: 28px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.55);
            font-size: 16px;
            margin-top: 0.75rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .btn-admin-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 14px 40px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.2px;
            transition: all 0.25s ease;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-admin-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 32px rgba(102, 126, 234, 0.45);
            color: #fff;
        }

        .btn-store-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-top: 1.5rem;
            transition: color 0.2s;
        }

        .btn-store-link:hover {
            color: rgba(255, 255, 255, 0.85);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0 2.5rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .feature-list li {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .feature-list li i {
            font-size: 14px;
            color: #667eea;
        }

        .footer-note {
            margin-top: 3rem;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        @media (max-width: 576px) {
            .welcome-card {
                padding: 2.5rem 1.5rem;
                border-radius: 18px;
            }

            .brand-text {
                font-size: 22px;
            }

            .btn-admin-login {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="welcome-wrapper">
        <div class="welcome-card">
            <div class="brand-icon">
                <i class="bi bi-shop"></i>
            </div>
            <h1 class="brand-text">GlobalStore</h1>
            <p class="welcome-subtitle">
                Admin Management Panel<br>
                Manage products, categories, orders, and users.
            </p>

            <a href="{{ url('/admin/login') }}" class="btn-admin-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Login to Admin Panel
            </a>

            <ul class="feature-list">
                <li><i class="bi bi-check-circle"></i> Dashboard</li>
                <li><i class="bi bi-check-circle"></i> Categories</li>
                <li><i class="bi bi-check-circle"></i> Products</li>
                <li><i class="bi bi-check-circle"></i> Orders</li>
                <li><i class="bi bi-check-circle"></i> Users</li>
            </ul>

            <a href="http://localhost:5173" class="btn-store-link" target="_blank">
                <i class="bi bi-bag"></i>
                Visit Customer Store
            </a>
            <p class="footer-note">© {{ date('Y') }} GlobalStore. All rights reserved.</p>
        </div>

    </div>
</body>

</html>
