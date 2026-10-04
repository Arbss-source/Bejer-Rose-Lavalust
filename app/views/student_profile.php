<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information — <?= htmlspecialchars($name); ?></title>
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
        nav { margin-bottom: 2.5rem; }
        nav a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 600;
            margin: 0 0.75rem;
            font-size: 0.95rem;
        }
        nav a:hover { text-decoration: underline; }
        .id-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            max-width: 460px;
            width: 100%;
            overflow: hidden;
        }
        .id-header {
            background: #2563eb;
            color: #fff;
            padding: 1.75rem 2rem;
            text-align: center;
        }
        .id-header h1 { font-size: 1.3rem; letter-spacing: 0.03em; }
        .avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #fff;
            color: #2563eb;
            font-weight: 700;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0.75rem auto 0;
        }
        .id-body { padding: 1.75rem 2rem; }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.92rem;
        }
        .row:last-child { border-bottom: none; }
        .row .label { color: #6b7280; font-weight: 600; }
        .row .value { text-align: right; max-width: 60%; }
        .bio {
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: #4b5563;
            line-height: 1.6;
            font-style: italic;
        }
    </style>
</head>
<body>

<nav>
    <a href="<?= site_url('student'); ?>">Home</a>
    <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
</nav>

<div class="id-card">
    <div class="id-header">
        <h1>Student Information</h1>
        <div class="avatar"><?= htmlspecialchars(strtoupper(substr($name, 0, 1))); ?></div>
    </div>
    <div class="id-body">
        <div class="row"><span class="label">Student ID</span><span class="value"><?= htmlspecialchars($student_id); ?></span></div>
        <div class="row"><span class="label">Name</span><span class="value"><?= htmlspecialchars($name); ?></span></div>
        <div class="row"><span class="label">Course</span><span class="value"><?= htmlspecialchars($course); ?></span></div>
        <div class="row"><span class="label">Year Level</span><span class="value"><?= htmlspecialchars($year); ?></span></div>
        <div class="row"><span class="label">Section</span><span class="value"><?= htmlspecialchars($section); ?></span></div>
        <div class="row"><span class="label">Email</span><span class="value"><?= htmlspecialchars($email); ?></span></div>
        <div class="row"><span class="label">Address</span><span class="value"><?= htmlspecialchars($address); ?></span></div>
        <div class="row"><span class="label">Contact</span><span class="value"><?= htmlspecialchars($contact); ?></span></div>
        <div class="row"><span class="label">Skills</span><span class="value"><?= htmlspecialchars($skills); ?></span></div>
        <p class="bio">"<?= htmlspecialchars($bio); ?>"</p>
    </div>
</div>

</body>
</html>
