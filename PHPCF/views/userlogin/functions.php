<?php
session_start();

if (isset($_POST['username']) && isset($_POST['pwd'])) {
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    include "connection.php";

    try {
        $sql = "SELECT * FROM users WHERE UserName=:username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pwd === $user['Password']) {
                $_SESSION['id'] = $user['UserID'];
                $_SESSION['username'] = $user['UserName'];
                $userID = $user['UserID'];

                $sql2 = "SELECT customer_details.customerName, customer_details.customerSurname, 
                customer_details.customerEmail, customer_details.customerPhone, 
                customer_details.customerGender, customer_details.customerAddress
                FROM customer_details 
                INNER JOIN users ON customer_details.userID = users.userID 
                WHERE users.userID = :userID";

                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([':userID' => $userID]);

                $row = $stmt2->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $_SESSION['name'] = $row['customerName'];
                    $_SESSION['surname'] = $row['customerSurname'];
                    $_SESSION['email'] = $row['customerEmail'];
                    $_SESSION['phone'] = $row['customerPhone'];
                    $_SESSION['gender'] = $row['customerGender'];
                    $_SESSION['address'] = $row['customerAddress'];

                    try {
                        $sql1 = "SELECT CustomerID FROM customer_details WHERE userID = :userID";
                        $stmt1 = $pdo->prepare($sql1);
                        $stmt1->execute([':userID' => $userID]);
                        $customer = $stmt1->fetch(PDO::FETCH_ASSOC);

                        if ($customer) {
                            $customerID = $customer['CustomerID'];

                            $sql2 = "SELECT MembershipType, StartDate FROM membership WHERE CustomerID = :customerID";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([':customerID' => $customerID]);
                            $membership = $stmt2->fetch(PDO::FETCH_ASSOC);

                            if ($membership) {
                                $membershipType = $membership['MembershipType'];
                                $startDate = $membership['StartDate'];

                                // Calculate next billing date
                                $currentDate = new DateTime();
                                $startDay = (new DateTime($startDate))->format('d');
                                $currentDay = $currentDate->format('d');

                                if ($startDay <= $currentDay) {
                                    $nextBillingDate = new DateTime($currentDate->format('Y-m-' . $startDay));
                                    $nextBillingDate->modify('+1 month');
                                } else {
                                    $nextBillingDate = new DateTime($currentDate->format('Y-m-' . $startDay));
                                }

                                if ($nextBillingDate == $currentDate) {
                                    // Handle current billing being today
                                    $nextBillingDate->modify('+1 month');
                                }

                                $_SESSION['nextBillingDate'] = $nextBillingDate->format('Y-m-d');
                            } else {
                                $membershipType = "None";
                                $_SESSION['nextBillingDate'] = "N/A";
                            }
                        } else {
                            $membershipType = "None";
                            $_SESSION['nextBillingDate'] = "N/A";
                        }

                        $_SESSION['membershipType'] = $membershipType;

                        header("Location: /PHPCF/views/userlogin/account.php");
                        exit();
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    header("Location: login.php?login_error=1");
                    exit();
                }
            } else {
                header("Location: login.php?login_error=1");
                exit();
            }
        } else {
            header("Location: login.php?login_error=1");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: login.php?login_error=1");
    exit();
}
?>
