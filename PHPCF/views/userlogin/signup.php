<?php
session_start();

$nameErr = $surnameErr = $emailErr = $genderErr = $addressErr = $contactErr = $usernameErr = $passwordErr = "";
$name = $surname = $email = $gender = $address = $contact = $uname = $upassword = "";

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = test_input($_POST["name"]);
    $surname = test_input($_POST["surname"]);
    $uname = test_input($_POST["uname"]);
    $upassword = test_input($_POST["upassword"]);
    $email = test_input($_POST["email"]);
    $contact = test_input($_POST["contact"]);
    $gender = test_input($_POST["gender"]);
    $address = test_input($_POST["address"]);

    // Validate Name
    if (empty($name)) {
        $nameErr = "Please enter your name";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $nameErr = "Only letters and white space allowed";
    }

    // Validate Surname
    if (empty($surname)) {
        $surnameErr = "Please enter your surname";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $surname)) {
        $surnameErr = "Only letters and white space allowed";
    }

    // Validate Username
    if (empty($uname)) {
        $usernameErr = "Please enter your username";
    }

    // Validate Password
    if (empty($upassword)) {
        $passwordErr = "Please enter your password";
    }

    // Validate Email
    if (empty($email)) {
        $emailErr = "Please enter your email address";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    // Validate Contact
    if (empty($contact)) {
        $contactErr = "Please enter your phone number";
    } elseif (!preg_match("/^\d{10}$/", $contact)) {
        $contactErr = "Please enter a valid phone number (e.g., 012 3456789)";
    }

    // Validate Gender
    if (empty($gender)) {
        $genderErr = "Gender is required";
    }

    // Validate Address
    if (empty($address)) {
        $addressErr = "Please enter your address";
    }

    if (empty($nameErr) && empty($surnameErr) && empty($usernameErr) && empty($passwordErr) && empty($emailErr) && empty($contactErr) && empty($genderErr) && empty($addressErr)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "corefitness";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);


        }


        // Check if username already exists
        $check_stmt = $conn->prepare("SELECT UserID FROM users WHERE UserName = ?");
        $check_stmt->bind_param("s", $uname);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $usernameErr = "Username already exists. Please choose another one.";
        } else {
            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (UserName, Password) VALUES (?, ?)");
            $hashed_password = $upassword;  // Encrypt the password
            $stmt->bind_param("ss", $uname, $hashed_password);
            $stmt->execute();

            // Get the newly inserted user's ID
        $userID = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO customer_details (CustomerName, CustomerSurname, CustomerPhone, CustomerEmail, CustomerAddress, CustomerGender, UserID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $surname, $contact, $email, $address, $gender, $userID);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Fitness Demo Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="widescreen.css" media="screen and (min-width: 900px)">
    <link rel="stylesheet" href="smallscreen.css" media="screen and (max-width: 600px)">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #141414;
            height: 100%;
        }

        .form-container {
            width: 55%;
            border-radius: 5px;
            background-color: black;
            padding: 20px;
            margin: 0 auto;
        }
        .form-container h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .input-field {
            color: white;
        }
        .input-field input, .input-field textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .input-field label {
            display: block;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 14px;
        }
        .input-field input[type="radio"] {
            width: auto;
        }
        .button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f00;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #c00;
        }
        .error {
            color: red;
            font-size: 0.8em;
        }
        .form-container a {
            color: #f00;
            text-decoration: none;
            text-align: center;
            display: block;
            margin-top: 10px;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            let valid = true;

            // Validate Name
            const name = document.forms["registrationForm"]["name"].value;
            if (name == "" || !/^[a-zA-Z ]*$/.test(name)) {
                document.getElementById("nameErr").innerText = "Please enter a valid name.";
                valid = false;
            } else {
                document.getElementById("nameErr").innerText = "";
            }

            // Validate Surname
            const surname = document.forms["registrationForm"]["surname"].value;
            if (surname == "" || !/^[a-zA-Z ]*$/.test(surname)) {
                document.getElementById("surnameErr").innerText = "Please enter a valid surname.";
                valid = false;
            } else {
                document.getElementById("surnameErr").innerText = "";
            }

            // Validate Username
            const uname = document.forms["registrationForm"]["uname"].value;
            if (uname == "") {
                document.getElementById("usernameErr").innerText = "Please enter your username.";
                valid = false;
            } else {
                document.getElementById("usernameErr").innerText = "";
            }

            // Validate Password
            const upassword = document.forms["registrationForm"]["upassword"].value;
            if (upassword == "") {
                document.getElementById("passwordErr").innerText = "Please enter your password.";
                valid = false;
            } else {
                document.getElementById("passwordErr").innerText = "";
            }

            // Validate Email
            const email = document.forms["registrationForm"]["email"].value;
            if (email == "" || !/\S+@\S+\.\S+/.test(email)) {
                document.getElementById("emailErr").innerText = "Please enter a valid email.";
                valid = false;
            } else {
                document.getElementById("emailErr").innerText = "";
            }

            // Validate Contact
            const contact = document.forms["registrationForm"]["contact"].value;
            if (contact == "" || !/^\d{10}$/.test(contact)) {
                document.getElementById("contactErr").innerText = "Please enter a valid phone number (e.g., 0123456789).";
                valid = false;
            } else {
                document.getElementById("contactErr").innerText = "";
            }

            // Validate Gender
            if (!document.forms["registrationForm"]["gender"].value) {
                document.getElementById("genderErr").innerText = "Gender is required.";
                valid = false;
            } else {
                document.getElementById("genderErr").innerText = "";
            }

            // Validate Address
            const address = document.forms["registrationForm"]["address"].value;
            if (address == "") {
                document.getElementById("addressErr").innerText = "Please enter your address.";
                valid = false;
            } else {
                document.getElementById("addressErr").innerText = "";
            }

            return valid;
        }
    </script>
</head>
<body>
<div class="form-container">
    <h1>Register:</h1>
    <form name="registrationForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm();">

        <div class="input-field">
            <label>Full Name:</label>
            <input type="text" name="name" placeholder="Full Name" value="<?php echo $name; ?>">
            <span id="nameErr" class="error"><?php echo $nameErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>Surname:</label>
            <input type="text" name="surname" placeholder="Surname" value="<?php echo $surname; ?>">
            <span id="surnameErr" class="error"><?php echo $surnameErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>User Name:</label>
            <input type="text" name="uname" placeholder="User Name" value="<?php echo $uname; ?>">
            <span id="usernameErr" class="error"><?php echo $usernameErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>New Password:</label>
            <input type="password" name="upassword" placeholder="Password" value="<?php echo $upassword; ?>">
            <span id="passwordErr" class="error"><?php echo $passwordErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>E-mail:</label>
            <input type="text" name="email" placeholder="example@email.com" value="<?php echo $email; ?>">
            <span id="emailErr" class="error"><?php echo $emailErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>Mobile Number:</label>
            <input type="text" name="contact" placeholder="012-3456789" value="<?php echo $contact; ?>">
            <span id="contactErr" class="error"><?php echo $contactErr; ?></span><br><br>
        </div>

        <div class="input-field">
            <label>Gender:</label>
            <input type="radio" name="gender" value="Male" <?php if (isset($gender) && $gender == "Male") echo "checked"; ?>>Male
            <input type="radio" name="gender" value="Female" <?php if (isset($gender) && $gender == "Female") echo "checked"; ?>>Female
            <span id="genderErr" class="error"><?php echo $genderErr; ?></span>
        </div>

        <div class="input-field">
            <label>Address:</label>
            <input type="text" name="address" placeholder="Address" value="<?php echo $address; ?>">
            <span id="addressErr" class="error"><?php echo $addressErr; ?></span>
        </div>

        <input class="button" type="submit" name="RegisterButton" value="Register">
        <input class="button" type="button" name="cancel" value="Cancel" onClick="window.location='login.php';">
    </form>
</div>

</body>
</html>
