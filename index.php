<?php

session_start();
include("connection.php");
include("mail.php");

if (!isset($_COOKIE["checkbox"])) {
    header("Location: welcome.php");
}

$user_id = strtoupper(substr(md5(microtime()), rand(0, 26), 10));

$errorEmpty = false;
$errorMessageEmpty = "Bitte wählen Sie Datum und Uhrzeit aus.";

$errorBookedOut = false;
$errorMessageBookedOut = "Der Zeitpunkt ist leider schon ausgebucht.";

if (isset($_POST["submit"])) {
    if ($_POST["user_date"] === "" ||  $_POST["starting_time"] === "") {
        $errorEmpty = true;
    } else {
        $errorEmpty = false;
        $user_date = $_POST["user_date"];
        $starting_time = $_POST["starting_time"];
        $ending_time = $_POST["ending_time"];

        $user_date = str_replace(".", "-", $user_date);
        $starting_time = str_replace(".", "-", $starting_time) . ":00";
        $newDate = DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $user_date . " " . $starting_time);

        $fetch_db_ending_time = $connection->prepare("SELECT ending_time, starting_time FROM user");
        $fetch_db_ending_time->execute();
        $result = $fetch_db_ending_time->fetchAll();

        $openAppointment = true;

        if (is_array($result) && count($result) > 0) {
            foreach ($result as $row) {
                $db_ending_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row["ending_time"]);
                $db_starting_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row["starting_time"]);

                if ($newDate <= $db_ending_time && $newDate >= $db_ending_time->modify("-3 hours")) {
                    $openAppointment = false;
                } elseif ($newDate >= $db_starting_time->modify("-3 hours") && $newDate <= $db_ending_time) {
                    $openAppointment = false;
                }
            }
        }

        if (!$openAppointment) {
            $errorBookedOut = true;
        } else {
            $stmt = $connection->prepare("INSERT INTO user (user_id, starting_time, ending_time) 
            VALUES (:user_id, :starting_time, :ending_time)");

            $stmt->execute([
                "user_id" => $user_id, "starting_time" => $newDate->format("Y-m-d H:i:s"),
                "ending_time" => $newDate->modify("+3 hours")->format("Y-m-d H:i:s")
            ]);

            $_SESSION["user_id"] = $user_id;
            $_SESSION["starting_time"] = $starting_time;
            $_SESSION["ending_time"] = $ending_time;

            sendMail($user_id, $newDate, $user_email);
            header("Location: appointment.php");
        }
    }
}

$appointment_status = true;
$appointment_message = "Ausgebuchte Zeiten:";
$show_dates = false;

if (!empty($_POST["user_date"]) && $errorBookedOut) {
    $user_date = $_POST["user_date"];

    $fetch_db_date = $connection->prepare("SELECT starting_time, ending_time FROM user");
    $fetch_db_date->execute();
    $result = $fetch_db_date->fetchAll();

    if (is_array($result) && count($result) > 0) {
        $appointment_status = false;
        $show_dates = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles -->
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="shortcut icon" href="assets/medical-appointment.png">

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js" defer></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js" defer></script>
    <script src="index.js" defer></script>

    <title>Terminbuchung</title>
</head>

<body>
    <div class="uke-appointment">
        <h1>Terminbuchung</h1>
        <form action="index.php" class="date-container" method="POST">
            <p class="error empty">
                <?php if ($errorEmpty) echo $errorMessageEmpty;
                elseif ($errorBookedOut) echo $errorMessageBookedOut; ?>
            </p>

            <div class="booked-out">
                <p class="out-msg"><?php if (!$appointment_status) echo $appointment_message; ?></p>
                <p class="out-dates">
                    <?php if ($show_dates) {
                        foreach ($result as $row) {
                            $db_date = new DateTime($row["starting_time"]);
                            $converted_date = $db_date->format("d.m.Y");

                            $db_starting_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row["starting_time"]);
                            $db_ending_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row["ending_time"]);

                            if ($user_date == $converted_date) {
                                echo $user_date . ", " . $db_starting_time->format("H:i") . "  -  " . $db_ending_time->format("H:i") . " Uhr" . "<br>";
                            }
                        }
                    } ?>
                </p>
            </div>

            <input type="date" name="user_date" id="user_date" class="user_date" placeholder="Wählen Sie ein Datum aus">
            <input type="text" id="starting_time" class="starting_time" name="starting_time" placeholder="Wählen Sie eine Uhrzeit aus" readonly />
            <input type="text" class="ending_time" name="ending_time" />
            <input type="submit" name="submit" value="Bestätigen" class="submit-btn">
        </form>
    </div>
    <a href="login.php" class="login-btn">Login</a>
</body>

</html>