<?php
require_once '../../config/database.php';
$users = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Users Management</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-light"><div class="container p-4">
    <h3 class="fw-bold mb-4"><a href="../index.php" class="btn btn-sm btn-outline-secondary me-2">←</a> User Base</h3>
    <div class="card border-0 shadow-sm p-3">
        <table class="table">
            <thead><tr><th>User ID</th><th>Full Name</th><th>Email Address</th><th>Joined Date</th></tr></thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div></body>
</html>