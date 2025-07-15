<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.location.href='signup.html';</script>";
        exit();
    }

    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[\W_]/', $password)
    ) {
        echo "<script>alert('Password must be at least 8 characters long, contain one uppercase letter, and one special character.'); window.location.href='signup.html';</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM login_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered'); window.location.href='signup.html';</script>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO login_user (email, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! You can now log in.'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Signup failed. Try again.'); window.location.href='signup.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
