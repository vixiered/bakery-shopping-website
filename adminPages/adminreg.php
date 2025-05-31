<?php
session_start();
include '../customer/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['usern'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $birth = $_POST['birth'] ?? '';
    $adress = $_POST['adress'] ?? '';
    $email = $_POST['email'] ?? '';

    // Check if username already exists in person
    $check = $conn->prepare("SELECT IDP FROM person WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['usernameError'] = "Username already taken";
        $_SESSION['old_usern'] = $username;
        $_SESSION['old_password'] = $password;
        $_SESSION['old_phone'] = $phone;
        $_SESSION['old_birth'] = $birth;
        $_SESSION['old_email'] = $email;
        $_SESSION['old_adress'] = $adress;
        header("Location: createadmin.php");
        exit();
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert into person with role 'customer'
            $stmt = $conn->prepare("INSERT INTO person (username, password, phone, birth, adress, email, role) VALUES (?, ?, ?, ?, ?, ?, 'admin')");
            $stmt->bind_param("ssssss", $username, $password, $phone, $birth, $adress, $email);
            
            if ($stmt->execute()) {
                $newUserId = $stmt->insert_id;
                
                // Now insert into customer table
                $stmt2 = $conn->prepare("INSERT INTO admin (IDP,username) VALUES (?, ?)");
                $stmt2->bind_param("is", $newUserId,$username);
                
                if ($stmt2->execute()) {
                    $conn->commit(); // Commit transaction if both inserts succeed
                    $_SESSION['user_id'] = $newUserId;
                    $_SESSION['role'] = 'admin';
                    header("Location: ../customer/profile.php");
                    exit();
                } else {
                    throw new Exception("Error creating admin record: " . $stmt2->error);
                }
            } else {
                throw new Exception("Error creating account: " . $stmt->error);
            }
        } catch (Exception $e) {
            $conn->rollback(); // Rollback if any error occurs
            $_SESSION['formError'] = $e->getMessage();
            header("Location: createadmin.php");
            exit();
        }
    }
} 
?>