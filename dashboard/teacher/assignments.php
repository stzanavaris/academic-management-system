<?php
require_once "../../auth/guard.php";
requireRole("teacher");

require_once "../../dashboard/config/db.php";

$teacherId = (int)$_SESSION['user']['id'];
$message = "";
$messageType = "";


/* =========================
   GET TEACHER COURSES
   ========================= */

$coursesSql = "SELECT id, title
               FROM courses
               WHERE teacher_id = $teacherId
               ORDER BY title ASC";

$coursesResult = mysqli_query($conn, $coursesSql);


/* =========================
   CREATE ASSIGNMENT
   ========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $courseId = (int)($_POST["course_id"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $dueDate = trim($_POST["due_date"] ?? "");


    /* Check required fields */

    if ($courseId <= 0 || $title === "") {

        $message = "Επίλεξε μάθημα και βάλε τίτλο εργασίας.";
        $messageType = "error";

    } else {

        /*
         * Make sure the selected course
         * actually belongs to this teacher.
         */

        $checkSql = "SELECT id
                     FROM courses
                     WHERE id = $courseId
                     AND teacher_id = $teacherId";

        $checkResult = mysqli_query($conn, $checkSql);


        if (!$checkResult || mysqli_num_rows($checkResult) === 0) {

            $message = "Δεν έχεις πρόσβαση σε αυτό το μάθημα.";
            $messageType = "error";

        } else {

            $titleEsc = mysqli_real_escape_string($conn, $title);
            $descriptionEsc = mysqli_real_escape_string($conn, $description);

            if ($dueDate !== "") {

                $dueDateEsc =
                        "'" . mysqli_real_escape_string($conn, $dueDate) . "'";

            } else {

                $dueDateEsc = "NULL";
            }


            $insertSql = "
                INSERT INTO assignments
                (course_id, title, description, due_date)

                VALUES
                (
                    $courseId,
                    '$titleEsc',
                    '$descriptionEsc',
                    $dueDateEsc
                )
            ";


            if (mysqli_query($conn, $insertSql)) {

                $message = "Η εργασία ανέβηκε επιτυχώς.";
                $messageType = "success";

            } else {

                $message = "Παρουσιάστηκε σφάλμα κατά την αποθήκευση.";
                $messageType = "error";
            }
        }
    }
}

include "../header.php";
?>

<!doctype html>

<html lang="el">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Ανάρτηση Εργασίας</title>

    <link rel="stylesheet"
          href="../../css/styles.css">

</head>


<body>

<main class="wrap">

    <section class="card form-card">

        <h1 class="page-title">
            Ανάρτηση Εργασίας
        </h1>


        <?php if ($message !== ""): ?>

            <div class="alert <?= htmlspecialchars($messageType) ?>">

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>


        <?php if (!$coursesResult || mysqli_num_rows($coursesResult) === 0): ?>

            <p>
                Δεν υπάρχει μάθημα συνδεδεμένο με τον λογαριασμό σου.
            </p>

            <div class="actions">

                <a class="btn"
                   href="../../dashboard.php">

                    Πίσω

                </a>

            </div>


        <?php else: ?>


            <form method="post">


                <div class="field">

                    <label for="course_id">
                        Μάθημα
                    </label>

                    <select
                            id="course_id"
                            name="course_id"
                            required
                    >

                        <option value="">
                            -- Επίλεξε μάθημα --
                        </option>


                        <?php while ($course = mysqli_fetch_assoc($coursesResult)): ?>

                            <option
                                    value="<?= (int)$course['id'] ?>"
                            >

                                <?= htmlspecialchars($course['title']) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="field">

                    <label for="title">
                        Τίτλος
                    </label>

                    <input
                            id="title"
                            name="title"
                            type="text"
                            required
                    >

                </div>


                <div class="field">

                    <label for="description">
                        Περιγραφή
                    </label>

                    <input
                            id="description"
                            name="description"
                            type="text"
                    >

                </div>


                <div class="field">

                    <label for="due_date">
                        Ημερομηνία Παράδοσης
                    </label>

                    <input
                            id="due_date"
                            type="date"
                            name="due_date"
                    >

                </div>


                <div class="actions">

                    <button
                            class="btn primary"
                            type="submit"
                    >

                        Ανάρτηση

                    </button>


                    <a
                            class="btn"
                            href="../../dashboard.php"
                    >

                        Πίσω

                    </a>

                </div>

            </form>

        <?php endif; ?>

    </section>

</main>

</body>

</html>