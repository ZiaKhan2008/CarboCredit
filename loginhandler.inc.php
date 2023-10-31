<?php
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $address = $_POST["address"]; // Corrected the way to access 'address' from POST data.

        require_once "dbh.inc.php"; // Include the file with the database connection logic.

        // Check if the user with the given email already exists.
        $checkQuery = "SELECT publickey FROM userids WHERE email = ? LIMIT 1";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([$email]);
        $existingAddress = $checkStmt->fetchColumn();

        if ($existingAddress !== false) {
            // User with the given email already exists.
            if (!empty(trim($address))) {
                header("Location: ../payment.html");

            }else {

                // Address is not empty; update the address.
                $updateQuery = "UPDATE userids SET publickey = ? WHERE email = ?;";
                $updateStmt = $pdo->prepare($updateQuery);

                if ($updateStmt->execute([$address, $email])) {
                    // The update was successful, redirect to a success page.
                    header("Location: ../payment.html");
                    exit();
                } else {
                    // Handle the case where the update fails, display an error message, or log the error.
                    header("Location: ../payment.html");
                    exit();
                }
            }
            // If the address is empty, do nothing and proceed to the success page.
            header("Location: ../payment.html");
            exit();
        } else {
            // User with the given email doesn't exist; proceed with registration.

            if (!empty(trim($address))) {
                // Address is not empty; proceed with registration.
                $insertQuery = "INSERT INTO userids (username, pwd, email, publickey) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($insertQuery);

                // Hash the password securely using password_hash.
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt->execute([$username, $hashedPassword, $email, $address]);

                // Provide feedback to the user and redirect to a success page.
                header("Location: ../payment.html");
                exit();
            } else {
                // Address is empty; handle this case as needed (e.g., display an error message).

                // You might want to display an error message here because the address is empty for a new user.
                // Redirect to an error page or display an error message.
                header("Location: ../error.html");
                exit();
            }
        }
    } else {
        // Handle non-POST requests by displaying an error message.
        header("Location: ../payment.html");
        exit();
    }
} catch (PDOException $e) {
    // Handle database errors, log the error, and display a user-friendly message.
    error_log("Database error: " . $e->getMessage());
    header("Location: ../payment.html");
    exit();
}
?>
