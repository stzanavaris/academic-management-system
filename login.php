<?php
$page  = 'login';
$title = 'Σύνδεση - Global Academic Institute';

function isActive($p, $curr) {
    return $p === $curr ? 'class="active"' : '';
}
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header>
    <h1>Global Academic Institute</h1>
    <nav aria-label="navigation">
        <ul>
            <li><a href="login-register.php" <?= isActive('home', $page) ?>>Αρχική</a></li>
            <li><a href="registration.php" <?= isActive('register', $page) ?>>Εγγραφή</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="wrap">
        <div class="card form-card">
            <h2>Σύνδεση</h2>

            <form action="auth/login_action.php" method="post">
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="field">
                    <label for="password">Κωδικός</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="actions center">
                    <button class="btn primary" type="submit">Σύνδεση</button>
                </div>
            </form>

        </div>
    </div>
</main>

</body>
</html>
