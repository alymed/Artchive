function showMessage(message, type = 'info') {
    const messageContainer = document.getElementById("messageContainer");
    const messageText = document.getElementById("messageText");
    const messageContent = document.getElementById("messageContent");
    
    messageText.textContent = message;
    messageContent.className = `message-content ${type}`;
    messageContainer.style.display = "block";
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeMessage();
    }, 5000);
}

function closeMessage() {
    document.getElementById("messageContainer").style.display = "none";
}

// URL parameter handling for multi-step signup
function handleUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const signupStep = urlParams.get('signupStep');
    const signupError = urlParams.get('signupError');
    const username = urlParams.get('username');
    const password = urlParams.get('password');
    
    // Handle signup steps
    if (signupStep) {
        const email = urlParams.get('email') || '';
        const name = urlParams.get('name') || '';
        const birthdate = urlParams.get('birthdate') || '';
        
        switch (signupStep) {
            case '2':
                openSignupForm2(email, name, birthdate);
                break;
            case '3':
                openSignupForm3(username, password);
                break;
        }
    }
}

function openUploadForm() {
    document.getElementById("upload-file").value = "";
    document.getElementById("upload-title").value = "";
    document.getElementById("upload-category").value = "";
    document.getElementById("upload-description").value = "";

    document.getElementById("uploadForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function openSupporterForm() {
    document.getElementById("supporterForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function closeSupporterForm() {
    document.getElementById("supporterForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

function openAddCategoryForm() {
    document.getElementById("addCategoryForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function closeAddCategoryForm() {
    document.getElementById("addCategoryForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

function closeUploadForm() {
    document.getElementById("uploadForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
    document.getElementById("home").checked = true;
}

function scrollToContact() {
    document.getElementById("contact").scrollIntoView({ behavior: "smooth" });
}

async function openEditProfileForm(idUser) {
    document.getElementById("editProfileForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
    console.log("oi");

    try {
        const [userRes] = await Promise.all([fetch(`getUserDataJS.php?query=${encodeURIComponent(idUser)}`)]);
        const userData = await userRes.json();
        // Buscar dados do usuário
    
        if (!userRes.ok) {
            throw new Error(`HTTP error! Status: ${userRes.status}`);
        }
        console.log(userRes);

        // Preencher campos do formulário com os dados do usuário
        document.getElementById("name").value = userData.name || "";
        document.getElementById("username").value = userData.username || "";
        document.getElementById("bio").value = userData.biography || "";

        // Exibir o formulário
        
    } catch (error) {
        console.error("Erro ao carregar dados do usuário:", error);
        showMessage("Erro ao carregar dados do perfil. Tente novamente.", "error");
    }
}

function closeEditProfileForm() {
    document.getElementById("editProfileForm").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

// Função para fechar o modal
function closePost() {
    const modal = document.getElementById('postModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto'; // Restaura scroll da página
}

// Função para abrir o modal
function openPost(idPost, idUser) {
    const modal = document.getElementById('postModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Previne scroll da página

    const mediaContainer = document.getElementById("modalMediaContainer");
    if (likeButton) {
        mediaContainer.setAttribute('data-post-id', idPost);
    }
    
    const targetDiv = document.getElementById("modalMediaContainer");
    // Aqui você carregaria os dados do post
    updatePostModalFromData(idPost, idUser, targetDiv);
}

function openPostGuest(idPost) {
    const modal = document.getElementById('postModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Previne scroll da página

    const mediaContainer = document.getElementById("modalMediaContainer");
    if (likeButton) {
        mediaContainer.setAttribute('data-post-id', idPost);
    }
    
    const targetDiv = document.getElementById("modalMediaContainer");
    // Aqui você carregaria os dados do post
    updatePostModalFromDataGuest(idPost, targetDiv);
}

// Função para carregar dados do post
async function loadPostData(idPost, idLiker) {
    try {


        const [postRes, likeRes] = await Promise.all([
            fetch(`getPostDataJS.php?query=${encodeURIComponent(idPost)}`),
            fetch(`checkIfPostLikedJS.php?query1=${encodeURIComponent(idLiker)}&query2=${encodeURIComponent(idPost)}`)
        ]);


        const postData = await postRes.json();
        const isLiked = await likeRes.json();

     

        if (Object.keys(postData).length === 0) {
            console.log("No post data found.");
            return;
        }

        

        const { idUser, idImage, title, description, numLikes, numComments } = postData;

        const [userRes, imageRes] = await Promise.all([
            fetch(`getUserDataJS.php?query=${encodeURIComponent(idUser)}`),
            fetch(`getImageDetailsJS.php?query=${encodeURIComponent(idImage)}`)
        ]);

        const userData = await userRes.json();
        const imageDetails = await imageRes.json();



        // Store current post ID in modal for later use
        const modal = document.getElementById("postModal");
        if (modal) {
            modal.dataset.currentPostId = idPost;
        }

        // Atualizar o modal com os dados carregados
        return {postData, userData, imageDetails, isLiked};

    } catch (error) {
        console.error('Erro ao carregar post:', error);
    }
}

// Função para carregar dados do post
async function loadPostDataGuest(idPost) {
    try {


        const postRes = await fetch(`getPostDataJS.php?query=${encodeURIComponent(idPost)}`);


        const postData = await postRes.json();

        if (Object.keys(postData).length === 0) {
            console.log("No post data found.");
            return;
        }

        

        const { idUser, idImage, title, description, numLikes, numComments } = postData;

        const [userRes, imageRes] = await Promise.all([
            fetch(`getUserDataJS.php?query=${encodeURIComponent(idUser)}`),
            fetch(`getImageDetailsJS.php?query=${encodeURIComponent(idImage)}`)
        ]);

        const userData = await userRes.json();
        const imageDetails = await imageRes.json();



        // Store current post ID in modal for later use
        const modal = document.getElementById("postModal");
        if (modal) {
            modal.dataset.currentPostId = idPost;
        }

        // Atualizar o modal com os dados carregados
        return {postData, userData, imageDetails};

    } catch (error) {
        console.error('Erro ao carregar post:', error);
    }
}

// Função auxiliar para atualizar o modal (baseada na updatePostModal original)
async function updatePostModalFromData(idPost, idUser,  targetDiv) {

    const { postData, userData, imageDetails, isLiked} = await loadPostData(idPost, idUser);
    console.log(userData);
    if (Object.keys(imageDetails).length != 0) {

        const mimeFilename = imageDetails.mimeFilename;
  

        if (targetDiv) {

            targetDiv.innerHTML = getMultimediaFileHTML(mimeFilename, imageDetails);
            

            // Atualizar dados do usuário
            document.getElementById("modalUsername").textContent = userData.username;
            document.getElementById("likeCount").textContent = postData.numLikes;
            document.getElementById("commentCount").textContent = postData.numComments;

            // Atualizar foto de perfil
            const profilePicElement = document.getElementById("modalProfilePic");
            if (userData.profile_pic) {
                profilePicElement.src = userData.profile_pic;
            
            } else {
                profilePicElement.src = "images/profilePicHandler.jpg";
            }
            profilePicElement.alt = `${userData.username}'s profile picture`;

            // Atualizar caption com username e descrição
            const captionUsername = document.getElementById("captionUsername");
            const captionText = document.getElementById("captionText");
            
            if (captionUsername) {
                captionUsername.textContent = userData.username || 'Unknown User';
            }
            
            if (captionText) {
                captionText.textContent = postData.description || '';
            }

            // Atualizar título do post
            const postTitleElement = document.getElementById("modalPostTitle");
            if (postTitleElement) {
                postTitleElement.textContent = postData.title || '';
            }

            const likeButton = document.getElementById("likeButton");
            if(likeButton){

                if(isLiked){
                    likeButton.classList.add('liked');
                }else{
                    likeButton.classList.remove('liked');
                }
            }
        }
    }
}

async function updatePostModalFromDataGuest(idPost,  targetDiv) {

    const { postData, userData, imageDetails, isLiked} = await loadPostDataGuest(idPost);
    console.log(userData);
    if (Object.keys(imageDetails).length != 0) {

        const mimeFilename = imageDetails.mimeFilename;
  

        if (targetDiv) {

            targetDiv.innerHTML = getMultimediaFileHTML(mimeFilename, imageDetails);
            

            // Atualizar dados do usuário
            document.getElementById("modalUsername").textContent = userData.username;
            document.getElementById("likeCount").textContent = postData.numLikes;
            document.getElementById("commentCount").textContent = postData.numComments;

            // Atualizar foto de perfil
            const profilePicElement = document.getElementById("modalProfilePic");
            if (userData.profile_pic) {
                profilePicElement.src = userData.profile_pic;
            
            } else {
                profilePicElement.src = "images/profilePicHandler.jpg";
            }
            profilePicElement.alt = `${userData.username}'s profile picture`;

            // Atualizar caption com username e descrição
            const captionUsername = document.getElementById("captionUsername");
            const captionText = document.getElementById("captionText");
            
            if (captionUsername) {
                captionUsername.textContent = userData.username || 'Unknown User';
            }
            
            if (captionText) {
                captionText.textContent = postData.description || '';
            }

            // Atualizar título do post
            const postTitleElement = document.getElementById("modalPostTitle");
            if (postTitleElement) {
                postTitleElement.textContent = postData.title || '';
            }

            const likeButton = document.getElementById("likeButton");
            if(likeButton){

                if(isLiked){
                    likeButton.classList.add('liked');
                }else{
                    likeButton.classList.remove('liked');
                }
            }
        }
    }
}

function getMultimediaFileHTML(mimeFilename, imageDetails){

    let innerHTML = "";

    switch (mimeFilename) {
        case 'image':
            innerHTML = `<img src="showFile.php?id=${imageDetails.id}" alt="Post">`;
            break;

        case 'video':
            innerHTML = `
                    <div class="DBGVideoContainer MyVideoContainer">
                        <div class="DBGVideoPlayer MyVideoPlayer">
                            <video id="TheVideo" width="640" height="480" poster="showFileImage.php?id=${imageDetails.id}" controls >
                                <source src="showFile.php?id=${imageDetails.id}" />
                            </video>
                        </div>
                    </div>
                    `;
            break;

        case 'audio':
            innerHTML = `
                    <div class="audio-post">
                        <audio controls>
                            <source src="showFile.php?id=${imageDetails.id}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    `;
            break;
    }

    return innerHTML;

}





// Função para toggle like
async function toggleLike(idUser) {

    const idPost = document.getElementById("modalMediaContainer").dataset.postId;

    const currentIsLikedRes = await fetch(`checkIfPostLikedJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`)
    const currentIsLiked = await currentIsLikedRes.json();
    
    let toggleLikeRes;
    if(currentIsLiked){
        toggleLikeRes = await fetch(`dislikePostJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`);
    }else{
        toggleLikeRes = await fetch(`likePostJS.php?query1=${encodeURIComponent(idUser)}&query2=${encodeURIComponent(idPost)}`);
    }

    const toggleLike = await toggleLikeRes.json();

    if (toggleLike) {

        

        const likeButton = document.getElementById("likeButton");
        if (likeButton) {

            if (currentIsLiked) {
                likeButton.classList.remove('liked');
            } else {
                likeButton.classList.add('liked');
            }
        }

        const { postData, userData, imageDetails, isLiked} = await loadPostData(idPost, idUser);

        document.getElementById("likeCount").textContent = postData.numLikes;


        

    } else {
        console.log("Something was wrong with the like/dislike process");
    }





   
    
    
}

// Função para atualizar o botão de privacidade
function updatePrivacyButton(postData, userId) {
    const postMenu = document.getElementById("postMenu");
    if (!postMenu || !postData) return;

    // Procurar pelo botão de privacidade existente (buscar por qualquer botão que contenha "Make")
    let privacyButton = postMenu.querySelector('button:nth-child(2)'); // Segundo botão no menu
    
    // Se não encontrar, criar o botão
    if (!privacyButton) {
        privacyButton = document.createElement('button');
        postMenu.appendChild(privacyButton);
    }
    
    // Verificar se o usuário é o dono do post
    if (postData.idUser == userId) {
        privacyButton.style.display = 'block';
        
        // Configurar o botão baseado na privacidade atual
        if (postData.privacy === 'public') {
            privacyButton.innerHTML = '<i class="bi bi-lock"></i> Make Private';
        } else {
            privacyButton.innerHTML = '<i class="bi bi-unlock"></i> Make Public';
        }
    }
    // Se o usuário não for o dono do post, esconder o botão
    else {
        privacyButton.style.display = 'none';
    }
}

function handleShare() {
    if (navigator.share) {
        navigator.share({
            title: document.getElementById('modalPostTitle').textContent,
            url: window.location.href
        });
    } else {
        // Fallback para browsers que não suportam Web Share API
        navigator.clipboard.writeText(window.location.href);
        alert('Link copiado para a área de transferência!');
    }
}

// Função auxiliar para obter ID do post atual
function getCurrentPostId() {
    const modal = document.getElementById('postModal');
    return modal ? modal.dataset.currentPostId : null;
}

// Função para toggle do menu do post
function togglePostMenu() {
    const menu = document.getElementById('postMenu');
    if (menu) {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
}