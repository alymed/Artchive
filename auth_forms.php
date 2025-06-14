<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.form-popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 3px solid white;
    z-index: 999;
    background-color: white;
    padding: 20px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.7);
    border-radius: 30px;
}

/* Add styles to the form container */
.form-container {
    width: 450px;
    padding: 10px;
    background-color: white;
}

/* Full-width input fields */
.form-container input[type="text"],
.form-container input[type="email"],
.form-container input[type="password"],
.form-container input[type="date"],
.form-container input[type="file"],
.form-container textarea {
    width: 60%;
    padding: 12px;
    margin: 5px 0 3px 0;
    border: 1px solid gray;
    background: #fff;
    border-radius: 16px;
}

/* When the inputs get focus, do something */
.form-container input[type="text"]:focus,
.form-container input[type="email"]:focus,
.form-container input[type="password"]:focus,
.form-container input[type="date"]:focus,
.form-container input[type="file"]:focus,
.form-container textarea:focus {
    background-color: #ddd;
    outline: none;
}

/* Set a style for the submit/login button */
.default-btn {
    background-color: #0a2c5a;
    color: white;
    padding: 16px 20px;
    border: none;
    cursor: pointer;
    width: 60%;
    margin-bottom: 10px;
    border-radius: 25px;
}

/* Add some hover effects to buttons */
.default-btn:hover,
.open-button:hover {
    background-color: #071e3e;
    color: white;
}

.close-icon {
    position: absolute;
    top: 5px;
    right: 15px;
    font-size: 27px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
}

.close-icon:hover {
    color: #071e3e;
}

.back-icon {
    position: absolute;
    top: 10px;
    left: 15px;
    background: none;
    border: none;
    font-size: 20px;
    color: #aaa;
    cursor: pointer;
    
}

.back-icon i:hover {
    color: #071e3e;
}

.form-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    /* semi-transparent black */
    z-index: 998;
    /* behind the popup */
    display: none;
    /* hidden by default */
}

.form-popup .logo-img {
    width: 70px;
    /* ou o valor que quiser */
    height: auto;
    display: block;
    margin: 0 auto 20px auto;
}

.form-container h3 {
    text-align: center;
    margin-bottom: 20px;
}

.form-container label {
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
}

.form-container .info {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.form-container label,
.form-container .action {
    align-self: flex-start;
    margin-left: 20%;
}

.form-container .action {
    padding: 0;
    text-decoration: none;
    font-weight: normal;
    color: #0a2c5a;
}

.form-container .action#signup {
    margin-bottom: 20px;
}

.form-container .action:hover {
    text-decoration: underline;
}

.password-hint{
    width: 65%;
    padding: 12px;
    margin: 5px 0 3px 0;
}
</style>


<!-- The form -->
<div class="form-popup" id="loginForm">
    <form method="POST" class="form-container" action="login.php">
        <span class="close-icon" onclick="closeLoginForm()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Login to see more</h3>
        <div class="info">
            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Email" name="email" id="login_email" required>
            <label for="passe"><b>Password</b></label>
            <input type="password" placeholder="Password" name="password" id="login_password" required>
            <a href="#" class="action" id="password">Forgot your password?</a>
            <div class="g-recaptcha" data-sitekey="6LcQV10rAAAAAB-XprIAz5u2HPQ6aZu4QRY6UyYw" required></div>
            <button type="submit" class="default-btn" name="login" id="login_button">Log in</button>
            <a class="action" id="signup" onclick="openSignupForm()">Not on Artchive yet? Sign up</a>
        </div>

    </form>
</div>

<div class="form-popup" id="signupForm">
    <form method="post" class="form-container" action="signup_emailvalidation.php">
        <span class="close-icon" onclick="closeSignupForm()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Welcome to Artchive</h3>
        <div class="info">
            <label for="name"><b>Name</b></label>
            <input type="text" placeholder="Name" name="name" id="register_name" required minlength="2" maxlength="50"
                pattern="[a-zA-Z\s\-\'\.]+">

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Email" name="email" id="register_email" required>

            <label for="birthdate"><b>Date of Birth</b></label>
            <input type="date" name="birthdate" id="register_birthdate" required>

            <button type="submit" class="default-btn" name="signup" id="register_button1">Continue</button>
            <a class="action" id="signup" onclick="openLoginForm()">Already a member? Log in</a>
        </div>
    </form>
</div>

<div class="form-popup" id="signupForm2">
    <form method="post" class="form-container" action="signup.php">
        <input type="hidden" name="email" id="signup2_email" value="">
        <input type="hidden" name="name" id="signup2_name" value="">
        <input type="hidden" name="birthdate" id="signup2_birthdate" value="">
        <button type="button" class="back-icon" onclick="backToPreviousForm()" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="close-icon" onclick="closeSignupForm()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Create Your Account</h3>
        <div class="info">

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Username" name="username" id="register_username" required minlength="3"
                maxlength="30" pattern="[a-zA-Z0-9_]+"
                title="Username must be 3-30 characters and contain only letters, numbers, and underscores">

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Password" name="password" id="register_password" required minlength="8"
                pattern="^(?=.*[A-Za-z])(?=.*\d).*$"
                title="Password must be at least 8 characters long and contain at least one letter and one number">

            <small class="password-hint">Password must be at least 8 characters with at least one letter and one
                number</small>

            <button type="submit" class="default-btn" name="signup" id="register_button2">Register</button>

        </div>

    </form>
</div>

<div class="form-popup" id="signupForm3">
    <form method="post" class="form-container" action="complete_profile.php" enctype="multipart/form-data">
        <input type="hidden" name="name" id="signup3_name" value="">
        <input type="hidden" name="email" id="signup3_email" value="">
        <input type="hidden" name="birthdate" id="signup3_birthdate" value="">
        <input type="hidden" name="username" id="signup3_username" value="">
        <input type="hidden" name="password" id="signup3_password" value="">

        <button type="button" class="back-icon" onclick="backToSignupForm2()" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="close-icon" onclick="closeSignupForm()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Complete Your Profile</h3>
        <div class="info">
            <label for="profile_picture"><b>Profile Picture</b> <span class="optional-text">(Optional)</span></label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">

            <label for="biography"><b>Biography</b> <span class="optional-text">(Optional)</span></label>
            <textarea name="biography" id="biography" placeholder="Tell us about yourself..."></textarea>

            <button type="submit" class="default-btn" name="complete_profile">Complete Registration</button>

            <button type="button" class="skip-btn" onclick="skipProfileSetup()">Skip for now</button>
        </div>
    </form>
</div>

<script>
// Back button functions
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
    // Remove inputs opcionais para garantir que são ignorados
    document.getElementById('profile_picture').value = '';
    document.getElementById('biography').value = '';

    // Envia o formulário normalmente
    document.querySelector('#signupForm3 form').submit();
}

function closeAllForms() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("signupForm").style.display = "none";
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "none";
    document.getElementById("formOverlay").style.display = "none";
}

function openSignupForm2(email = '', name = '', birthdate = '') {
    // Populate hidden fields with data from step 1
    if (email) document.getElementById("signup2_email").value = email;
    if (name) document.getElementById("signup2_name").value = name;
    if (birthdate) document.getElementById("signup2_birthdate").value = birthdate;

    closeAllForms();
    document.getElementById("signupForm2").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";

    console.log("Going to Step 2:", email, name, birthdate);
}

function openSignupForm3(email = '', name = '', birthdate = '', username = '', password = '') {
    // Populate hidden field with user ID
    if (email) document.getElementById("signup3_email").value = email;
    if (name) document.getElementById("signup3_name").value = name;
    if (birthdate) document.getElementById("signup3_birthdate").value = birthdate;
    if (username) document.getElementById("signup3_username").value = username;
    if (password) document.getElementById("signup3_password").value = password;


    closeAllForms();
    document.getElementById("signupForm2").style.display = "none";
    document.getElementById("signupForm3").style.display = "block";
    document.getElementById("formOverlay").style.display = "block";
}
// Handle URL parameters for signup steps
window.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const signupStep = urlParams.get('signupStep');

    if (signupStep === '2') {
        // Populate form with data from URL parameters
        const email = urlParams.get('email');
        const name = urlParams.get('name');
        const birthdate = urlParams.get('birthdate');

        // You would typically store this data and move to step 2
        openSignupForm2(email, name, birthdate);
    }

    if (signupStep === '3') {
        // Populate form with data from URL parameters
        const email = urlParams.get('email');
        const name = urlParams.get('name');
        const birthdate = urlParams.get('birthdate');
        const username = urlParams.get('username');
        const password = urlParams.get('password');

        // You would typically store this data and move to step 2
        openSignupForm3(email, name, birthdate, username, password);
    }

    // Handle signup errors
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
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>