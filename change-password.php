<?php
//VARIABLE: error array
$error_array = array();

//INCLUDE: config(database)
require_once('private/classes/config.php');
require_once('private/classes/login.php');

//FORM HANDLER: change
require_once 'private/form_handler/change.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password | Connect</title>
    <link rel="shortcut icon" href="assets/img/others/logoI.ico" />

    <!--EXTERNAL RESOURCES: font, jQuery, fontAwesome-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/035ed2373e.js" crossorigin="anonymous"></script>

    <!--CSS SOURCE: main.css-->
    <link href="assets/scss/main.css" type="text/css" rel="stylesheet">
</head>

<body>
    <div class="mainContainer">
        <!--DIV: Main Change-Password Container-->
        <div class="changeContainer">
            <div class="container">
                <div class="form2">
                    <div class="cheaderContainer">
                        <div class="logoContainer">
                            <img class="logo" src="assets/img/others/logo.png">
                            <h1>CONNECT</h1>
                        </div>
                    </div>
                    <!--FORM: changing password-->
                    <form action="<?php echo 'change-password.php?token=' . $token . ''; ?>" method="post">
                        <h1>Change Password</h1>
                        <div class="input-group">
                            <input value="" type="password" name="newPassword" onkeyup="this.setAttribute('value', this.value);" required>
                            <label>New Password</label>
                            <!--ERROR ARRAY-->
                            <?php if (in_array("Password must be between 6 to 60 characters", $error_array)) {
                                echo "<div class='error'> <i class='fas fa-exclamation-circle fa-fw'></i>
                            <p>Password must be between 6 to 60 characters</p> </div>";
                            } else if (in_array("Password must not be the same as your old password", $error_array)) {
                                echo "<div class='error'> <i class='fas fa-exclamation-circle fa-fw'></i>
                            <p>Password must not be the same as your old password</p> </div>";
                            } ?>
                        </div>
                        <div class="input-group">
                            <input value="" type="password" name="retypePassword" onkeyup="this.setAttribute('value', this.value);" required>
                            <label>Retype Password</label>
                            <!--ERROR ARRAY-->
                            <?php if (in_array("Password don't match", $error_array)) {
                                echo "<div class='error'> <i class='fas fa-exclamation-circle fa-fw'></i>
                            <p>Password don't match</p> </div>";
                            } else if (in_array("Password must contain letters and numbers only", $error_array)) {
                                echo "<div class='error'> <i class='fas fa-exclamation-circle fa-fw'></i>
                            <p>Password must contain letters and numbers only</p> </div>";
                            }; ?>
                        </div>
                        <div class="input-group">
                            <input value="" type="text" name="code" onkeyup="this.setAttribute('value', this.value);" required>
                            <label>Code</label>
                            <!--ERROR ARRAY-->
                            <?php if (in_array("Incorrect Code", $error_array)) {
                                echo "<div class='error'> <i class='fas fa-exclamation-circle fa-fw'></i>
                            <p>Incorrect Code</p> </div>";
                            } ?>
                        </div>
                        <input type="submit" name="changePassword" value="Submit" class="forgot-submit">
                        <div class="btnContainer">
                        <!--BUTTON: to cancel the change password-->
                            <button name="cancelChange"><i class="fas fa-chevron-left"></i><span>Back</span></button>
                        </div>
                    </form>
                </div>
                <figure>
                    <img src="assets/img/others/forgotPassword.png">
                </figure>
            </div>
        </div>
    </div>
</body>

</html>