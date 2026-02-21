<?php
session_start();
include "db.php";

$user = $_SESSION["user"] ?? (isset($_POST["user"]) ? $_POST["user"] : null);
if (!$user) {
  $user = "Guest_" . rand(1000,9999);
}

$content = trim($_POST["content"] ?? "");
$close = isset($_POST["close_friends"]) ? 1 : 0;

$media = null;

if (isset($_FILES["media"]) && $_FILES["media"]["error"] === UPLOAD_ERR_OK) {
  $ext = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));
  $allowed = ["jpg","jpeg","png","gif","webp","mp4","mov","webm"];

  if (!in_array($ext, $allowed)) {
    die("Invalid file type.");
  }

  if (!is_dir(__DIR__ . "/uploads")) {
    mkdir(__DIR__ . "/uploads", 0777, true);
  }

  $safeName = preg_replace("/[^a-zA-Z0-9._-]/", "", $_FILES["media"]["name"]);
  $media = time() . "_" . $safeName;

  move_uploaded_file($_FILES["media"]["tmp_name"], __DIR__ . "/uploads/" . $media);
}

$stmt = $conn->prepare("INSERT INTO posts (user, content, media, is_close_friend) VALUES (?,?,?,?)");
$stmt->bind_param("sssi", $user, $content, $media, $close);
$stmt->execute();

header("Location: feed.php");
exit;