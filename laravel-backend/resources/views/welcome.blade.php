<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobalStore — Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
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
                radial-gradient(ellipse at 20% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 50%, rgba(168, 85, 247, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .welcome-container {
            max-width: 1000px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #fff;
            margin-bottom: 1rem;
            box-shadow: 0 12px 32px rgba(99, 102, 241, 0.4);
        }

        .brand-text {
            font-weight: 800;
            font-size: 36px;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            font-weight: 400;
            margin-top: 0.5rem;
        }

        .cards-row {
            display: grid;
            grid-template-columns: 1fr;
            max-width: 460px;
            margin: 0 auto;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.3);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .portal-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 30px 72px rgba(0, 0, 0, 0.45);
        }

        .portal-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 1.25rem;
        }

        .portal-icon.admin {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
        }

        .portal-title {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 0.5rem;
        }

        .portal-desc {
            color: rgba(255, 255, 255, 0.55);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 1.75rem;
            min-height: 44px;
        }

        .portal-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }

        .portal-features li {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .portal-features li i {
            font-size: 12px;
        }

        .portal-features.admin li i {
            color: #818cf8;
        }

        .btn-portal {
            border: none;
            color: #fff;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.2px;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .btn-portal.admin {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
        }

        .btn-portal.admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 32px rgba(99, 102, 241, 0.45);
            color: #fff;
        }

        .footer-note {
            margin-top: 3rem;
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        @media (max-width: 576px) {
            .brand-text {
                font-size: 26px;
            }

            .portal-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="welcome-wrapper">
        <div class="welcome-container">
            <div class="brand-header">
                <div class="brand-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <h1 class="brand-text">GlobalStore</h1>
                <p class="brand-subtitle">Your one-stop shop for everything you need</p>
            </div>

            <div class="cards-row">
                <div class="portal-card">
                    <div>
                        <div class="portal-icon admin">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h2 class="portal-title">Admin Panel</h2>
                        <p class="portal-desc">Manage products, categories, orders, and users from a unified dashboard.</p>
                        <ul class="portal-features admin">
                            <li><i class="bi bi-check-circle"></i> Dashboard</li>
                            <li><i class="bi bi-check-circle"></i> Categories</li>
                            <li><i class="bi bi-check-circle"></i> Products</li>
                            <li><i class="bi bi-check-circle"></i> Orders</li>
                            <li><i class="bi bi-check-circle"></i> Users</li>
                        </ul>
                    </div>
                    <a href="{{ url('/admin/login') }}" class="btn-portal admin">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Admin Login
                    </a>
                </div>
            </div>

            <p class="footer-note">© {{ date('Y') }} GlobalStore. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
