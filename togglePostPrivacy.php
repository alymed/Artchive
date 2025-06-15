<?php
require_once("lib/lib.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $postId = $input['postId'] ?? '';
    $privacy = $input['privacy'] ?? '';
    
    if (empty($postId) || empty($privacy)) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    // Verificar se o usuário é o dono do post
    $postData = getPostData($postId);

    if ($postData['idUser'] !== $_SESSION['id']) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    
    // Atualizar privacidade do post
    $result = updatePostPrivacy($postId, $privacy);
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update privacy']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>