<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Directory | LavaLust</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #17212b;
    }
    * { box-sizing: border-box; }
    body {
            min-height: 100vh;
            color: var(--ink);
            background: var(--paper);
            font-family: 'DM Sans', sans-serif;
        }
        .shell { min-height: 100vh; display: grid; grid-template-columns: 232px 1fr; }
    .brand { display: flex; align-items: center; gap: 10px; margin: 0 10px 52px; color: #fff; text-decoration: none; }
    .brand-mark { display: grid; width: 34px; height: 34px; place-items: center; color: var(--ink); background: var(--yellow); border-radius: 10px; font-family: 'Space Grotesk', sans-serif; font-weight: 700; }
    .brand-name { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 700; letter-spacing: -.03em; }
    .nav { display: grid; gap: 6px; }
    .nav a { display: flex; align-items: center; gap: 12px; padding: 12px 13px; color: #a9c5c1; border-radius: 9px; font-size: .88rem; text-decoration: none; }
    .nav a:hover, .nav a.active { color: #fff; background: #215354; }
    main { padding: 38px clamp(24px, 5vw, 72px) 58px; overflow: hidden; }
    .topline { display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 42px; }
    .crumb { color: var(--muted); font-size: .8rem; }
    .directory { background: var(--white); border: 1px solid var(--line); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
    .directory-head { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 20px 24px; border-bottom: 1px solid var(--line); }
    .directory-title { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 600; }
        <section class="directory" aria-label="Users list">
            <div class="directory-head">
                <span class="directory-title">All members</span>
                    <thead>
                        <tr><th scope="col">Member</th><th scope="col">Email</th><th scope="col">Username</th><th scope="col">Member ID</th></tr>
                    </thead>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <?php $initials = strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1)); ?>
                                <td><div class="person"><span class="avatar"><?= htmlspecialchars($initials); ?></span><span class="name"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span></div></td>
                                <td class="email"><?= htmlspecialchars($user['email']); ?></td>
                                <td class="username">@<?= htmlspecialchars($user['username']); ?></td>
                                <td class="id">#<?= htmlspecialchars($user['id']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="empty">No users found.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </section>
    </main>
    </div>
<script>
    const search = document.getElementById('user-search');
    const rows = document.querySelectorAll('#user-rows tr');
    search.addEventListener('input', function () {
    });
</script>
</body>
</html>
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
        h1 { margin-bottom: 1.5rem; font-size: 1.6rem; }
        table {
            background: #fff;
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 0.85rem 1.25rem;
            text-align: left;
            font-size: 0.92rem;
        }
        th {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
        }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #eef2ff; }
        td { border-bottom: 1px solid #f1f5f9; }
        .empty {
            padding: 1.5rem;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>
<body>

<h1>Users</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']); ?></td>
                    <td><?= htmlspecialchars($user['firstname']); ?></td>
                    <td><?= htmlspecialchars($user['lastname']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="empty">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
