  <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once( "lib/lib.php" );

    $idUser = $_SESSION['id'];

    $current_user = getUserData($idUser); // Get current user's data
    $user_type = $current_user['user_type']; // Get user type
    $supporter = ($user_type !== 'supporter'); // Supporters cannot post

?>
  
  <div class="form-overlay" id="formOverlay"></div>

  <div class="search-container">
    <a href="app.php" class="nav-logo">
      <img src="images/logo.png" alt="Logo" class="logo-img">
    </a>
    <div class="center-wrapper">
    <input type="text" class="search-box" id="search" placeholder="Search..." autocomplete="off">
    <div id="autocomplete-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 1000;"></div>
  </div>

    

    <?php if ($supporter): ?>
    <a href="perfil.php" class="user-icon">
      <i class="bi bi-person-circle"></i>
    </a>
    <?php else: ?>
    <?php endif; ?>
  </div>

  <script>

    document.getElementById('search').addEventListener('input', function() {
    const search = this.value;

    if (search.length === 0) {
        document.getElementById('autocomplete-results').innerHTML = '';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'searchUsersJS.php?query=' + encodeURIComponent(search), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);
            let html = '';

            results.forEach(item => {
                html += `<div class="autocomplete-item">${item.username}</div>`;
            });

            document.getElementById('autocomplete-results').innerHTML = html;

            document.querySelectorAll('.autocomplete-item').forEach(item => {
                item.addEventListener('click', function () {
                    let username = item.textContent;
                    window.location.href = `perfil.php?username=${encodeURIComponent(username)}`;
                });
            });
        }
    };
    xhr.send();

  });
  
  </script>