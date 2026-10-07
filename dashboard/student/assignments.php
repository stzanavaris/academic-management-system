<?php

require_once "../../auth/guard.php";
requireRole("student");

require_once "../../dashboard/config/db.php";

$studentId = (int)$_SESSION['user']['id'];

$message = "";
$messageType = "";


/* =========================
   SUBMIT / UPDATE ASSIGNMENT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $assignmentId = (int)($_POST["assignment_id"] ?? 0);
    $text = trim($_POST["submission_text"] ?? "");


    /* Basic validation */

    if ($assignmentId <= 0 || $text === "") {

        $message = "Συμπλήρωσε κείμενο υποβολής.";
        $messageType = "error";

    } else {

        /*
         * Make sure the assignment belongs to a course
         * in which this student is actually enrolled.
         */

        $checkStmt = mysqli_prepare(
                $conn,
                "SELECT a.id
             FROM assignments a
             JOIN enrollments e
                ON e.course_id = a.course_id
             WHERE a.id = ?
             AND e.student_id = ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
                $checkStmt,
                "ii",
                $assignmentId,
                $studentId
        );

        mysqli_stmt_execute($checkStmt);

        $checkResult = mysqli_stmt_get_result($checkStmt);


        if (mysqli_num_rows($checkResult) === 0) {

            $message = "Δεν έχεις πρόσβαση σε αυτή την εργασία.";
            $messageType = "error";

        } else {

            /*
             * Check if the student already has
             * a submission for this assignment.
             */

            $existingStmt = mysqli_prepare(
                    $conn,
                    "SELECT id
                 FROM submissions
                 WHERE assignment_id = ?
                 AND student_id = ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                    $existingStmt,
                    "ii",
                    $assignmentId,
                    $studentId
            );

            mysqli_stmt_execute($existingStmt);

            $existingResult =
                    mysqli_stmt_get_result($existingStmt);


            /* =========================
               UPDATE EXISTING SUBMISSION
               ========================= */

            if (mysqli_num_rows($existingResult) > 0) {

                $submission =
                        mysqli_fetch_assoc($existingResult);

                $submissionId =
                        (int)$submission["id"];


                $updateStmt = mysqli_prepare(
                        $conn,
                        "UPDATE submissions
                     SET
                        submission_text = ?,
                        submitted_at = NOW(),
                        grade = NULL,
                        feedback = NULL,
                        graded_at = NULL
                     WHERE id = ?
                     AND student_id = ?"
                );

                mysqli_stmt_bind_param(
                        $updateStmt,
                        "sii",
                        $text,
                        $submissionId,
                        $studentId
                );


                if (mysqli_stmt_execute($updateStmt)) {

                    $message = "Η υποβολή ενημερώθηκε επιτυχώς.";
                    $messageType = "success";

                } else {

                    $message = "Σφάλμα στην ενημέρωση της υποβολής.";
                    $messageType = "error";
                }

                mysqli_stmt_close($updateStmt);


                /* =========================
                   CREATE NEW SUBMISSION
                   ========================= */

            } else {

                $insertStmt = mysqli_prepare(
                        $conn,
                        "INSERT INTO submissions
                        (
                            assignment_id,
                            student_id,
                            submission_text,
                            submitted_at
                        )
                     VALUES (?, ?, ?, NOW())"
                );

                mysqli_stmt_bind_param(
                        $insertStmt,
                        "iis",
                        $assignmentId,
                        $studentId,
                        $text
                );


                if (mysqli_stmt_execute($insertStmt)) {

                    $message = "Η υποβολή καταχωρήθηκε επιτυχώς.";
                    $messageType = "success";

                } else {

                    $message = "Σφάλμα στην υποβολή.";
                    $messageType = "error";
                }

                mysqli_stmt_close($insertStmt);
            }

            mysqli_stmt_close($existingStmt);
        }

        mysqli_stmt_close($checkStmt);
    }
}


/* =========================
   GET STUDENT ASSIGNMENTS
   ========================= */

$listStmt = mysqli_prepare(
        $conn,
        "SELECT
        a.id,
        a.title,
        a.description,
        a.due_date,
        c.title AS course_title,

        s.id AS submission_id,
        s.submission_text,
        s.submitted_at,
        s.grade,
        s.feedback

     FROM assignments a

     JOIN courses c
        ON c.id = a.course_id

     JOIN enrollments e
        ON e.course_id = c.id

     LEFT JOIN submissions s
        ON s.assignment_id = a.id
        AND s.student_id = ?

     WHERE e.student_id = ?

     ORDER BY a.created_at DESC"
);

mysqli_stmt_bind_param(
        $listStmt,
        "ii",
        $studentId,
        $studentId
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

        <title>Εργασίες</title>

        <link
                rel="stylesheet"
                href="../../css/styles.css"
        >

    </head>


    <body>

    <main class="wrap">

        <section class="card">

            <h1 class="page-title">
                Εργασίες Φοιτητή
            </h1>


            <?php if ($message !== ""): ?>

                <div class="alert <?= htmlspecialchars($messageType) ?>">

                    <?= htmlspecialchars($message) ?>

                </div>

            <?php endif; ?>


            <?php if (mysqli_num_rows($result) === 0): ?>

                <p>
                    Δεν υπάρχουν εργασίες ακόμη.
                </p>

            <?php else: ?>

                <div class="list">


                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <div class="item">


                            <div class="item-head">

                                <strong>
                                    <?= htmlspecialchars($row["title"]) ?>
                                </strong>


                                <div class="item-meta">

                                    Μάθημα:
                                    <?= htmlspecialchars($row["course_title"]) ?>


                                    <?php if ($row["due_date"]): ?>

                                        |
                                        Προθεσμία:
                                        <?= htmlspecialchars($row["due_date"]) ?>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <?php if ($row["description"]): ?>

                                <div class="item-body">

                                    <?= htmlspecialchars($row["description"]) ?>

                                </div>

                            <?php endif; ?>


                            <?php if ($row["submission_id"]): ?>

                                <div class="item-meta">

                                    Τελευταία υποβολή:
                                    <?= htmlspecialchars($row["submitted_at"]) ?>

                                </div>

                            <?php endif; ?>


                            <form
                                    method="post"
                                    class="item-actions"
                            >

                                <input
                                        type="hidden"
                                        name="assignment_id"
                                        value="<?= (int)$row["id"] ?>"
                                >


                                <div class="field">

                                    <label>
                                        Υποβολή (κείμενο)
                                    </label>

                                    <input
                                            type="text"
                                            name="submission_text"
                                            value="<?= htmlspecialchars($row["submission_text"] ?? "") ?>"
                                            placeholder="Γράψε την υποβολή σου εδώ..."
                                            required
                                    >

                                </div>


                                <button
                                        class="btn primary"
                                        type="submit"
                                >

                                    <?php if ($row["submission_id"]): ?>

                                        Ενημέρωση Υποβολής

                                    <?php else: ?>

                                        Υποβολή

                                    <?php endif; ?>

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