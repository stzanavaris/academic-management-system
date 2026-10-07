<?php

session_start();

require_once "dashboard/config/db.php";


/* =========================
   ALLOW POST REQUESTS ONLY
   ========================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: registration.php");
    exit;
}


/* =========================
   GET FORM DATA
   ========================= */

$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$role = $_POST["role"] ?? "";
$regcode = trim($_POST["regcode"] ?? "");


/* =========================
   VALIDATION
   ========================= */

if (
    $username === "" ||
    $email === "" ||
    $password === "" ||
    $role === "" ||
    $regcode === ""
) {

    header("Location: registration.php?error=empty");
    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: registration.php?error=email");
    exit;
}


if (strlen($password) < 8) {

    header("Location: registration.php?error=password");
    exit;
}


if ($role !== "student" && $role !== "teacher") {

    header("Location: registration.php?error=role");
    exit;
}


/* =========================
   REGISTRATION CODE
   ========================= */

if ($role === "student" && $regcode !== "STUD2025") {

    header("Location: registration.php?error=student_code");
    exit;
}


if ($role === "teacher" && $regcode !== "PROF2025") {

    header("Location: registration.php?error=teacher_code");
    exit;
}


/* =========================
   CHECK EXISTING EMAIL
   ========================= */

$checkStmt = mysqli_prepare(
    $conn,
    "SELECT id
     FROM users
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $checkStmt,
    "s",
    $email
);

mysqli_stmt_execute($checkStmt);

$checkResult = mysqli_stmt_get_result($checkStmt);


if (mysqli_num_rows($checkResult) > 0) {

    mysqli_stmt_close($checkStmt);

    header("Location: registration.php?error=exists");
    exit;
}

mysqli_stmt_close($checkStmt);


/* =========================
   HASH PASSWORD
   ========================= */

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* =========================
   CREATE USER
   ========================= */

$insertStmt = mysqli_prepare(
    $conn,
    "INSERT INTO users
        (name, email, password, role)
     VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $insertStmt,
    "ssss",
    $username,
    $email,
    $passwordHash,
    $role
);


if (mysqli_stmt_execute($insertStmt)) {

    mysqli_stmt_close($insertStmt);

    header("Location: login.php?registered=1");
    exit;

} else {

    mysqli_stmt_close($insertStmt);

    header("Location: registration.php?error=server");
    exit;
}