<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");
include 'auth_forms.php';

$idUser = $_SESSION['id'] ?? null;

if (isset($idUser)) {
    header('Location: app.php');
    exit();
}

$owner = false;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artchive</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/script.js"></script>
</head>

<body>
    <div class="form-overlay" id="formOverlay"></div>

    <div class="search-container">
        <a href="app.php" class="nav-logo">
            <img src="images/logo.png" alt="Logo" class="logo-img">
        </a>
        <div style="position: relative;">
            <input type="text" class="search-box" id="search" placeholder="Search..." autocomplete="off">
            <div id="autocomplete-results"
                style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 1000;"></div>
        </div>

        <a onclick="openSignupForm()" class="user-icon">
            <i class="bi bi-person-circle"></i>
        </a>
    </div>

    <input type="radio" name="tab" id="film">
    <input type="radio" name="tab" id="photos" checked>
    <input type="radio" name="tab" id="music">


    <div class="tabs">
        <label for="film" class="tab">
            <i class="bi bi-camera-reels default-icon"></i>
            <i class="bi bi-camera-reels-fill active-icon"></i>
        </label>
        <label for="photos" class="tab">
            <i class="bi bi-camera default-icon"></i>
            <i class="bi bi-camera-fill active-icon"></i>
        </label>
        <label for="music" class="tab">
            <i class="bi bi-file-music default-icon"></i>
            <i class="bi bi-file-music-fill active-icon"></i>
        </label>
    </div>

    <div class="tab-content">
        <div id="filmContent" class="content">
            <div class="img_container">
                <?php
                $posts = array();

                $allUsers = getAllUsersData();

                for ($i = 0; $i < count($allUsers); $i++) {
                    $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                    for ($j = 0; $j < count($allUsersPosts); $j++) {
                        $idFile = $allUsersPosts[$j]['idImage'];
                        $fileData = getFileDetails($idFile);
                        if ($fileData['mimeFilename'] == 'video') {
                            $posts[] = $allUsersPosts[$j];
                        }
                    }
                }

                if (count($posts) > 0) {
                    $randomKeys = array_rand($posts, count($posts));

                    if (count($posts) == 1) {
                        $randomKeys = [$randomKeys];
                    }

                    for ($k = 0; $k < count($posts); $k++) {

                        $post = $posts[$randomKeys[$k]];
                        $idPost = $post['id'];
                        $postTitle = $post['title'];
                        $fileID = $post['idImage'];
                        $user = getUsernameById($post['idUser']);
                        $description = $post['description'];
                        $date = $post['createdAt'];

                        echo "<figure class=\"card card_large\" 
                                  data-post-id=\"$idPost\" 
                                  data-description=\"" . htmlspecialchars($description) . "\" 
                                  data-date=\"$date\">";
                        echo "<img src=\"showFileThumb.php?id=$fileID&size=Large\" alt=\"Post\"></img>";
                        echo "<figcaption>$postTitle</figcaption>";
                        echo "</figure>";
                    }
                }
                ?>
            </div>
        </div>

        <div id="musicContent" class="content">
            <div class="img_container">
                <?php
                $posts = array();

                $allUsers = getAllUsersData();

                for ($i = 0; $i < count($allUsers); $i++) {
                    $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                    for ($j = 0; $j < count($allUsersPosts); $j++) {
                        $idFile = $allUsersPosts[$j]['idImage'];
                        $fileData = getFileDetails($idFile);
                        if ($fileData['mimeFilename'] == 'audio') {
                            $posts[] = $allUsersPosts[$j];
                        }
                    }
                }

                if (count($posts) > 0) {
                    $randomKeys = array_rand($posts, count($posts));
                    if (count($posts) == 1) {
                        $randomKeys = [$randomKeys];
                    }
                    for ($k = 0; $k < count($posts); $k++) {

                        $post = $posts[$randomKeys[$k]];

                        $idPost = $post['id'];
                        $postTitle = $post['title'];
                        $fileID = $post['idImage'];

                        $image = "<img src=\"showFileThumb.php?id=$fileID&size=small\" alt=\"Post\"></img>";
                        $caption = "<figcaption> aaaaaaaa </figcaption>";
                        echo "<figure class=\"card card_small\" data-post-id=\"$idPost\">$image $caption </figure>";
                    }

                }
                ?>
            </div>
        </div>

        <div id="photoContent" class="content">
            <div class="img_container">
                <?php
                $posts = array();

                $allUsers = getAllUsersData();

                for ($i = 0; $i < count($allUsers); $i++) {
                    $allUsersPosts = getPosts($allUsers[$i]['id'], $owner);
                    for ($j = 0; $j < count($allUsersPosts); $j++) {
                        $idFile = $allUsersPosts[$j]['idImage'];
                        $fileData = getFileDetails($idFile);
                        if ($fileData['mimeFilename'] == 'image') {
                            $posts[] = $allUsersPosts[$j];
                        }
                    }
                }

                if (count($posts) > 0) {
                    $randomKeys = array_rand($posts, count($posts));

                    if (count($posts) == 1) {
                        $randomKeys = [$randomKeys];
                    }

                    for ($k = 0; $k < count($posts); $k++) {
                        $post = $posts[$randomKeys[$k]];
                        $idPost = $post['id'];
                        $postTitle = $post['title'];
                        $fileID = $post['idImage'];
                        $image = "<img src=\"showFileThumb.php?id=$fileID&size=small\" alt=\"Post\"></img>";
                        $caption = "<figcaption> $postTitle </figcaption>";
                        echo "<figure class=\"card card_small\" data-post-id=\"$idPost\">$image $caption </figure>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>


    <div id="postModal" class="post-popup">
        <div class="post-container">
            <span class="close-icon" onclick="closePost()">&times;</span>
            <div class="post">
                <div class="post-header">
                    <img id="modalProfilePic" src="images/profilePicHandler.jpg" alt="User profile" class="profile-pic">
                    <div class="user-info">
                        <span id="modalUsername" class="username"></span>
                        <span id="modalPostTitle" class="post-title"></span>
                    </div>
                    <div class="post-menu">
                        <i class="bi bi-three-dots-vertical menu-icon" onclick="togglePostMenu()"></i>
                        <div class="dropdown-menu" id="postMenu">
                            <button onclick="handleShare()">
                                <i class="bi bi-share"></i> Share
                            </button>
                        </div>
                    </div>
                </div>

                <div id="modalMediaContainer">
                </div>
                <div class="post-footer">
                    <div class="post-actions">
                        
                        <span id="commentCount" class="action-count"></span>
                    </div>
                    <div class="caption">
                        <span class="username" id="captionUsername"></span>
                        <span id="captionText" class="caption-text"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', async function () {
                const idPost = this.dataset.postId;
                await openPostGuest(idPost); // now await works here
            });
        });
    });
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('postMenu');
        const menuIcon = document.querySelector('.menu-icon');

        if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

</script>

</html>