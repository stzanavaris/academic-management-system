<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: Template.php");
    exit;
}
include 'dashboard/header.php';
$userName = $_SESSION['user']['name'] ?? '';
$userRole = $_SESSION['user']['role'] ?? '';
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main class="wrap">
    <section class="card">
        <h2 class="page-title">Καλωσήρθες <?= htmlspecialchars($userName) ?></h2>
        <p class="meta">Ρόλος: <strong><?= htmlspecialchars($userRole) ?></strong></p>

        <?php if ($userRole === 'student'): ?>
            <h3>Μενού Φοιτητή</h3>
            <div class="list">
                <div class="item">
                    <a class="btn primary" href="dashboard/student/courses.php">Μαθήματα</a>
                </div>
                <div class="item">
                    <a class="btn primary" href="dashboard/student/assignments.php">Εργασίες</a>
                </div>
                <div class="item">
                    <a class="btn primary" href="dashboard/student/grades.php">Βαθμολογίες</a>
                </div>
            </div>

        <?php elseif ($userRole === 'teacher'): ?>
            <h3>Μενού Καθηγητή</h3>
            <div class="list">
                <div class="item">
                    <a class="btn primary" href="dashboard/teacher/courses.php">Διαχείριση Μαθημάτων</a>
                </div>
                <div class="item">
                    <a class="btn primary" href="dashboard/teacher/assignments.php">Ανάρτηση Εργασιών</a>
                </div>
                <div class="item">
                    <a class="btn primary" href="dashboard/teacher/grading.php">Βαθμολόγηση</a>
                </div>
            </div>

        <?php else: ?>
            <div class="alert">
                Άγνωστος ρόλος χρήστη.
            </div>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>2025 Global Academic Institute</p>
</footer>

</body>
</html>
