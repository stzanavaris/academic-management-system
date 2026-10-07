<?php

session_start();

require_once "../dashboard/config/db.php";


/* =========================
   GET LOGIN DATA
   ========================= */

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";


if ($email === "" || $password === "") {

    header("Location: ../login.php?error=1");
    exit;
}


/* =========================
   FIND USER
   ========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, password, role
     FROM users
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


/* =========================
   CHECK PASSWORD
   ========================= */

if ($user = mysqli_fetch_assoc($result)) {

    $storedPassword = $user["password"];

    /*
     * New accounts use password_hash().
     *
     * Old accounts may still contain plain-text
     * passwords, so we temporarily support both.
     */

    $isHashed = password_get_info($storedPassword)["algo"] !== null;

    $passwordCorrect = false;


    if ($isHashed) {

        $passwordCorrect =
            password_verify($password, $storedPassword);

    } else {

        $passwordCorrect =
            hash_equals($storedPassword, $password);


        /*
         * Automatically upgrade an old plain-text
         * password after a successful login.
         */

        if ($passwordCorrect) {

            $newHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $updateStmt = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET password = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $updateStmt,
                "si",
                $newHash,
                $user["id"]
            );

            mysqli_stmt_execute($updateStmt);

            mysqli_stmt_close($updateStmt);
        }
    }


    if ($passwordCorrect) {

        session_regenerate_id(true);

        $_SESSION["user"] = [
            "id" => $user["id"],
            "name" => $user["name"],
            "role" => $user["role"]
        ];

        mysqli_stmt_close($stmt);

        header("Location: ../dashboard.php");
        exit;
    }
}


mysqli_stmt_close($stmt);

header("Location: ../login.php?error=1");
exit;