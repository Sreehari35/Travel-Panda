<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['email'];
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
