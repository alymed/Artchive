function updateBiographyCounter() {
    const textarea = document.getElementById('biography');
    const counter = document.getElementById('bioCounter');
    counter.textContent = `${textarea.value.length} / 90`;
}

function backToPreviousForm() {
    closeAllForms();
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function backToSignupForm2() {
    closeAllForms();
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function skipProfileSetup() {
    document.getElementById('profile_picture').value = '';
    document.getElementById('biography').value = '';
    document.querySelector('#signupForm3 form').submit();
}

function closeAllForms() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

function openLoginForm() {
  document.getElementById("signupForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";
  document.getElementById("formOverlay").style.display = "block";
}

function openSignupForm() {
    closeAllForms();
    document.getElementById("signupForm").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function openSignupForm2(email = '', name = '', birthdate = '') {
    if (email) document.getElementById("signup2_email").value = email;
    if (name) document.getElementById("signup2_name").value = name;
    if (birthdate) document.getElementById("signup2_birthdate").value = birthdate;

    closeAllForms();
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

function openSignupForm3(email = '', name = '', birthdate = '', user_type = '', username = '', password = '') {
    if (email) document.getElementById("signup3_email").value = email;
    if (name) document.getElementById("signup3_name").value = name;
    if (birthdate) document.getElementById("signup3_birthdate").value = birthdate;
    if (user_type) document.getElementById("signup3_user_type").value = user_type;
    if (username) document.getElementById("signup3_username").value = username;
    if (password) document.getElementById("signup3_password").value = password;

    closeAllForms();
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}

window.addEventListener('DOMContentLoaded', function () {
    const bioTextarea = document.getElementById('biography');
    if (bioTextarea) {
        bioTextarea.addEventListener('input', updateBiographyCounter);
        updateBiographyCounter();
    }

    const urlParams = new URLSearchParams(window.location.search);
    const signupStep = urlParams.get('signupStep');

    if (signupStep === '2') {
        const email = urlParams.get('email');
        const name = urlParams.get('name');
        const birthdate = urlParams.get('birthdate');
        openSignupForm2(email, name, birthdate);
    }

    if (signupStep === '3') {
        const email = urlParams.get('email');
        const name = urlParams.get('name');
        const birthdate = urlParams.get('birthdate');
        const user_type = urlParams.get('user_type');
        const username = urlParams.get('username');
        const password = urlParams.get('password');
        openSignupForm3(email, name, birthdate, user_type, username, password);
    }

    const signupError = urlParams.get('signupError');
    if (signupError) {
        let errorMessage = '';
        switch (signupError) {
            case 'EmailInUse':
                errorMessage = 'This email is already registered. Please use a different email or login.';
                break;
            case 'UsernameInUse':
                errorMessage = 'This username is already taken. Please choose a different username.';
                break;
            case 'RegisterError':
                errorMessage = 'Registration failed. Please try again.';
                break;
            case 'InvalidInputs':
                errorMessage = 'Please fill in all required fields correctly.';
                break;
        }
        if (errorMessage) {
            alert(errorMessage);
        }
    }
});