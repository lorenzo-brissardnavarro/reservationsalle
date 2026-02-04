<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

// if (!isset($_SESSION['id'])) {
//     header("Location: ../index.php");
//     exit;
// }

if(isset($_GET['id'])){
    $creneau = $_GET['id'];
}




?>
