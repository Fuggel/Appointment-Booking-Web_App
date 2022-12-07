<?php

include("appointment.php");
include("connection.php");
include("mail.php");

$stmt = $connection->prepare("SELECT * FROM user WHERE user_id=:user_id");
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();
$row = $stmt->fetch();

$stmt = $connection->prepare("DELETE FROM user WHERE user_id=:user_id");
$stmt->bindParam(":user_id", $_SESSION["user_id"]);
$stmt->execute();

$user_id = $row["user_id"];
$starting_time = $row["starting_time"];
$ending_time = $row["ending_time"];

$user_date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $starting_time);
$starting_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $starting_time);
$ending_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $ending_time);

session_unset();
session_destroy();
echo "<script>window.location.href='index.php';</script>";
sendMailCanceled($user_id, $user_date, $starting_time, $ending_time, $user_email);
