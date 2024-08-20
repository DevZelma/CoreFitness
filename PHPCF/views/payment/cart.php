<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: /PHPCF/views/userlogin/login.php");
    exit();
}

$membershipType = $_SESSION['membershipType'] ?? "None";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membershipType = test_input($_POST["membershipType"]);
    $startDate = date('Y-m-d'); // Assuming you want to set the start date as the current date
    $NextBill = date('Y-m-d', strtotime('+1 month')); // 
    $endDate = date('Y-m-d', strtotime('+1 year')); // Assuming you want to set the end date as one year from the start date

    if (!empty($membershipType)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "corefitness";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO membership (customerID, membershipType, startDate, EndDate) VALUES (?, ?, ?, ?)");
        // Assuming customerID is stored in $_SESSION['id']
        $stmt->bind_param("isss", $_SESSION['id'], $membershipType, $startDate, $endDate);

        if ($stmt->execute()) {
            $_SESSION['membershipType'] = $membershipType;
            
           
            header("Location: /PHPCF/views/payment/confirm.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="/PHPCF/css/payment.css">
</head>
<body>
<main class="page payment-page">
    <section class="payment-form dark">
        <div class="container">
            <div class="block-heading">
                <h2>Payment</h2>
            </div>

            <form method="post" onsubmit="return validateForm()">
                <div class="products">
                    <h3 class="title">Checkout</h3>
                    <?php if ($_SESSION['membershipType'] == "None"): ?>
                        <div class="form-group">
                            <select id="product-options" class="form-control" name="membershipType" required>
                                <option value="">Select an option</option>
                                <option value="Basic">Basic</option>
                                <option value="Pro">Pro</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                        <div class="total">Total <span class="price"></span></div>
                    <?php else: ?>
                        <br>
                        <div class="alert alert-info">
                            You have already purchased a <?php echo htmlspecialchars($membershipType); ?> membership. Go to <a href="/PHPCF/views/userlogin/account.php">Account</a> if you want to change your membership.
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($membershipType == "None"): ?>
                    <div class="card-details">
                        <h3 class="title">Credit Card Details</h3>
                        <div class="row">
                            <div class="form-group col-sm-7">
                                <label for="card-holder">Card Holder</label>
                                <input id="card-holder" type="text" class="form-control" placeholder="Card Holder" aria-label="Card Holder" required>
                            </div>
                            <div class="form-group col-sm-5">
                                <label for="expiration-date">Expiration Date</label>
                                <div class="input-group expiration-date">
                                    <input type="text" class="form-control" placeholder="MM" aria-label="MM" required>
                                    <span class="date-separator">/</span>
                                    <input type="text" class="form-control" placeholder="YY" aria-label="YY" required>
                                </div>
                            </div>
                            <div class="form-group col-sm-8">
                                <label for="card-number">Card Number</label>
                                <input id="card-number" type="text" class="form-control" placeholder="Card Number" aria-label="Card Number" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cvc">CVC</label>
                                <input id="cvc" type="text" class="form-control" placeholder="CVC" aria-label="CVC" required>
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block btn-red">Proceed</button>
                            </div>
                            <?php endif; ?>
                            <div class="form-group col-sm-12">
                                <button type="button" class="btn btn-primary btn-block btn-red" onClick="window.location='/PHPCF/views/home/index.html';">Cancel</button>
                                <br>
                            </div>
                        </div>
                    </div>

            </form>
        </div>
    </section>
</main>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
document.getElementById('product-options').addEventListener('change', function() {
    var selectedOption = this.value;
    var price = 0;

    // Assign price based on selected option
    switch(selectedOption) {
        case 'Basic':
            price = 1500; // Example price for Basic
            break;
        case 'Pro':
            price = 3000; // Example price for Premium
            break;
        case 'Premium':
            price = 3500; // Example price for Pro
            break;
        default:
            price = 0;
    }

    // Update the total
    document.querySelector('.total .price').textContent = 'R' + price ;
    localStorage.setItem('selectedProductPrice', price);
});

function validateForm() {
    var membershipType = document.getElementById('product-options').value;
    if (membershipType === "") {
        alert("Please select a membership option.");
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('product-options');
    
    // Retrieve the stored value and set the selected option
    const savedOption = localStorage.getItem('selectedMembershipType');
    if (savedOption) {
        selectElement.value = savedOption;
    }

    // Save the selected option to localStorage when it changes
    selectElement.addEventListener('change', function() {
        localStorage.setItem('selectedMembershipType', selectElement.value);
    });
});
</script>
</body>
</html>
