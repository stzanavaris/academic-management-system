<?php
$page  = 'login-register';
$title = 'Σύνδεση / Εγγραφή - Global Academic Institute';

function isActive($p, $curr) {
    return $p === $curr ? 'class="active"' : '';
}
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

<header>
    <h1>Σύνδεση - Εγγραφή</h1>
    <nav>
        <ul>
            <li><a href="Template.php" <?= isActive('home', $page) ?>>Αρχική</a></li>
            <li><a href="programs.php" <?= isActive('programs', $page) ?>>Προγράμματα</a></li>
            <li><a href="departments.php" <?= isActive('departments', $page) ?>>Τμήματα</a></li>
            <li><a href="login-register.php" <?= isActive('login-register', $page) ?>>Εγγραφή-Σύνδεση</a></li>
            <li><a href="contact.php" <?= isActive('contact', $page) ?>>Επικοινωνία</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h1>Καλωσήρθες!</h1>
    <p>Επίλεξε μια από τις παρακάτω επιλογές:</p>

    <div class="buttons">
        <a href="login.php" class="btn login">Σύνδεση</a>
        <a href="registration.php" class="btn register">Εγγραφή</a>
    </div>
</div>

</body>
</html>
