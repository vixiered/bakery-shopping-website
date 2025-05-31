<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['usern'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT IDP, password, role FROM person WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['IDP'];
            $_SESSION['role'] = $row['role'];  // 🔥 this line is critical

            header("Location: profile.php");
            exit();
        } else {
            $_SESSION['login_error'] = "❌ Incorrect password.";
        }
    } else {
        $_SESSION['login_error'] = "❌ No user with that username.";
    }

    header("Location: loginhtml.php");
    exit();
}
?>
