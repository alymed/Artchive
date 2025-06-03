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

  function closeSignupForm() {
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
  }

function openUploadForm() {
  document.getElementById("uploadForm").style.display = "block";
  document.getElementById("uploadOverlay").style.display = "block";
}

function closeUploadForm() {
  document.getElementById("uploadForm").style.display = "none";
  document.getElementById("uploadOverlay").style.display = "none";
}



function openPost(imageSrc) {
  const modal = document.getElementById('postModal');
  const modalImage = document.getElementById('modalImage');

  modalImage.src = imageSrc;
  modal.style.display = 'flex';
}

function closePost() {
  const modal = document.getElementById('postModal');
  modal.style.display = 'none';
}

function togglePostMenu() {
  const menu = document.getElementById("postMenu");
  menu.style.display = menu.style.display === "block" ? "none" : "block";
}

function addComment() {
  const input = document.getElementById("newComment");
  const commentList = document.getElementById("commentList");

  if (input.value.trim() !== "") {
    const newComment = document.createElement("div");
    newComment.classList.add("comment");
    newComment.innerHTML = `<strong>@you</strong> ${input.value}`;
    commentList.appendChild(newComment);
    input.value = "";
  }
}
