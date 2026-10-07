<?php

require_once "../../auth/guard.php";
requireRole("teacher");

require_once "../../dashboard/config/db.php";

$teacherId = (int)$_SESSION['user']['id'];

$message = "";
$messageType = "";


/* =========================
   SAVE GRADE
   ========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $submissionId = (int)($_POST["submission_id"] ?? 0);
    $grade = trim($_POST["grade"] ?? "");
    $feedback = trim($_POST["feedback"] ?? "");


    /* =========================
       BASIC VALIDATION
       ========================= */

    if ($submissionId <= 0 || $grade === "") {

        $message = "Συμπλήρωσε βαθμό.";
        $messageType = "error";

    } elseif (!is_numeric($grade)) {

        $message = "Ο βαθμός πρέπει να είναι αριθμός.";
        $messageType = "error";

    } else {

        $gradeVal = (float)$grade;


        /* =========================
           GRADE RANGE
           ========================= */

        if ($gradeVal < 0 || $gradeVal > 10) {

            $message = "Ο βαθμός πρέπει να είναι από 0 έως 10.";
            $messageType = "error";

        } else {

            /*
             * Update the submission only if it belongs
             * to a course taught by the logged-in teacher.
             */

            $updateStmt = mysqli_prepare(
                    $conn,
                    "UPDATE submissions s
                 JOIN assignments a
                    ON a.id = s.assignment_id
                 JOIN courses c
                    ON c.id = a.course_id
                 SET
                    s.grade = ?,
                    s.feedback = ?,
                    s.graded_at = NOW()
                 WHERE s.id = ?
                 AND c.teacher_id = ?"
            );


            if ($updateStmt) {

                mysqli_stmt_bind_param(
                        $updateStmt,
                        "dsii",
                        $gradeVal,
                        $feedback,
                        $submissionId,
                        $teacherId
                );


                if (mysqli_stmt_execute($updateStmt)) {

                    if (mysqli_stmt_affected_rows($updateStmt) > 0) {

                        $message = "Η βαθμολογία καταχωρήθηκε.";
                        $messageType = "success";

                    } else {

                        /*
                         * 0 affected rows can also happen when the
                         * submitted values are identical, so verify
                         * that the teacher actually owns the submission.
                         */

                        $checkStmt = mysqli_prepare(
                                $conn,
                                "SELECT s.id
                             FROM submissions s
                             JOIN assignments a
                                ON a.id = s.assignment_id
                             JOIN courses c
                                ON c.id = a.course_id
                             WHERE s.id = ?
                             AND c.teacher_id = ?
                             LIMIT 1"
                        );

                        mysqli_stmt_bind_param(
                                $checkStmt,
                                "ii",
                                $submissionId,
                                $teacherId
                        );

                        mysqli_stmt_execute($checkStmt);

                        $checkResult =
                                mysqli_stmt_get_result($checkStmt);


                        if (mysqli_num_rows($checkResult) === 1) {

                            $message = "Η βαθμολογία καταχωρήθηκε.";
                            $messageType = "success";

                        } else {

                            $message = "Δεν έχεις πρόσβαση σε αυτή την υποβολή.";
                            $messageType = "error";
                        }

                        mysqli_stmt_close($checkStmt);
                    }

                } else {

                    $message = "Σφάλμα στη βαθμολόγηση.";
                    $messageType = "error";
                }

                mysqli_stmt_close($updateStmt);

            } else {

                $message = "Σφάλμα στη βαθμολόγηση.";
                $messageType = "error";
            }
        }
    }
}


/* =========================
   GET TEACHER SUBMISSIONS
   ========================= */

$listStmt = mysqli_prepare(
        $conn,
        "SELECT
        s.id AS submission_id,
        u.name AS student_name,
        c.title AS course_title,
        a.title AS assignment_title,
        s.submission_text,
        s.submitted_at,
        s.grade,
        s.feedback
     FROM submissions s
     JOIN assignments a
        ON a.id = s.assignment_id
     JOIN courses c
        ON c.id = a.course_id
     JOIN users u
        ON u.id = s.student_id
     WHERE c.teacher_id = ?
     ORDER BY s.submitted_at DESC"
);

mysqli_stmt_bind_param(
        $listStmt,
        "i",
        $teacherId
);

mysqli_stmt_execute($listStmt);

$result = mysqli_stmt_get_result($listStmt);


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

        <title>Βαθμολόγηση</title>

        <link
                rel="stylesheet"
                href="../../css/styles.css"
        >

    </head>


    <body>

    <main class="wrap">

        <section class="card">

            <h1 class="page-title">
                Βαθμολόγηση Υποβολών
            </h1>


            <?php if ($message !== ""): ?>

                <div class="alert <?= htmlspecialchars($messageType) ?>">

                    <?= htmlspecialchars($message) ?>

                </div>

            <?php endif; ?>


            <?php if (mysqli_num_rows($result) === 0): ?>

                <p>
                    Δεν υπάρχουν υποβολές ακόμη.
                </p>

            <?php else: ?>

                <div class="list">


                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <div class="item">


                            <div class="item-head">

                                <strong>
                                    <?= htmlspecialchars($row["student_name"]) ?>
                                </strong>


                                <div class="item-meta">

                                    Μάθημα:
                                    <?= htmlspecialchars($row["course_title"]) ?>

                                    |

                                    Εργασία:
                                    <?= htmlspecialchars($row["assignment_title"]) ?>

                                    |

                                    Υποβλήθηκε:
                                    <?= htmlspecialchars($row["submitted_at"]) ?>

                                </div>

                            </div>


                            <div class="item-body">

                                <em>Υποβολή:</em>

                                <?= htmlspecialchars($row["submission_text"]) ?>

                            </div>


                            <form
                                    method="post"
                                    class="item-actions"
                            >

                                <input
                                        type="hidden"
                                        name="submission_id"
                                        value="<?= (int)$row["submission_id"] ?>"
                                >


                                <div class="field">

                                    <label>
                                        Βαθμός (0-10)
                                    </label>

                                    <input
                                            type="number"
                                            name="grade"
                                            min="0"
                                            max="10"
                                            step="0.01"
                                            value="<?= htmlspecialchars((string)$row["grade"]) ?>"
                                            required
                                    >

                                </div>


                                <div class="field">

                                    <label>
                                        Σχόλια / Feedback (προαιρετικό)
                                    </label>

                                    <input
                                            type="text"
                                            name="feedback"
                                            value="<?= htmlspecialchars((string)$row["feedback"]) ?>"
                                    >

                                </div>


                                <button
                                        class="btn primary"
                                        type="submit"
                                >

                                    Αποθήκευση Βαθμού

                                </button>

                            </form>

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

mysqli_stmt_close($listStmt);

?>