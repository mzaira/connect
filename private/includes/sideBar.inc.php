<?php

//Check if the user is loggedin
if (Login::isLoggedIn()) {
    //to get user_id and their profile_pic
    $id = Login::isLoggedIn();

    //to get the webpage URL
    $page = basename($_SERVER['REQUEST_URI']);

} else {
    //If not the user will be redirect to register.php page
    header('Location: register.php');
}
?>

<!--Navigation Div-->
<nav id="navigation">
    <div class="logo">
        <img class="logo" src="assets/img/others/logo.png">
        <h1>Connect</h1>
    </div>
    <div class="nav">
        <h1>Menu</h1>
        <!--To redirect them to their corresponding pages-->
        <!--To add "active class" if the page is on their corresponding pages-->
        <a href="<?php echo "profile.php?id=$id" ?>" class="<?php echo ($page == 'profile.php?id=' . $id) ? 'active' : ''; ?>">
            <figure><img src="<?php echo User::profileImage($id); ?>"></figure> <?php echo User::name($id); ?>
        </a>
        <a href="../connect" class="<?php echo ($page == 'connect' || $page == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home fa-fw"></i><span>Home</span></a>
        <a href="settings.php" class="<?php echo ($page == 'settings.php') ? 'active' : ''; ?>"><i class="fas fa-cog fa-fw"></i><span>Settings</span></a>
        <!--To open the logout modal-->
        <form action="index.php" method="post">
            <button type="submit" name="logout"><i class="fas fa-power-off fa-fw"></i><span>Logout</span></button>
        </form>
    </div>
</nav>