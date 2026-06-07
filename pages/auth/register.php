<?php

session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $error = "Email already exists!";
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO users(name,email,password,role)
            VALUES(?,?,?,?)
        ");

        $stmt->execute([
            $name,
            $email,
            $password,
            $role
        ]);

        header("Location: login.php?registered=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Create Account
                    </h3>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <input
                            type="text"
                            name="name"
                            class="form-control mb-3"
                            placeholder="Full Name"
                            required
                        >

                        <input
                            type="email"
                            name="email"
                            class="form-control mb-3"
                            placeholder="Email Address"
                            required
                        >

                        <input
                            type="password"
                            name="password"
                            class="form-control mb-3"
                            placeholder="Password"
                            required
                        >

                        <select name="role" class="form-select mb-3" required>
                            <option value="">Register As</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <button class="btn btn-success w-100">
                            Register
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        Already have account?
                        <a href="login.php">Login</a>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>