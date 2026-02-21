<?php
include "db.php";
session_start();

$user = trim($_POST["username"] ?? "");
$pass = $_POST["password"] ?? "";

$stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
  $stmt->bind_result($hash);
  $stmt->fetch();

  if (password_verify($pass, $hash)) {
    $_SESSION["user"] = $user;
    header("Location: feed.php");
    exit;
  }
}

echo "Login failed. <a href='index.html'>Back</a>";