<?php
include "db.php";

$user = trim($_POST["username"] ?? "");
$pass = $_POST["password"] ?? "";

if (!$user || !$pass) {
  die("Missing fields");
}

$hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
$stmt->bind_param("ss", $user, $hash);

if ($stmt->execute()) {
  header("Location: index.html");
  exit;
} else {
  echo "Username already exists.";
}