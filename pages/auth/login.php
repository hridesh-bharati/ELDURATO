<?php

session_start();
require_once '../../config/database.php';

if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] === 'admin') {
        header("Location: ../../admin/index.php");
    } else {
        header("Location: ../account/dashboard.php");
    }

    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email=?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../../admin/index.php");
        } else {
            header("Location: ../account/dashboard.php");
        }

        exit;

    } else {

        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow border-0">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Login
                    </h3>

                    <?php if(isset($_GET['registered'])): ?>
                        <div class="alert alert-success">
                            Registration successful.
                        </div>
                    <?php endif; ?>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

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

                        <button class="btn btn-dark w-100">
                            Login
                        </button>

                    </form>

                    <div class="text-center mt-3">
                        <a href="register.php">
                            Create Account
                        </a>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>