<?php
require_once('lib/lib.php');

if (!isset($_GET['id'])) {
    echo "<p>Erro: ID do post não fornecido.</p>";
    exit;
}

$id = intval($_GET['id']);
$db = getDatabaseConnection();

// Buscar post e dados do utilizador
$stmt = $db->prepare("
    SELECT up.*, u.username, u.profileImagePath
    FROM `users-posts` up
    JOIN users u ON up.idUser = u.id
    WHERE up.id = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<p>Post não encontrado.</p>";
    exit;
}

// Obter número de likes
$stmtLikes = $db->prepare("SELECT COUNT(*) FROM likes WHERE idPost = ?");
$stmtLikes->execute([$id]);
$likeCount = $stmtLikes->fetchColumn();

// Obter comentários
$stmtComments = $db->prepare("
    SELECT c.comment, u.username
    FROM comments c
    JOIN users u ON c.idUser = u.id
    WHERE c.idPost = ?
    ORDER BY c.dateCreated ASC
");
$stmtComments->execute([$id]);
$comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML do post -->
<div class="post-details">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><strong>Por:</strong> <?= htmlspecialchars($post['username']) ?></p>
    <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($post['description'])) ?></p>
    <p><strong>Visibilidade:</strong> <?= htmlspecialchars($post['visibility']) ?></p>
    <p><strong>Data:</strong> <?= htmlspecialchars($post['createdAt']) ?></p>
    <p><strong>Likes:</strong> <?= $likeCount ?></p>

    <?php if (!empty($post['idImage'])): ?>
        <img src="uploads/<?= htmlspecialchars($post['idImage']) ?>" alt="Imagem do post" style="max-width: 100%;">
    <?php endif; ?>

    <h3>Comentários:</h3>
    <?php if (count($comments) > 0): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li><strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['comment']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Sem comentários.</p>
    <?php endif; ?>
</div>
