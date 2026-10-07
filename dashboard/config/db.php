<?php
$conn = mysqli_connect("localhost", "root", "", "university_db");

if (!$conn) {
    die("Αποτυχία σύνδεσης με βάση");
}
