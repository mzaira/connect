<?php
ob_start();
session_start();

//VARIABLE: error array
$error_array = array();

//INCLUDE: config (database), login, mail, user
require_once 'private/classes/config.php';
require_once 'private/classes/login.php';
require_once 'private/classes/mail.php';
require_once 'private/classes/user.php';

//FORM HANDLER: signin, signup, forgot
require_once 'private/form_handler/signin.inc.php';
require_once 'private/form_handler/signup.inc.php';
require_once 'private/form_handler/forgot.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In or Sign Up | Connect</title>
    <link rel="shortcut icon" href="assets/img/others/logoI.ico" />

    <!--EXTERNAL RESOURCES: font, jQuery, fontAwesome-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/035ed2373e.js" crossorigin="anonymous"></script>

    <!--CSS SOURCE: main.css-->
    <link href="assets/scss/main.css" type="text/css" rel="stylesheet">

    <!--JAVASCRIPT: register.js-->
    <script src="private/js/register.js"></script>
</head>

<body>

    <!--DIV: to show and hide elements-->
    <?php
    //EXPLANATION: if signup button is clicked the signupContainer will show, 
    //             so that if there are any error, it can directly show up
    if (isset($_POST['signup'])) {
        echo '
		<script>
		$(document).ready(function() {
			$("#signinContainer").hide();
			$("#signupContainer").show();
		});
		</script>
		';
    }
    ?>

    <!--MODAL: Forgot Password-->
    <div id="forgot-password">
        <!--MODAL CLOSE-->
        <div id="overlay" onclick="closeforgot()"></div>
        <div class="container">
            <!--MODAL CLOSE-->
            <button onclick="closeforgot()"><i class="fas fa-times-circle"></i></button>
            <div class="forgot-form">
                <h1>Don't Worry</h1>
                <p>We are here to help you to recover your password.
                    Enter the email address you used when you joined and we'll send you
                    instructions to reset your password</p>
                <div class="line">
                    <!--ERROR ARRAY: show message if sent successfully-->
                    <?php
                    //EXPLANATION: if the mail has been successfully, a message will pop-up
                    if (in_array("Message sent", $error_array)) {
                        echo "<script> forgotPassword(); </script><p class='message'>Message Sent</p>";
                    } else if (in_array("Message could not be sent.", $error_array)) {
                        echo "<script> forgotPassword(); </script><p class='message'>Message could not be sent.</p>";
                    }
                    ?>
                </div>
                <!--FORM: Reset Password-->
                <form action="register.php" method="post">
                    <div class="input-group">
                        <input type="email" name="forgot-email" value="" required>
                        <label>Email</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("Email doesn't exist", $error_array)) {
                            echo "<script> forgotPassword(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email doesn't exist</span>
                        </div>";
                        } ?>
                    </div>
                    <input type="submit" name="resetpassword" value="Send" class="modal-submit">
                </form>
            </div>
            <figure>
                <img src="assets/img/others/forgotPassword.png">
            </figure>
        </div>
    </div>

    <!--DIV: Main Register Container-->
    <div class="registerContainer">
        <div class="headerContainer">
            <div class="logoContainer">
                <img class="logo" src="assets/img/others/logo.png">
                <h1>CONNECT</h1>
            </div>
        </div>
        <div class="imageContainer">
            <img src="assets/img/others/home.png">
        </div>
        <div class="form">
            <!--DIV: Sign In Container-->
            <div id="signinContainer" class="formContainer">
                <div class="header">
                    <p>Social Media</p>
                    <h1>Sign In</h1>
                </div>
                <!--FORM: Sign In-->
                <form action="register.php" method="post">
                    <!--DIV: saved session-->
                    <div class="input-group">
                        <input type="text" name="emailUsername" value="<?php
                            //EXPLANATION: if the session['emailUsername'] has been saved, the value will appear here
                            if (isset($_SESSION['emailUsername'])) {
                            echo $_SESSION['emailUsername'];
                            } ?>" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Email or Username</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("Email or Username doesn't exist2", $error_array)) {
                            echo "<div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email or Username doesn't exist</span>
                        </div>";
                        } ?>
                    </div>
                    <div class="input-group">
                        <input value="" type="password" name="password" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Password</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("Incorrect password", $error_array)) {
                            echo "<div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Incorrect password or username or email</span>
                        </div>";
                        } ?>
                    </div>
                    <!--CHECKBOX: to remember the email / username-->
                    <div class="input-group checkbox">
                        <input type="checkbox" name="remember" value="true">
                        <label>Remember me</label>
                    </div>
                    <input type="submit" name="signin" value="Sign In" class="submit">
                </form>
                <div class="registerBtns">
                    <!--MODAL OPEN-->
                    <button class="registerBtn" onclick="forgotPassword()">Forgot Password</button>
                    <!--DIV: slide up to Sign Up-->
                    <button class="registerBtn" id="signupBtn">Create New Account</button>
                </div>
            </div>

            <!--DIV: Sign Up Container-->
            <div id="signupContainer" class="formContainer">
                <div class="header">
                    <p>Social Media</p>
                    <h1>Sign Up</h1>
                </div>
                <!--FORM: Sign Up-->
                <form action="register.php" method="post">
                    <div class="input-group">
                        <input value="" type="text" name="name" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Name</label>
                        <!--ERROY ARRAY-->
                        <?php if (in_array("Name must exceed 3 characters", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Name must exceed 3 characters</span>
                        </div>";
                        } ?>
                    </div>
                    <div class="input-group">
                        <input value="" type="email" name="email" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Email</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("mail input not validated", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email input not validated</span>
                        </div>";
                        } else if (in_array("Email is already registered", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email is already registered</span>
                        </div>";
                        } ?>
                    </div>
                    <div class="input-group">
                        <input value="" type="text" name="username" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Username</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("mail input not validated", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email input not validated</span>
                        </div>";
                        } else if (in_array("Email is already registered", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Email is already registered</span>
                        </div>";
                        } ?>
                    </div>
                    <div class="input-group">
                        <input value="" type="password" name="password" onkeyup="this.setAttribute('value', this.value);" required>
                        <label>Password</label>
                        <!--ERROR ARRAY-->
                        <?php if (in_array("Password must contain letters and numbers only", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Password must contain letters and numbers only</span>
                        </div>";
                        } else if (in_array("Password must be between 6 to 60 characters", $error_array)) {
                            echo "<script> signupBtn(); </script>
                        <div class='error'>
                        <i class='fas fa-exclamation-circle fa-fw'></i>
                        <span>Password must be between 6 to 60 characters</span>
                        </div>";
                        } ?>
                    </div>
                    <input type="submit" name="signup" value="Sign Up" class="submit">
                </form>
                <div class="registerBtns">
                    <!--DIV: slide up to Sign In-->
                    <button class="registerBtn" id="signinBtn">Already have an account?</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>