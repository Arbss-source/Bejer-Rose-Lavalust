<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal — Home</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f4f7fb 0%, #e8edf5 100%);
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1.5rem;
        }
        nav {
            margin-bottom: 2.5rem;
        }
        nav a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 600;
            margin: 0 0.75rem;
            font-size: 0.95rem;
        }
        nav a:hover { text-decoration: underline; }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2.5rem 3rem;
            max-width: 480px;
            text-align: center;
        }
        .badge-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1.25rem;
        }
        h1 { font-size: 1.6rem; margin-bottom: 0.5rem; }
        p.sub { color: #6b7280; margin-bottom: 1.5rem; line-height: 1.5; }
        .notice {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>

<nav>
    <a href="<?= site_url('student'); ?>">Home</a>
    <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
</nav>

<div class="card">
    <div class="badge-icon">🎓</div>
    <h1>Welcome, <?= htmlspecialchars($name); ?></h1>
    <p class="sub">This is your student portal home page. Visiting this page grants you a temporary access badge to view your profile.</p>

    <?php if ($denied): ?>
        <div class="notice">
            Access denied: you need an active badge before viewing the profile page.
            It's been granted now — try the link below.
        </div>
    <?php endif; ?>

    <a class="btn" href="<?= site_url('student/profile'); ?>">View My Profile →</a>
</div>

</body>
</html>
