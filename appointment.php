<?php

session_start();
include("connection.php");

$canceled = false;
$canceledMessage = "Ihr Termin wurde erfolgreich storniert.";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
}

if (isset($_POST["cancel"])) {
    $canceled = true;
    header("refresh:3;url=cancel.php");
}

$stmt = $connection->prepare("SELECT starting_time, ending_time FROM user WHERE user_id=:user_id");
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$row = $stmt->fetch();

$starting_time = $row["starting_time"];
$ending_time = $row["ending_time"];

$user_date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $starting_time);
$starting_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $starting_time);
$ending_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $ending_time);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles -->
    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/appointment.css">
    <link rel="shortcut icon" href="assets/medical-appointment.png" type="image/x-icon">

    <title>Terminbuchung | Ihre Daten</title>
</head>

<body>
    <h1 class="appointment-heading">Vielen Dank für Ihre Teilnahme!</h1>
    <div class="appointment-container">
        <div class="appointment-personal">
            <h3>Ihre ID: <?php echo $_SESSION["user_id"]; ?></h3>
            <h3>Datum: <?php echo $user_date->format("d.m.Y"); ?></h3>
            <h3>Uhrzeit: <?php echo $starting_time->format("H:i"); ?> - <?php echo $ending_time->format("H:i"); ?></h3>
            <h3>Ort: UKE, Gebäude W34, 2.OG (xENI-Labor)</h3>
            <form action="appointment.php" method="POST">
                <p class='error cancel'><?php if ($canceled) echo $canceledMessage; ?></p>
                <input type="submit" name="cancel" class="cancel-btn" value="Stornieren" />
            </form>
        </div>

        <h2>Wichtige Informationen:</h2>
        <p class="information">
            Durch die Terminbestätigung haben Sie eine persönliche ID erhalten, mit der Sie sich ab sofort auf der Website anmelden können. Diese Funktion können Sie nutzen, um die Informationen zum Termin einzusehen oder diesen zu stornieren.
            <br><br>
            Aufgrund der derzeitigen Corona-Situation bitten wir Sie, kurz vor Ihrem Termin vor dem Gebäude W34 des UKE mit Ihrem vollständigen Impfnachweis und, falls möglich, einem negativen Corona-Schnelltest-Nachweis (nicht älter als 24h) zu erscheinen. Dort werden wir Sie abholen, Ihnen einen Mund-Nasen-Schutz übergeben und Sie dann in den Untersuchungsraum des Gebäudes führen. Sollten Sie vorher keinen Schnelltest durchgeführt haben, ist es auch möglich ihn bei uns zu machen.
            <br><br>
            Im Untersuchungsraum müssen zunächst Formulare ausgefüllt werden. Als nächstes bekommen Sie dann ein Lokalanästhetikum auf die Kopfhaut unter den Stimulationselektroden, um die Empfindungen durch die tACS zu vermindern. Dieses muss eine Stunde einwirken. Während der Einwirkzeit werden Sie weiter über das Experiment aufgeklärt und füllen mehrere Formulare aus. Für die restliche Wartezeit können Sie sich gern etwas zur Beschäftigung mitnehmen. Kurz vor dem Ende der Einwirkzeit bringen wir ein Elektrodengel auf die Kopfhaut unter den Stimulationselektroden auf.
            <br><br>
            Nach dem Ende des Experiments können Sie Sich die Haare waschen und abtrocknen (Handtücher und Shampoo sind vorhanden).
            <br><br>
            <strong>Wichtig:</strong> Sollten Sie am Tag der Messung typische Covid-19 Krankheitssymptome (z.B. Husten, Schnupfen, Fieber, Verlust des Geruchs- oder Geschmackssinns) bei Ihnen feststellen, bitten wir Sie eindringlich, nicht zum UKE zu kommen und sich telefonisch oder per E-Mail bei uns abzumelden.
            <br><br>
            Vor jeder Messung muss ein Formular zur Selbstauskunft ausgefüllt werden (siehe Link zur Ansicht). Dieses wird 4 Wochen aufbewahrt und dann vernichtet. Sollten Sie Frage 1 oder 2 mit „Ja“ oder Frage 3 mit „Ja“ und eine der darauffolgenden Fragen mit „nein“ beantworten, dann dürfen Sie leider nicht an der Studie teilnehmen. Melden Sie sich in diesem Fall, oder bei Fragen dazu, bitte vorab bei uns.
            <br><br>
            <a href="./assets/selbstauskunft.pdf" target="blank" class="pdf-btn">Formular</a>
        </p>

        <br><br>

        <p class="information underline">
            <strong>Weiterhin bitten wir Sie, folgende Punkte zu beachten, um einen reibungslosen Ablauf zu ermöglichen:</strong>
        </p>

        <ul>
            <li>Erscheinen Sie bitte ausgeschlafen zur Untersuchung</li>
            <li>Konsumieren Sie bitte nicht übermäßig Alkohol am Vorabend der Untersuchung</li>
            <li>Erscheinen Sie bitte pünktlich und lassen Sie den Antigen-Schnelltest bestenfalls im Vorhinein durchführen.</li>
        </ul>
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>

</body>

</html>