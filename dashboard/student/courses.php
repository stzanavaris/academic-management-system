<?php
require_once "../../auth/guard.php";
requireRole("student");
include "../header.php";
require_once "../../dashboard/config/db.php";

$studentId = (int)$_SESSION['user']['id'];

$sql = "SELECT c.id, c.title, c.description, u.name AS teacher_name
        FROM enrollments e
        JOIN courses c ON c.id = e.course_id
        JOIN users u ON u.id = c.teacher_id
        WHERE e.student_id = $studentId
        ORDER BY c.title ASC";

$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Μαθήματα Φοιτητή</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<main class="wrap">
    <section class="card">
        <h1 class="page-title">Μαθήματα Φοιτητή</h1>
        <p class="meta">Εδώ βλέπεις τα μαθήματα στα οποία είσαι εγγεγραμμένος/η.</p>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <p>Δεν είσαι εγγεγραμμένος/η σε κάποιο μάθημα.</p>
        <?php else: ?>
            <div class="list">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="item">
                        <div class="item-head">
                            <strong><?= htmlspecialchars($row['title']) ?></strong>
                            <div class="item-meta">
                                Καθηγητής: <?= htmlspecialchars($row['teacher_name']) ?> | Course ID: <?= (int)$row['id'] ?>
                            </div>
                        </div>

                        <?php if (!empty($row['description'])): ?>
                            <div class="item-body"><?= htmlspecialchars($row['description']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a class="btn" href="../../dashboard.php">Πίσω</a>
        </div>
    </section>
</main>
</body>
</html>
