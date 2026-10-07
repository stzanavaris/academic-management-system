<?php
$page  = 'register';
$title = 'Εγγραφή - Global Academic Institute';

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
    <h1>Φόρμα Εγγραφής</h1>
    <nav aria-label="navigation">
        <ul>
            <li><a href="login-register.php" <?= isActive('home', $page) ?>>Αρχική</a></li>
            <li><a href="login.php" <?= isActive('login', $page) ?>>Σύνδεση</a></li>
        </ul>
    </nav>
</header>

<main class="wrap">
    <section class="card form-card">
        <h2>Δημιουργία Λογαριασμού</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box">
                <?php
                if ($_GET['error'] === 'empty') {
                    echo "Συμπλήρωσε όλα τα πεδία.";
                } elseif ($_GET['error'] === 'student_code') {
                    echo "Λάθος κωδικός εγγραφής για Φοιτητή.";
                } elseif ($_GET['error'] === 'teacher_code') {
                    echo "Λάθος κωδικός εγγραφής για Καθηγητή.";
                } elseif ($_GET['error'] === 'exists') {
                    echo "Υπάρχει ήδη χρήστης με αυτό το email.";
                } else {
                    echo "Σφάλμα εγγραφής.";
                }
                ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="register.php">

            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" required minlength="2">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required minlength="8">
            </div>

            <div class="field">
                <label for="role">Επιλογή ρόλου</label>
                <select id="role" name="role" required>
                    <option value="">-- Επιλέξτε --</option>
                    <option value="student">Φοιτητής</option>
                    <option value="teacher">Καθηγητής</option>
                </select>
            </div>

            <div class="field">
                <label for="regcode">Ειδικός κωδικός εγγραφής</label>
                <input id="regcode" name="regcode" type="text" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn primary">Εγγραφή</button>
                <button type="reset" class="btn">Καθαρισμός</button>
            </div>

        </form>
    </section>
</main>

<footer>
    <p>2025 Global Academic Institute</p>
</footer>

</body>
</html>
