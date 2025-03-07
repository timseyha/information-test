<?php
include('config/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    // $email = $_POST['email'];
    // $password = $_POST['password'];
    // $confirm_password = $_POST['confirm_password'];
    // $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    // $check_email->bind_param("s", $email);
    // $check_email->execute();
    // $check_email->store_result();

    // if ($password == $confirm_password) {
    //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //     $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";


    //     if ($conn->query($sql) === TRUE) {
    //         echo '<script type="text/javascript">alert("Data Saved Successfully");</script>';
    //     } else {
    //         echo "Error: " . $sql . "<br>" . $conn->error;
    //     }
    // } else {
    //     echo "Passwords do not match";
    // }
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password == $confirm_password) {
        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            // echo "Error: Email already exists. Please use another email.";
            echo '<script type="text/javascript">alert("Error: Email already exists. Please use another email");</script>';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                echo '<script type="text/javascript">alert("Data Saved Successfully");</script>';
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else {
        echo "Passwords do not match";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo '<script type="text/javascript">alert("Data Saved Successfully");</script>';
            header("Location: index.php");
        } else {
            echo '<script type="text/javascript">alert("Error: Please try again");</script>';
        }
    } else {
        echo "No user found with this email";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login & Registration Form</title>
    <!---Custom CSS File--->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
</head>

<body>
    <div class="container">
        <input type="checkbox" id="check">
        <div class="login form">
            <header>Login</header>
            <form action="login.php" method="POST">
                <input type="text" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Enter your password" required>
                <a href="#">Forgot password?</a>
                <input type="submit" class="button" name="login" value="Login">
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <label for="check">Signup</label>
                </span>
            </div>
        </div>
        <div class="registration form">
            <header>Signup</header>
            <form action="login.php" method="POST">
                <input type="text" name="email" placeholder="Enter your email" required>
                <input type="password" name="password" placeholder="Create a password" required>
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                <input type="submit" class="button" name="signup" value="Signup">
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <label for="check">Login</label>
                </span>
            </div>
        </div>
    </div>
</body>

</html>