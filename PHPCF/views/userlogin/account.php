<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>

    <link rel="stylesheet" type="text/css" href="/PHPCF/css/user.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a7136a10c0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" media="screen and (min-width: 900px)" href="widescreen.css">
    <link rel="stylesheet" media="screen and (max-width: 600px)" href="smallscreen.css">
</head>
<body>

    <div class="container">
        <div class="profile-sidebar">
            <img src="/PHPCF/images/user-img.png"/>
            <h2 class="profile-name"> <?php echo $_SESSION['name']; ?> </h2>
            <p class="profile-contact"><?php echo $_SESSION['username']; ?></p>
            <button class="profile-btn" onclick="homePage()">Home</button>
            <button class="edit-btn" onclick="enableEdit()">Edit Profile</button>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
        
        <div class="profile-details">
            <h3>Profile Information</h3>
            <form id="editProfileForm" method="post" action="update_profile.php" onsubmit="return validateForm()">
                <p><strong>Name:</strong> <span id="name"><?php echo $_SESSION['name']; ?></span> <input type="text" name="name" value="<?php echo $_SESSION['name']; ?>" style="display:none;" required></p>
                <p><strong>Surname:</strong> <span id="surname"><?php echo $_SESSION['surname']; ?></span> <input type="text" name="surname" value="<?php echo $_SESSION['surname']; ?>" style="display:none;" required></p>
                <p><strong>Email:</strong> <span id="email"><?php echo $_SESSION['email']; ?></span> <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" style="display:none;" required></p>
                <p><strong>Contact:</strong> <span id="phone"><?php echo $_SESSION['phone']; ?></span> <input type="text" name="phone" value="<?php echo $_SESSION['phone']; ?>" style="display:none;" required pattern="\d{10}"></p>
                <p><strong>Gender:</strong> <span id="gender"><?php echo $_SESSION['gender']; ?></span> <input type="text" name="gender" value="<?php echo $_SESSION['gender']; ?>" style="display:none;" required></p>
                <p><strong>Address:</strong> <span id="address"><?php echo $_SESSION['address']; ?></span> <input type="text" name="address" value="<?php echo $_SESSION['address']; ?>" style="display:none;" required></p>
                <button class="w3-button w3-black w3-round-xxlarge" type="submit" id="saveBtn" style="display:none;">Save</button>
            </form>
            
            <h3>Membership Details</h3>
            <p><strong>Membership:</strong> <?php echo $_SESSION['membershipType']; ?></p>
            <p><strong>Next Billing:</strong> <?php echo $_SESSION['nextBillingDate']; ?></p>

            <?php
            if ($_SESSION['membershipType'] === 'None') {
                echo '<p class="users_signup"><a href="/PHPCF/views/payment/cart.php">Buy Membership</a></p>';
            } else {
                echo '<p class="users_signup"><a href="change_mem.php?action=change">Change Membership</a></p>';
                echo '<p class="users_signup"><a href="change_mem.php?action=delete">Cancel Membership</a></p>';
            }
            ?>
        </div>
    </div>

    <script>
    function logout() {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = 'logout.php';
        document.body.appendChild(form);
        form.submit();
    }

    function homePage() {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '/PHPCF/views/home/index.html';
        document.body.appendChild(form);
        form.submit();
    }

    function enableEdit() {
        const spans = document.querySelectorAll('#editProfileForm span');
        const inputs = document.querySelectorAll('#editProfileForm input[type="text"], #editProfileForm input[type="email"]');
        spans.forEach(span => span.style.display = 'none');
        inputs.forEach(input => input.style.display = 'inline');
        document.getElementById('saveBtn').style.display = 'inline';
    }

    function saveChanges() {
        document.getElementById('editProfileForm').submit();
    }

    function validateForm() {
        const phone = document.querySelector('input[name="phone"]').value;
        const phonePattern = /^\d{10}$/;
        if (!phonePattern.test(phone)) {
            alert("Please enter a valid 10-digit phone number.");
            return false;
        }


        return true;
    }

    var savedPrice = localStorage.getItem('selectedProductPrice');
    if (savedPrice) {
        document.querySelector('.total .price').textContent = 'R' + savedPrice;
    }
    </script>
</body>
</html>
