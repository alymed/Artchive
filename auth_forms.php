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

                <button type="submit" class="default-btn" name="login" id="login_button" >Log in</button>
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
                <input type="text" placeholder="Name" name="name" id="register_name" required>

                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Email" name="email" id="register_email1" required>

                <label for="birthdate"><b>Date of Birth</b></label>
                <input type="date" name="birthdate" id="register_birthdate" required>

                <button type="submit" class="default-btn" name="signup" /*onclick="openSignupForm2()"*/ id="register_button1" >Continue</button>
                <a class="action" id="signup" onclick="openLoginForm()">Already a member? Log in</a>

            </div>

        </form>
    </div>

    <div class="form-popup" id="signupForm2">
        <form method="post" class="form-container" action="signup.php">
            <span class="close-icon" onclick="closeSignupForm()">&times;</span>
            <img src="images/logo.png" alt="Logo" class="logo-img">

            <h3>Welcome to Artchive</h3>
            <div class="info">

       
                <label hidden for="name"><b>Name</b></label>
                <input hidden type="text" placeholder="Name" name="name" id="register_name2" required>

                <label hidden for="email"><b>Email</b></label>
                <input hidden type="text" placeholder="Email" name="email" id="register_email2" required>

                <label hidden for="birthdate"><b>Date of Birth</b></label>
                <input hidden type="date" name="birthdate" id="register_birthdate2" required>>

                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Username" name="username" id="register_username" required>

                <label for="password"><b>Password</b></label>
                <input type="password" placeholder="Password" name="password" id="register_password" required>


                <button type="submit" class="default-btn" name="signup" id="register_button2">Register</button>

            </div>

        </form>
    </div>