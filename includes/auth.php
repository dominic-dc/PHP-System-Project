<?php

    if (isset($_POST['logout'])) {

        if(isset($_SESSION['login'])) {
            session_unset();
            session_destroy();
        }

        // Check cookie if it is set and unset it 
        if(isset($_COOKIE['_uid_']) && isset($_COOKIE['_uiid_'])) {
            setcookie('_uid_', '', time() - 60 * 60 * 24, '/', '', '', true);
            setcookie('_uiid_', '', time() - 60 * 60 * 24, '/', '', '', true);
        }
        
        header("Location: {$curr_page}");
    }

    if(isset($_SESSION['login'])) { 
        if(isset($_COOKIE['_uid_']) && isset($_COOKIE['_uiid_'])) {
            $user_id = base64_decode($_COOKIE['_uid_']);
            $user_nickname = base64_decode($_COOKIE['_uiid_']);
            $sql = "SELECT * FROM users WHERE user_id = ? AND user_nickname = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $user_nickname]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_name = $user['user_name'];
        }
        ?>
        <form action="<?php echo $curr_page; ?>" method="POST">
            <button name="logout" class="btn-teal btn rounded-pill px-4 ml-lg-4">Logout (<?php echo (isset($user_name)) ? $user_name : $_SESSION['user_name']; ?>)</button>
        </form>
<?php } else { 
            if (!isset($_COOKIE['_uid_']) && !isset($_COOKIE['_uiid_'])) { 
                echo '<a class="btn-teal btn rounded-pill px-4 ml-lg-4" href="backend/signin.php">Sign in</a>';
                echo '<a class="btn-teal btn rounded-pill px-4 ml-lg-4" href="backend/signup.php">Sign up</a>';
            } else {
                $user_id = base64_decode($_COOKIE['_uid_']);
                $user_nickname = base64_decode($_COOKIE['_uiid_']);
                $sql = "SELECT * FROM users WHERE user_id = ? AND user_nickname = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id, $user_nickname]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_name = $user['user_name']; ?>

                
        <?php }       
    } 
?> 