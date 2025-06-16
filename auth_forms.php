<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/form-styles.css">


<?php session_start(); ?>
<?php if (!empty($_SESSION['login_error'])): ?>
    <div class="error-message">
        <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
    </div>
<?php endif; ?>
<div class="form-popup" id="loginForm">
    <form method="POST" class="form-container" action="login.php">
        <span class="close-icon" onclick="closeAllForms()">&times;</span>
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
        <span class="close-icon" onclick="closeAllForms()">&times;</span>
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
        <span class="close-icon" onclick="closeAllForms()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Create Your Account</h3>
        <div class="info">

            <label for="user_type"><b>User Type</b></label>
            <select name="user_type" id="user_type" required>
                <option value="" disabled selected>Select user type</option>
                <option value="supporter">Supporter</option>
                <option value="user">User</option>
                <option value="administrator">Administrator</option>
            </select>
            
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
        <input type="hidden" name="user_type" id="signup3_user_type" value="">
        <input type="hidden" name="username" id="signup3_username" value="">
        <input type="hidden" name="password" id="signup3_password" value="">

        <button type="button" class="back-icon" onclick="backToSignupForm2()" aria-label="Back">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <span class="close-icon" onclick="closeAllForms()">&times;</span>
        <img src="images/logo.png" alt="Logo" class="logo-img">

        <h3>Complete Your Profile</h3>
        <div class="info">
            <label for="profile_picture"><b>Profile Picture</b> <span class="optional-text">(Optional)</span></label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*">

            <label for="biography"><b>Biography</b> <span class="optional-text">(Optional)</span></label>
            <textarea name="biography" id="biography" placeholder="Tell us about yourself..." maxlength="90"> </textarea>
            <div class="char-counter" id="bioCounter">0 / 90</div>

            <button type="submit" class="default-btn" name="complete_profile">Complete Registration</button>

            <button type="button" class="skip-btn" onclick="skipProfileSetup()">Skip for now</button>
        </div>
    </form>
</div>





<script src="js/forms.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>