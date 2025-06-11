<?php

session_start();
require_once("lib.php");

// Suponho que $db seja sua conexão PDO (deve estar em lib.php)
global $db;

if (isset($_POST['post_id'], $_POST['comment']) && isset($_SESSION['id'])) {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['id'];

    addComment($postId, $userId, $comment);

    header("Location: post.php?id=" . $postId);
    exit;
}

function getComments($postId) {
    global $db;

    $stmt = $db->prepare("
        SELECT comments.*, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE post_id = ? 
        ORDER BY createdAt ASC
    ");
    $stmt->execute([$postId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($postId, $userId, $commentText) {
    global $db;

    $stmt = $db->prepare("
        INSERT INTO comments (post_id, user_id, content, createdAt) 
        VALUES (?, ?, ?, NOW())
    ");
    return $stmt->execute([$postId, $userId, $commentText]);
}
