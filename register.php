<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $name = trim($_POST['nam']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Check if phone is 10 digits
    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "Phone number must be 10 digits.";
        exit;
    }

    // Check if passwords match
    if ($password !== $repassword) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User already exists. Please try again.";
    } else {
        // Insert user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (email, nam, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $name, $phone, $hashedPassword);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
