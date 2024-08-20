<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link rel="stylesheet" type="text/css" href="/PHPCF/css/user.css">
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
    <div class="bg-image"></div>

    <div class="center">
        <h1>Login</h1>
        <form action="functions.php" method="post">
            <div class="txt_field">
                <input type="text" name="username" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_field">
                <input type="password" name="pwd" required>
                <span></span>
                <label>Password</label>
            </div>
            <input class="button" type="submit" value="Login"/>
            <input class="button" type="button" name="cancel" value="Cancel" onClick="window.location='/PHPCF/views/home/index.html';" /><br>
            <br>
            <?php
            if (isset($_GET['login_error'])) {
                if ($_GET['login_error'] == 1) {
                    echo '<span style="color: red;">Invalid username or password.</span>';
                }
            }
            ?>
            <p class="users_signup">New To CoreFitness? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>
