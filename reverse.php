<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Get user ID
$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['word'])) {
    $word = $_POST['word'];
    $reversed_word = strrev($word);

    $sql = "INSERT INTO reversed_words (user_id, word, reversed_word) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $word, $reversed_word);
    $stmt->execute();

    header("Location: history.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Word Reversal</title>
</head>
<body>
    <h1>Word Reversal</h1>
    <form method="post" action="reverse.php">
        <label for="word">Enter a word:</label>
        <input type="text" id="word" name="word">
        <button type="submit">Reverse</button>
    </form>
    <p><a href="history.php">View History</a> | <a href="logout.php">Logout</a></p>
</body>
</html>
