function openLoginForm() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }
  
  function closeLoginForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
  }


  function openSignupForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }

  function openSignupForm2() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }
  
  function openSignupForm3() {
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }

  function closeSignupForm() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
  }

function openUploadForm() {
  document.getElementById("uploadForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function closeUploadForm() {
  document.getElementById("uploadForm").style.display = "none";
  document.getElementById("formOverlay").style.display = "none";
  document.getElementById("home").checked = true;
}

function scrollToContact(){
  document.getElementById("contact").scrollIntoView({ behavior: "smooth" });
}
function openEditProfileForm() {
    document.getElementById("editProfileForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
  }

  function closeEditProfileForm() {
    document.getElementById("editProfileForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
  }

  function previewProfilePic(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('profilePreview').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

function openPost(postId) {
  const modal = document.getElementById('postModal');
  modal.style.display = "block";
  console.log('Post ID JS:', postId);

    // Seleciona o card correspondente pelo data-post-id
  const card = document.querySelector(`.card[data-post-id="${postId}"] img`);
  if (card) {
    document.getElementById('modalImage').src = card.src;
  }

  document.getElementById('currentPostId').value = postId;
  loadComments(postId);
}


function closePost() {
  const modal = document.getElementById('postModal');
  modal.style.display = 'none';
}

function togglePostMenu() {
  const menu = document.getElementById("postMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

// function addComment() {
//   const input = document.getElementById("newComment");
//   const commentList = document.getElementById("commentList");

//   if (input.value.trim() !== "") {
//     const newComment = document.createElement("div");
//     newComment.classList.add("comment");
//     newComment.innerHTML = `<strong>@you</strong> ${input.value}`;
//     commentList.appendChild(newComment);
//     input.value = "";
//   }
// }


function addComment() {
    const commentInput = document.getElementById('newComment');
    const commentText = commentInput.value.trim();
    const postId = document.getElementById('currentPostId').value;

    if (!commentText) {
        alert("Please enter a comment");
        return;
    }

    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('comment', commentText);

    fetch('addComment.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // para enviar cookies de sessão
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpa o input
            commentInput.value = '';

            // Atualiza a lista de comentários no modal
            loadComments(postId);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Fetch error:', err);
        alert('Error sending comment');
    });
}

function loadComments(postId) {
    fetch(`getComments.php?post_id=${postId}`, {
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(comments => {
        const commentList = document.getElementById('commentList');
        commentList.innerHTML = ''; // limpa a lista atual

        comments.forEach(comment => {
            const div = document.createElement('div');
            div.className = 'comment';
            div.innerHTML = `<strong>@${comment.username}</strong> ${comment.content}`;
            commentList.appendChild(div);
        });
    })
    .catch(err => {
        console.error('Error loading comments:', err);
    });
}
