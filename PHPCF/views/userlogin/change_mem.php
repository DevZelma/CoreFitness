<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: /PHPCF/views/userlogin/login.php");
    exit();
}

$membershipType = isset($_SESSION['membershipType']) ? $_SESSION['membershipType'] : '';

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"])) {

       

        $action = test_input($_POST["action"]);
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "corefitness";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($action == "change") {
            $membershipType = test_input($_POST["membershipType"]);

            if (!empty($membershipType)) {
                $stmt = $conn->prepare("UPDATE membership SET membershipType = ? WHERE customerID = ?");
                $stmt->bind_param("si", $membershipType, $_SESSION['id']);

            

                if ($stmt->execute()) {
                    $_SESSION['membershipType'] = $membershipType;
                    header("Location: /PHPCF/views/userlogin/account.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            }
        } elseif ($action == "delete") {
            $stmt = $conn->prepare("DELETE FROM membership WHERE customerID = ?");
            $stmt->bind_param("i", $_SESSION['id']);

            $_SESSION['membershipType'] = 'None';
            $_SESSION['nextBillingDate'] = 'N/A';

            if ($stmt->execute()) {
                $_SESSION['membershipType'] = "None";
                header("Location: /PHPCF/views/userlogin/account.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

$action = isset($_GET['action']) ? test_input($_GET['action']) : '';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Membership</title>
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
        <div class="block-heading"></div>
        <form method="post">
          <div class="products">
            <h3 class="title">
              <?php
              if ($action == "change") {
                echo "Change Membership";
              } elseif ($action == "delete") {
                echo "Cancel Membership";
              } else {
                echo "Membership";
              }
              ?>
            </h3>
            <div class="card-details">
              <?php if ($action == "change") { ?>
                <div class="form-group">
                  <label for="product-options">Choose an option</label>
                  <select id="product-options" class="form-control" name="membershipType">
                      <option value="Basic">Basic</option>
                      <option value="Pro">Pro</option>
                      <option value="Premium">Premium</option>
                  </select>
                </div>
              <?php } elseif ($action == "delete") { ?>
                <p>Are you sure you want to cancel your membership?</p>
              <?php } ?>
              <input type="hidden" name="action" value="<?php echo $action; ?>">
              <div class="form-group col-sm-12">
                <button type="submit" class="btn btn-primary btn-block btn-red">
                  <?php echo $action == "change" ? "Proceed" : "Confirm"; ?>
                </button>
              </div>
              <div class="form-group col-sm-12">
                <button type="button" class="btn btn-primary btn-block btn-red" onClick="window.location='/PHPCF/views/userLogin/account.php';">Back</button>
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
  <script type="text/javascript">
    var membershipType = "<?php echo $membershipType; ?>";
    document.addEventListener('DOMContentLoaded', function() {
      var selectElement = document.getElementById('product-options');
      if (selectElement) {
        var options = selectElement.options;
        for (var i = options.length - 1; i >= 0; i--) {
          if (options[i].value === membershipType) {
            selectElement.remove(i);
          }
        }
      }
    });
  </script>
</body>
</html>
