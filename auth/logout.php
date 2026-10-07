<?php
session_start();
session_destroy();
header("Location: ../Template.php");
exit;
