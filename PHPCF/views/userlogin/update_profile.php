<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs and sanitize them
    $name = test_input($_POST["name"]);
    $surname = test_input($_POST["surname"]);
    $email = test_input($_POST["email"]);
    $phone = test_input($_POST["phone"]);
    $gender = test_input($_POST["gender"]);
    $address = test_input($_POST["address"]);

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "corefitness";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the user's information in the database using prepared statements
    $stmt = $conn->prepare("UPDATE customer_details SET customerName = ?, customerSurname = ?, customerEmail = ?, customerPhone = ?, customerGender = ?, customerAddress = ? WHERE userID = ?");
    $stmt->bind_param("ssssssi", $name, $surname, $email, $phone, $gender, $address, $_SESSION['id']);

    if ($stmt->execute()) {
        // Update session variables with the new data
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['gender'] = $gender;
        $_SESSION['address'] = $address;

        // Redirect back to the profile page
        header("Location: account.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    header("Location: account.php");
    exit();
}
?>
