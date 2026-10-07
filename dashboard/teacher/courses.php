<?php

require_once "../../auth/guard.php";
requireRole("teacher");

require_once "../../dashboard/config/db.php";

$teacherId = (int)$_SESSION["user"]["id"];


/* =========================
   GET TEACHER COURSES
   ========================= */

$stmt = mysqli_prepare(
        $conn,
        "SELECT
        c.id,
        c.title,
        c.description,
        c.created_at,

        (
            SELECT COUNT(*)
            FROM enrollments e
            WHERE e.course_id = c.id
        ) AS student_count,

        (
            SELECT COUNT(*)
            FROM assignments a
            WHERE a.course_id = c.id
        ) AS assignment_count

     FROM courses c

     WHERE c.teacher_id = ?

     ORDER BY c.created_at DESC"
);

mysqli_stmt_bind_param(
        $stmt,
        "i",
        $teacherId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


include "../header.php";

?>

    <!doctype html>

    <html lang="el">

    <head>

        <meta charset="utf-8">

        <meta
                name="viewport"
                content="width=device-width, initial-scale=1"
        >

        <title>Διαχείριση Μαθημάτων</title>

        <link
                rel="stylesheet"
                href="../../css/styles.css"
        >

    </head>


    <body>

    <main class="wrap">

        <section class="card">

            <h1 class="page-title">
                Διαχείριση Μαθημάτων
            </h1>

            <p class="meta">
                Εδώ βλέπεις τα μαθήματα που διδάσκεις και τις βασικές πληροφορίες τους.
            </p>


            <?php if (mysqli_num_rows($result) === 0): ?>

                <p>
                    Δεν υπάρχει μάθημα συνδεδεμένο με τον λογαριασμό σου.
                </p>

            <?php else: ?>

                <div class="list">


                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <div class="item">


                            <div class="item-head">

                                <strong>
                                    <?= htmlspecialchars($row["title"]) ?>
                                </strong>


                                <?php if ($row["created_at"]): ?>

                                    <div class="item-meta">

                                        Δημιουργήθηκε:
                                        <?= htmlspecialchars($row["created_at"]) ?>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <?php if (!empty($row["description"])): ?>

                                <div class="item-body">

                                    <?= htmlspecialchars($row["description"]) ?>

                                </div>

                            <?php endif; ?>


                            <div class="item-meta">

                                Εγγεγραμμένοι φοιτητές:
                                <strong>
                                    <?= (int)$row["student_count"] ?>
                                </strong>

                                |

                                Εργασίες:
                                <strong>
                                    <?= (int)$row["assignment_count"] ?>
                                </strong>

                            </div>


                        </div>

                    <?php endwhile; ?>


                </div>

            <?php endif; ?>


            <div class="actions">

                <a
                        class="btn"
                        href="../../dashboard.php"
                >
                    Πίσω
                </a>

            </div>

        </section>

    </main>

    </body>

    </html>

<?php

mysqli_stmt_close($stmt);

?>