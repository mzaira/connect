<?php
$pagename = 'Settings | Connect';
require 'private/includes/header.inc.php';
require 'private/includes/settings.inc.php';
?>
<body>
    <div class="mainContainer">
        <?php require 'private/includes/navigation.inc.php';
        require 'private/includes/logout.inc.php'; ?>

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
                <a href="<?php echo "profile.php?id=$id" ?>">
                    <figure><img src="<?php echo User::profileImage($id); ?>"></figure> <?php echo User::name($id); ?>
                </a>
                <a href="../connect"><i class="fas fa-home fa-fw"></i><span>Home</span></a>
                <a href="settings.php" class="active"><i class="fas fa-cog fa-fw"></i><span>Settings</span></a>
                <!--To open the logout modal-->
                <form action="index.php" method="post">
                    <button type="submit" name="logout"><i class="fas fa-power-off fa-fw"></i><span>Logout</span></button>
                </form>
            </div>
        </nav>

        <div class="scontainer" style="padding-right: 0px; display:flex; flex-direction:column; height:100vh; ">
            <div class="settingContainer">
                <div class="tabs">
                    <form action="settings.php">
                        <a href="settings.php" class="<?php if($active == 'edit'){ echo 'active'; }?>">Edit Tab</a>
                        <button type="submit" name="tab" value="sec" class="<?php if($active == 'sec'){ echo 'active'; }?>">Privacy & Security</button>
                    </form>
                </div>
                <div class="settingsContainer">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
</body>

</html>