<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current values
$stmt = $conn->prepare("SELECT username, email, birth,phone, adress FROM person WHERE IDP = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();

// Use POST values or fallback to current ones
$username = !empty($_POST['username']) ? $_POST['username'] : $current['username'];
$email    = !empty($_POST['email'])    ? $_POST['email']    : $current['email'];
$birth    = !empty($_POST['birth'])    ? $_POST['birth']    : $current['birth'];
$adress   = !empty($_POST['adress'])   ? $_POST['adress']   : $current['adress'];
$phone   = !empty($_POST['phone'])   ? $_POST['phone']   : $current['phone'];


// Update
$stmt = $conn->prepare("UPDATE person SET username=?, email=?, birth=?, adress=?, phone=? WHERE IDP=?");
$stmt->bind_param("sssssi", $username, $email, $birth, $adress,$phone, $user_id);
$stmt->execute();

header("Location: profile.php");
exit();
?>
