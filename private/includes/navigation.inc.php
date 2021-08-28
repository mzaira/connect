<nav id="navigation3">
    <div class="buttons">
        <div class="searchContainer">
            <form action="search.php" method="GET" name="search_form">
                <div class="input-group">
                    <input value="" type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>'), this.setAttribute('value', this.value);" name="q" autocomplete="off">
                    <label><i class="fas fa-search"></i> Search</label>
                </div>
            </form>

            <div class="searchContent">
                <div class="searchResult"></div>
                <div class="searchResultFooter"></div>
            </div>
        </div>
        <button id="notification"><i class="fas fa-bell fa-fw"></i></button>
    </div>
</nav>