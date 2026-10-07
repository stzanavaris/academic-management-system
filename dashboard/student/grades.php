<?php
require_once "../../auth/guard.php";
requireRole("student");
include "../header.php";
require_once "../../dashboard/config/db.php";

$studentId = (int)$_SESSION['user']['id'];

$sql = "SELECT c.title AS course_title,
               a.title AS assignment_title,
               s.grade,
               s.feedback,
               s.graded_at
        FROM submissions s
        JOIN assignments a ON a.id = s.assignment_id
        JOIN courses c ON c.id = a.course_id
        WHERE s.student_id = $studentId
        ORDER BY s.graded_at DESC, s.submitted_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Βαθμολογίες</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<main class="wrap">
    <section class="card">
        <h1>Βαθμολογίες</h1>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <p>Δεν υπάρχουν βαθμολογίες ακόμη.</p>
        <?php else: ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li style="margin-bottom:12px;">
                        <strong><?= htmlspecialchars($row['assignment_title']) ?></strong>
                        <div style="color:#67758c;">
                            Μάθημα: <?= htmlspecialchars($row['course_title']) ?>
                            <?php if ($row['graded_at']): ?> | Ημ/νία: <?= htmlspecialchars($row['graded_at']) ?><?php endif; ?>
                        </div>
                        <div><strong>Βαθμός:</strong> <?= htmlspecialchars((string)$row['grade']) ?></div>
                        <?php if ($row['feedback']): ?>
                            <div><em>Feedback:</em> <?= htmlspecialchars($row['feedback']) ?></div>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <div class="actions">
            <a class="btn" href="../../dashboard.php">Πίσω</a>
        </div>
    </section>
</main>
</body>
</html>
