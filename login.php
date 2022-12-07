<?php

session_start();
include("connection.php");

$errorEmptyId = false;
$emptyIdMessage = "Bitte geben Sie Ihre ID an.";

$errorIncorrectId = false;
$incorrectIdMessage = "Login fehlgeschlagen, Ihre ID ist fehlerhaft.";

if (isset($_POST["submit"])) {
    if ($_POST["user_id"] === "") {
        $errorEmptyId = true;
    } else {
        $errorEmptyId = false;
        $user_id = $_POST["user_id"];

        $stmt = $connection->prepare("SELECT * FROM user WHERE user_id=:user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || str_contains($user_id, " ")) {
            $errorIncorrectId = true;
        } else {
            $_SESSION["user_id"] = $user_id;
            header("Location: appointment.php");
        }
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles -->
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="shortcut icon" href="assets/medical-appointment.png" type="image/x-icon">

    <title>Terminbuchung | Login</title>
</head>

<body>
    <form action="login.php" method="POST">
        <div class="login">
            <div class="wrapper">
                <h1>Login</h1>
                <div class="login-container">
                    <p class="error"><?php if ($errorEmptyId) echo $emptyIdMessage;
                                        elseif ($errorIncorrectId) echo $incorrectIdMessage; ?></p>
                    <div class="login-field" class="date-container">
                        <input type="text" class="input-login" placeholder="Tragen Sie Ihre ID ein" name="user_id" autocomplete="off">
                        <button name="submit">Login</button>
                    </div>
                </div>
            </div>
        </div>
        <a href="index.php" class="home-btn">Home</a>
    </form>
</body>

</html>