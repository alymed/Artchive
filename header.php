
  <div class="form-overlay" id="formOverlay"></div>

  <div class="search-container">
    <a href="app.php" class="nav-logo">
      <img src="images/logo.png" alt="Logo" class="logo-img">
    </a>
    <div class="center-wrapper">
    <input type="text" class="search-box" id="search" placeholder="Search..." autocomplete="off">
    <div id="autocomplete-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; z-index: 1000;"></div>
  </div>

    <a href="perfil.php" class="user-icon">
      <i class="bi bi-person-circle"></i>
    </a>
  </div>

  <script type>

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

  <script type="module">

  document.getElementById("search").addEventListener("keydown", async function(event) {
    if (event.key === "Enter") {
      event.preventDefault(); 
      const query = this.value;
      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'searchPostsJS.php?query=' + encodeURIComponent(query), true);
      
      xhr.onreadystatechange = async function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);



            const resultsContainer =  document.getElementById('resultsContainer');

  
            if(results.length > 0){

              let i = 0;
  
              for (const item of results) {

                const resultContainerId =`modalMediaContainer${i}`;
                resultsContainer.innerHTML += `<div id="${resultContainerId}"></div>`;
                const resultContainer =  document.getElementById(resultContainerId);

                console.log(resultContainerId);
                const idPost = item.id;

                const { postData, userData, imageDetails } = await loadPostData(idPost);

                
                const mimeFilename= imageDetails.mimeFilename;
                const sizes = ['small', 'medium', 'large'];
                let size;

                if(mimeFilename == 'image'){
                  size = sizes[Math.floor(Math.random() * sizes.length)];
                }else if(mimeFilename == 'video'){
                  size = sizes[2];
                }else{
                  size = sizes[0];
                }

                resultContainer.innerHTML = `
                  <figure class="card card_" data-post-id="${idPost}">
                    <img src="showFileThumb.php?id=${imageDetails.id}&size=${size}" alt="Post">
                    <figcaption>Caption or title here</figcaption>
                  </figure>
                `;

                document.querySelectorAll('.card').forEach(card => {
                    card.addEventListener('click', async function() {
                        const postId = this.dataset.postId;
                        console.log('Post ID:', postId);
                        await openPost(postId); // now await works here
                    });
                });

                i++;

              }

              

            } else{
              resultsContainer.innerHTML = `<h1>No results</h1>`;
            }

            document.getElementById('results').checked = true;
            
          }
      };
      xhr.send();
    }
    

  });
  
  </script>