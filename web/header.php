<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'My Website'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/noisy.png') repeat;
            opacity: 0.1;
            z-index: -1;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .navbar:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand, .nav-link {
            color: #fff !important;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #00d4ff !important;
            transform: translateY(-2px);
        }

        .glass-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            overflow: hidden;
            animation: float 4s ease-in-out infinite;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .glass-card-inner {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card-inner:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px 12px 0 0;
        }

        .glass-input {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 8px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-input::placeholder {
            color: #ccc;
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-glass {
            backdrop-filter: blur(10px);
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            color: #fff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: linear-gradient(45deg, #0056b3, #003d80);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .table {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-table thead {
            background: rgba(0, 123, 255, 0.2);
        }

        .table-hover tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s ease;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .pre {
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px;
            overflow-x: auto;
            color: #fff;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .file-preview img, .file-preview object, .file-preview iframe {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .file-preview:hover img, .file-preview:hover object, .file-preview:hover iframe {
            transform: scale(1.02);
        }

        .home-unlogged-text a {
            color: #ff4500 !important;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .home-unlogged-text a:hover {
            color: #ff6347 !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-glass shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="?page=home">My Website</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="?page=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="?page=about">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="?page=services">Services</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="?page=contact">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link text-white" href="?page=upload">Upload File</a></li>
                        <?php if ($_SESSION['is_admin']): ?>
                            <li class="nav-item"><a class="nav-link text-white" href="?page=admin">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link text-white" href="?action=logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-white" href="?page=register">Register</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="?page=login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">