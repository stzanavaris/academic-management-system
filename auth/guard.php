<?php
session_start();

function requireRole($role) {
    if (!isset($_SESSION['user'])) {
        header("Location: /CN5006_1/Template.php");
        exit;
    }

    if ($_SESSION['user']['role'] !== $role) {
        header("Location: /CN5006_1/forbidden.php");
        exit;
    }
}
