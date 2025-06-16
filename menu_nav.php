<style>
    input[name="top_tab"] {
        display: none;
    }

    .top_tabs {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        padding: 10px 0;
        gap: 10px;
        margin-bottom: 20px;
        scrollbar-width: thin;
        scrollbar-color: #888 transparent;
        white-space: nowrap;
        border-bottom: 1px solid #ddd;
        height: auto;
        max-height: none;
    }

    .top_tabs::-webkit-scrollbar {
        height: 6px;
    }

    .top_tabs::-webkit-scrollbar-track {
        background: transparent;
    }

    .top_tabs::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .top_tabs::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    label.top_tab {
        flex: 0 0 auto;
        padding: 12px 18px;
        cursor: pointer;
        white-space: nowrap;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        background-color: transparent;
        border-radius: 4px 4px 0 0;
        font-weight: 500;
        color: #666;
        position: relative;
    }

    .top_tab:hover {
        background-color: #f0f0f0;
        color: #333;
    }

    .top_tab.active {
        border-bottom: 3px solid #0a2c5a;
        color: #0a2c5a;
        font-weight: bold;
        background-color: rgba(10, 44, 90, 0.05);
    }

    .tab-content {
        max-width: 1200px;
        margin: 0 auto;
    }


    .demo-content {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin: 10px 0;
    }

    .content-section {
        display: none;
    }

    .content-section.active {
        display: block;
    }
</style>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("lib/lib.php");

$idUser = $_SESSION['id'];

if (isset($username)) {
    $idUserProfile = searchUsers($username, $idUser)[0]["id"];
    $owner = false;
} else {
    $idUserProfile = $_SESSION['id'];
    $owner = true;
}

$followers = getUserFollowers($idUser);
$following = getUserFollowing($idUser);

#Profile
$profile_posts = getPosts($idUserProfile, $owner);
$profile_userData = getUserData($idUserProfile);
$profile_followers = getUserFollowers($idUserProfile);
$profile_following = getUserFollowing($idUserProfile);

$notifications = getActivities($idUser);

if (!$owner) {
    $isfollowing = false;
    for ($i = 0; $i < count($profile_followers); $i++) {

        if ($profile_followers[$i]['idFollower'] == $idUser) {
            $isfollowing = true;
        }
    }
}

// Buscar categorias da base de dados
$categories = getAllCategories();

$current_user = getUserData($idUser); 
$user_type = $current_user['user_type'];
$canPost = ($user_type !== 'user');
$isAdministrator = ($user_type === 'administrator');
?>

<input hidden type="radio" name="tab" id="profile">
<input hidden type="radio" name="tab" id="results">
<input type="radio" name="tab" id="home">
<input type="radio" name="tab" id="create">
<input type="radio" name="tab" id="notification">
<input type="radio" name="tab" id="film">
<input type="radio" name="tab" id="photos">
<input type="radio" name="tab" id="music">
<input type="radio" name="tab" id="logout">


<div class="tabs">
    <label for="home" class="tab">
        <i class="bi bi-house-door default-icon"></i>
        <i class="bi bi-house-door-fill active-icon"></i>
    </label>
    <?php if ($canPost): ?>
        <label for="create" class="tab" onclick="openUploadForm()">
            <i class="bi bi-plus-square default-icon"></i>
            <i class="bi bi-plus-square-fill active-icon"></i>
        </label>
    <?php else: ?>
    <?php endif; ?>
    <label for="notification" class="tab">
        <i class="bi bi-bell default-icon"></i>
        <i class="bi bi-bell-fill active-icon"></i>
    </label>
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
    <a href="logout.php" class="tab" style="color:#212529">
        <i class="bi bi-box-arrow-right default-icon"></i>
        <i class="bi bi-box-arrow-right active-icon"></i>
    </a>
</div>

<div class="tab-content">

    <div id="homeContent" class="content">

        <!-- TOP TABS INPUTS -->
        <input type="radio" name="top_tab" id="all" checked>
        <?php foreach ($categories as $index => $cat): ?>
            <input type="radio" name="top_tab" id="tag<?= $cat['id'] ?>">
        <?php endforeach; ?>

        <!-- TOP TABS LABELS -->
        <div class="top_tabs">
            <label for="all" class="top_tab">All</label>
            <?php foreach ($categories as $cat): ?>
                <label for="tag<?= $cat['id'] ?>" class="top_tab"><?= htmlspecialchars($cat['tagName']) ?></label>
            <?php endforeach; ?>
        </div>

        <div class="all-content content-section active">

            <div class="img_container">
                <?php
                $posts = array();
                for ($i = 0; $i < count($following); $i++) {
                    $followingUserPosts = getPosts($following[$i]['idFollowed'], $owner);
                    for ($j = 0; $j < count($followingUserPosts); $j++) {
                        $posts[] = $followingUserPosts[$j];
                    }
                }

                if (count($posts) > 0) {
                    $randomKeys = array_rand($posts, count($posts));

                    $sizes = ['small', 'medium', 'large'];

                    foreach ($randomKeys as $k) {
                        $post = $posts[$k];
                        $postID = $post['id'];
                        $postTitle = htmlspecialchars($post['title']);
                        $user = getUserData($post['idUser'])['username'];
                        $description = $post['description'];
                        $date = $post['createdAt'];

                        $fileID = $post['idImage'];
                        $fileDetails = getFileDetails($fileID);

                        if ($fileDetails['mimeFilename'] == 'image') {
                            $size = $sizes[array_rand($sizes)];
                        } else if ($fileDetails['mimeFilename'] == 'video') {
                            $size = 'large';
                        } else {
                            $size = 'small';
                        }

                        echo "<figure class=\"card card_$size\" 
                        data-post-id=\"$postID\" 
                        data-username=\"$user\" 
                        data-description=\"" . htmlspecialchars($description) . "\" 
                        data-date=\"$date\">";
                        echo "<img src=\"showFileThumb.php?id=$fileID&size=$size\" alt=\"" . htmlspecialchars($postTitle) . "\">";
                        echo "<figcaption>$postTitle</figcaption>";
                        echo "</figure>";

                    }
                }
                ?>
            </div>
        </div>
        <?php foreach ($categories as $cat): ?>
            <div class="tag<?= $cat['id'] ?>-content content-section" id="tag<?= $cat['id'] ?>-content">
                <div class="img_container">
                    <?php
                    $categoryPosts = getPostsByCategory($cat['id']);
                    if (count($categoryPosts) > 0):
                        foreach ($categoryPosts as $post):
                            $postID = $post['id'];
                            $postTitle = htmlspecialchars($post['title']);
                            $user = getUserData($post['idUser'])['username'];
                            $description = htmlspecialchars($post['description']);
                            $date = $post['createdAt'];
                            $fileID = $post['idImage'];
                            $fileDetails = getFileDetails($fileID);

                            $sizes = ['small', 'medium', 'large'];
                            if ($fileDetails['mimeFilename'] == 'image') {
                                $size = $sizes[array_rand($sizes)];
                            } else if ($fileDetails['mimeFilename'] == 'video') {
                                $size = 'large';
                            } else {
                                $size = 'small';
                            }
                            ?>

                            <figure class="card card_<?= $size ?>" data-post-id="<?= $postID ?>"
                                data-username="<?= htmlspecialchars($user) ?>" data-description="<?= $description ?>"
                                data-date="<?= $date ?>">
                                <img src="showFileThumb.php?id=<?= $fileID ?>&size=<?= $size ?>" alt="<?= $postTitle ?>">
                                <figcaption><?= $postTitle ?></figcaption>
                            </figure>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No posts found in this category.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>


    </div>

    <div id="createContent" class="content"></div>

    <div id="notification" class="content">
        <h2>Notifications</h2>
        <ul class="notification-list">
            <?php
            for ($i = 0; $i < count($notifications); $i++) {

                $actor = getUserData($notifications[$i]['idActor']);
                $action = $notifications[$i]['action'];
                $isRead = $notifications[$i]['isRead'];

                $createdAt = new DateTime($notifications[$i]['createdAt']);
                $now = new DateTime();
                $diffInSeconds = $now->getTimestamp() - $createdAt->getTimestamp();

                if ($diffInSeconds < 60) {
                    $time = "A few seconds ago";
                } elseif ($diffInSeconds < 3600) {
                    $time = floor($diffInSeconds / 60) . "m";
                } elseif ($diffInSeconds < 86400) {
                    $time = floor($diffInSeconds / 3600) . "h";
                } else {
                    $time = floor($diffInSeconds / 86400) . "d";
                }


                switch ($action) {
                    case 'follow':
                        $text = $actor['username'] . " followed you!";
                        break;

                    case 'comment':
                        $target = getPostData($notifications[$i]['idTarget']);
                        $text = $actor['username'] . " commented on your post!";
                        break;

                    case 'like':
                        $target = getPostData($notifications[$i]['idTarget']);
                        $text = $actor['username'] . " liked your post!";
                        break;
                }
                ?>
                <li class="notification-item">
                    <i class="bi bi-info-circle"></i>
                    <?php echo $text ?>
                    <span class="time"><?php echo $time ?></span>
                </li>
                <?php
            }
            ?>

        </ul>
    </div>
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
                    $description = $post['description'];

                    $fileID = $post['idImage'];

                    $sizes = ['small', 'medium', 'large'];
                    $randomSize = $sizes[array_rand($sizes)];


                    $image = "<img src=\"showFileThumb.php?id=$fileID&size=$randomSize\" alt=\"Post\"></img>";
                    $caption = "<figcaption> $description </figcaption>";
                    echo "<figure class=\"card card_$randomSize\" data-post-id=\"$idPost\">$image $caption </figure>";
                }
            }
            ?>
        </div>
    </div>

    <div id="logoutContent" class="content">
        <h2>Content 3</h2>
        <p><a href="logout.php">Logout</a></p>
    </div>

    <div id="resultsContent" class="content">
        <div id="resultsContainer" class="img_container">
        </div>
    </div>

    <div id="profileContent" class="content">
        <div class="profile-header">
            <div class="profile-pic">
                <img src="<?php echo htmlspecialchars($profile_userData['profile_pic']); ?>" alt="Profile Picture" />
            </div>
            <div class="profile-info">
                <h1 id="userName"> <?php echo $profile_userData['username']; ?> </h1>
                <p class="bio" id="userBio"><?php echo $profile_userData['biography']; ?></p>

                <div class="social-stats">
                    <div><strong><?php echo count($profile_followers) ?></strong><br />Followers</div>
                    <div><strong><?php echo count($profile_following) ?> </strong><br />Following</div>

                    <?php if ($canPost): ?>
                        <div><strong><?php echo count($profile_posts) ?></strong><br />Posts</div>
                    <?php else:
                    endif;

                    if (!$owner && !$isfollowing) {
                        ?>
                        <a href="follow.php?idFollower=<?php echo urlencode($idUser) ?>&idFollowed=<?php echo urlencode($idUserProfile) ?>"
                            class="follow-btn">Follow</a>
                        <?php
                    } else if (!$owner && $isfollowing) {
                        ?>
                            <a href="unfollow.php?idFollower=<?php echo urlencode($idUser) ?>&idFollowed=<?php echo urlencode($idUserProfile) ?>"
                                class="follow-btn">Unfollow</a>
                        <?php
                    }
                    ?>

                    <?php if ($owner) { ?>
                    <button class="default-btn" onclick="openEditProfileForm()">Edit Profile</button>
                    <?php } ?>

                    <?php if ($user_type === 'user') { ?>
                        <button class="default-btn" onclick="openSupporterForm()">Become Supporter</button>
                    <?php } ?>

                    <?php if ($isAdministrator) { ?>
                        <button class="default-btn" onclick="openAddCategoryForm()">Add Category</button>
                    <?php } ?>

                    <?php if (!$owner) { ?>
                        <button class="default-btn" onclick="scrollToContact()">Contact Me</button>
                    <?php } ?>

                </div>
            </div>
        </div>

        <div class="img_container">
            <?php

            if ($canPost) {
                for ($idx = 0; $idx < count($profile_posts); $idx++) {
                    $idFile = $profile_posts[$idx]['idImage'];
                    $idPost = $profile_posts[$idx]['id'];
                    $target = "<img src=\"showFileThumb.php?id=" . $idFile . "&size=small\" alt=\"Post\"></img>";
                    echo "<div class=\"card card_small\" data-post-id=\"$idPost\">$target</div>";
                }
            } else if (!$canPost) { ?>
                    <p>Become supporter to post contents</p>

            <?php }

            ?>
        </div>
        <?php if (!$owner) { ?>
            <div id="contact" class="contact-container">
                <h2>Contact Me</h2>
                <form action="sendEmail.php?id=<?php echo urlencode($idUserProfile); ?>" method="POST">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name..." required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your email..." required>

                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="Subject..." required>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" placeholder="Write your message..." required></textarea>
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

                    <button class="default-btn" type="submit">Send</button>
                </form>
            </div>
        <?php } ?>
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
                        <input type="hidden" id="privacyInput" name="changePrivacy" value="public">
                        <div id="userData" data-username="<?= htmlspecialchars($idUser) ?>"></div>

                        <button id="privacyToggle" style="display: none;" aria-pressed="false"
                            title="Definido como público" onclick="(<?php togglePostPrivacy($idUser); ?>)">
                            <i class="bi bi-lock-open"></i> Make Private
                        </button>
                    </div>
                </div>
            </div>

            <div id="modalMediaContainer">
            </div>

            <div class="post-footer">
                <div class="post-actions">
                    <a id="likeButton" class="like-button" onclick="toggleLike(<?php echo $idUser ?>)">
                        <i class="bi bi-heart"></i>
                    </a>
                    <span id="likeCount" class="action-count">-1</span>
                </div>
                <div class="caption">
                    <span class="username" id="captionUsername"></span>
                    <span id="captionText" class="caption-text"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-popup" id="uploadForm">
    <form method="POST" class="form-container" action="fileUpload.php" enctype="multipart/form-data">
        <span class="close-icon" onclick="closeUploadForm()">&times;</span>

        <h3>Upload New Content</h3>
        <div class="info">

            <input type="file" id="upload-file" name="userFile" size="64" required>

            <input type="text" id="upload-title" name="title" placeholder="Enter a title" required>
            <textarea type="text" id="upload-description" name="description" placeholder="Write a short description..."
                rows="6"></textarea>

            <label for="upload-category">Category:</label>
            <select id="upload-category" name="category" required>
                <option value="">Select a category...</option>
                <?php
                if (isset($categories) && count($categories) > 0) {
                    for ($i = 0; $i < count($categories); $i++) {
                        $categoryId = htmlspecialchars($categories[$i]['id']);
                        $categoryName = htmlspecialchars($categories[$i]['tagName']);
                        echo "<option value=\"$categoryId\">$categoryName</option>";
                    }
                }
                ?>
            </select>

            <button type="button" id="privacyToggle" class="privacy-btn" name="privacy" aria-pressed="false"
                title="Definir como público ou privado">
                <i class="fa-solid fa-lock-open"></i>
            </button>
            <input type="hidden" name="privacy" id="privacyInput" value="public">

            <button type="submit" class="default-btn">Upload</button>
        </div>
    </form>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="js/script.js"></script>
<script>
    const privacyToggle = document.getElementById('privacyToggle');
    const privacyInput = document.getElementById('privacyInput');

    privacyToggle.addEventListener('click', () => {
        const icon = privacyToggle.querySelector('i');
        if (privacyInput.value === 'public') {
            privacyInput.value = 'private';
            privacyToggle.setAttribute('aria-pressed', 'true');
            icon.classList.remove('fa-lock-open');
            icon.classList.add('fa-lock');
            privacyToggle.title = "Definido como privado";
        } else {
            privacyInput.value = 'public';
            privacyToggle.setAttribute('aria-pressed', 'false');
            icon.classList.remove('fa-lock');
            icon.classList.add('fa-lock-open');
            privacyToggle.title = "Definido como público";
        }
    });

    const idUser = <?php echo json_encode($idUser); ?>

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', async function () {
                const idPost = this.dataset.postId;
                await openPost(idPost, idUser);
            });
        });

        const topTabInputs = document.querySelectorAll('input[name="top_tab"]');
        const contentSections = document.querySelectorAll('.content-section');

        function showTabContent(selectedTabId) {
            contentSections.forEach(section => {
                section.classList.remove('active');
            });

            if (selectedTabId === 'all') {
                const allContent = document.querySelector('.all-content');
                if (allContent) {
                    allContent.classList.add('active');
                }
            } else {
                const categoryId = selectedTabId.replace('tag', '');
                const categoryContent = document.querySelector(`.tag${categoryId}-content`);
                if (categoryContent) {
                    categoryContent.classList.add('active');
                }
            }
        }

        topTabInputs.forEach(input => {
            input.addEventListener('change', function () {
                if (this.checked) {
                    showTabContent(this.id);
                }
            });
        });

        showTabContent('all');

        const topTabLabels = document.querySelectorAll('.top_tab');
        topTabLabels.forEach(label => {
            label.addEventListener('click', function () {
                const targetInputId = this.getAttribute('for');
                const targetInput = document.getElementById(targetInputId);
                if (targetInput) {
                    targetInput.checked = true;
                    showTabContent(targetInputId);
                }
            });
        });
    });
</script>