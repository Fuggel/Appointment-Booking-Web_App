<?php

session_start();

$agreement = true;
$errorMessage = "Bitte bestätigen Sie, dass Sie den Text gelesen haben und die Kriterien erfüllen.";

if (isset($_POST["submit"])) {
    $agreement = false;
    if (isset($_POST["checkbox-terms"])) {
        $agreement = true;
        $checkbox = $_POST["checkbox-terms"];

        setcookie("checkbox", $checkbox, time() + (86400 * 30));
        header("Location: index.php");
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
    <link rel="stylesheet" href="./styles/welcome.css">
    <link rel="shortcut icon" href="assets/medical-appointment.png">

    <title>Terminbuchung | Informationen</title>
</head>

<body>
    <h1 class="welcome-header">Einfluss nicht-invasiver Hirnstimulation <br> auf das motorische Lernen</h1>
    <div class="welcome-container">
        <p class="welcome-text">
            Liebe:r Proband:in,
            <br><br><br>
            Vielen Dank für Ihr Interesse an unserer Studie!
            <br><br>
            <strong>Bitte lesen Sie sich die folgenden Informationen durch, bevor Sie im nächsten Schritt einen Termin buchen.</strong>
            <br><br>
            Wir untersuchen den Einfluss von nicht-invasiver Hirnstimulation auf das motorische Lernen. Es handelt sich dabei um eine Wechselstromstimulation (transcranial Alternating Current Stimulation, kurz: tACS).
            Es gibt grundsätzlich zwei Gruppen, in die Versuchspersonen eingeteilt werden können: Placebo und tatsächliche Stimulation. In welche der beiden Gruppen Sie eingeteilt werden, werden weder Sie noch der/die Versuchleiter:in wissen. Es handelt sich also um eine doppelt-verblindete Studie.
            <br><br>
            Außerdem bitten wir Sie, folgende <strong>Ein- und Ausschlusskriterien</strong> zu beachten:
            <br><br>
            <span>Folgendes sollte auf Sie zutreffen:</span>
        </p>

        <ul>
            <li>Sie sind zwischen 18 und 35 Jahre alt.</li>
            <li>Sie sind Rechtshänder:in.</li>
        </ul>

        <br><br>

        <p class="welcome-text">
            <span>Bitte teilen Sie uns mit, ob Sie eine der folgenden Fragen mit „ja“ beantworten. In diesem Fall dürfen Sie leider nicht an der Studie teilnehmen:</span>
        </p>

        <ul>
            <li>Haben / hatten Sie eine Epilepsie?</li>
            <li>Haben / hatten Sie jemals einen Krampfanfall?</li>
            <li>Wurde bei Ihnen jemals eine neurologische Erkrankung diagnostiziert?</li>
            <li>Haben Sie Schrittmacher oder implantierte Geräte (z.B. Medikamentenpumpen) im Körper?</li>
            <li>Bei Frauen: sind Sie schwanger / könnten Sie schwanger sein?</li>
            <li>Nehmen Sie psychotrope Substanzen (z.B. Antiepileptika, Antidepressiva, Antipsychotika) ein?</li>
        </ul>

        <br><br>

        <p class="welcome-text">
            Liebe Grüße
            <br>
            Hakan Ceylan und Sophie Grigutsch
        </p>

        <form action="welcome.php" method="POST">
            <input type="checkbox" class="checkbox-terms" name="checkbox-terms" id="checkbox" />
            <label for="checkbox">Ich habe den Text gelesen und erfülle alle Kriterien.</label>
            <p class="terms-error"><?php if (!$agreement) echo $errorMessage; ?></p>
            <input type="submit" name="submit" value="Bestätigen" class="submit-terms">
        </form>
    </div>
</body>

</html>