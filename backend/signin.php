<?php session_start(); ?>
<?php require_once('../includes/db.php'); ?>

<?php 

    if(isset($_SESSION['login'])) {
        header("Location: ../index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>SIGN IN || Admin Panel</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/all.min.js"></script>
        <script src="js/feather.min.js"></script>
    </head>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>

                <?php

                if (isset($_POST['submit'])) {
                    $email = trim($_POST['email']);
                    $password = trim($_POST['password']);
                    
                    // Check if email exists
                    if (empty($email)) {
                        $empty_email = "Email is required.";
                    } else {
                        $sql = "SELECT * FROM users WHERE user_email = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$email]);
                        $count = $stmt->rowCount();
                        if ($count != 1) {
                            $error = "Invalid email or password.";
                        }
                    }

                    
                    // Check if password is correct
                    if (empty($password)) {
                        $empty_password = "Password is required.";
                    } else if (!empty($email) && !isset($error)) {
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        $user_password_hash = $user['user_password'];
                        $user_name = $user['user_name'];
                        $user_role = $user['user_role'];
                        if (password_verify($password, $user_password_hash)) {
                            if(!empty($_POST['check'])) {
                                $user_id = $user["user_id"];
                                $user_nickname = $user["user_nickname"];
                                $d_user_id = base64_encode($user_id);
                                $d_user_nickname = base64_encode($user_nickname);
                                // User_id
                                setcookie('_uid_', $d_user_id, time() + 60 * 60 * 24, '/', '', '', true);
                                // User_nickname
                                setcookie('_uiid_', $d_user_nickname, time() + 60 * 60 * 24, '/', '', '', true);
                            }
                            $_SESSION['user_name'] = $user_name;
                            $_SESSION['user_role'] = $user_role;
                            $_SESSION['login'] = 'success';
                            header("Location:../index.php");
                        } else {
                            $error = "Invalid email or password.";
                        }
                    }

                    

                }

                ?>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="font-weight-light my-4">SIGN IN</h3></div>
                                    <div class="card-body">
                                        <form action="signin.php" method="POST">
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4 <?php echo (isset($empty_email) || (isset($error) && !isset($empty_password))) ? 'border border-danger' : '';?>" id="inputEmailAddress" name="email" type="email" placeholder="Enter email address" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : '';?>"/>
                                                <?php echo (isset($empty_email)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$empty_email}</p>" : '';?>
                                                <?php echo (isset($error) && !isset($empty_password)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error}</p>" : '';?>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4 <?php echo (isset($empty_password) || (isset($error) && !isset($empty_email))) ? 'border border-danger' : '';?>" id="inputPassword" name="password" type="password" placeholder="Enter password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : '';?>" />
                                                <?php echo (isset($empty_password)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$empty_password}</p>" : '';?>
                                                <?php echo (isset($error) && !isset($empty_email) && !isset($empty_password)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error}</p>" : '';?>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                <input name="check" class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                                <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label>
                                            </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                                <button type="submit" class="btn btn-primary btn-block" name="submit">SIGN IN</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center d-flex justify-content-between">
                                        <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
                                        <div class="small"><a href="forgot-password.php">Forgot Password?</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!--Script JS-->
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
