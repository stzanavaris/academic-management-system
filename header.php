<?php
if (!isset($title)) {
    $title = 'Global Academic Institute';
}
if (!isset($page)) {
    $page = '';
}
if (!function_exists('isActive')) {
    function isActive($p, $curr) {
        return $p === $curr ? 'class="active"' : '';
    }
}
?>

<header>
    <h1>Καλωσορίσατε στο <?= htmlspecialchars($title) ?></h1>
    <nav aria-label="Κύρια πλοήγηση">
        <ul>
            <li><a href="Template.php" <?= isActive('home', $page) ?>>Αρχική</a></li>
            <li><a href="programs.php" <?= isActive('programs', $page) ?>>Προγράμματα</a></li>
            <li><a href="departments.php" <?= isActive('departments', $page) ?>>Τμήματα</a></li>
            <li><a href="login-register.php" <?= isActive('login', $page) ?>>Εγγραφή-Σύνδεση</a></li>
            <li><a href="contact.php" <?= isActive('contact', $page) ?>>Επικοινωνία</a></li>
        </ul>
    </nav>
</header>
