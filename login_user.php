<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['password'], $_POST['role'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $selected_role = $_POST['role'];

        $stmt = $conn->prepare("SELECT id, password, role FROM login_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password) && $role === $selected_role) {
                $_SESSION['login_user'] = $email;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'manager') {
                    header("Location: manager_dashboard.php");
                } else {
                    header("Location: table_selection copy.php");
                }
                exit();
            } else {
                echo "<script>alert('Invalid email, password, or role'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password'); window.location.href='login.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('All fields are required'); window.location.href='login.html';</script>";
    }
}

$conn->close();
?>
