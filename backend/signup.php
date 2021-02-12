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
        <title>SIGN UP || Admin Panel</title>
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
                        if(isset($_POST['submit'])) {
                           
                            $first_name = trim($_POST['first-name']);
                            $last_name = trim($_POST['last-name']);
                            $full_name = $first_name . " " . $last_name;

                            $nickname = trim($_POST['nickname']);
                            // Check if nickname already exists
                            $sql2 = "SELECT * FROM users WHERE user_nickname = ?";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->execute([$nickname]);
                            $countNickname = count($stmt2->fetchAll());
                            if ($countNickname != 0) {
                                $error_nickname = "Nickname already exists!";
                                $error_check = True;
                            }

                            $email = trim($_POST['email']);
                            // Check if email already exists
                            $sql1 = "SELECT * FROM users WHERE user_email = ?";
                            $stmt1 = $pdo->prepare($sql1);
                            $stmt1->execute([$email]);
                            $countEmail = count($stmt1->fetchAll());
                            if ($countEmail != 0) {
                                $error_email = "Email already exists!";
                                $error_check = True;
                            }

                            $password = trim($_POST['password']);
                            $confirm_password = trim($_POST['confirm-password']);

                            if(empty($first_name)) {
                                $error_fname = "First name is required!";
                                $error_check = True;
                            }
                            if(empty($last_name)) {
                                $error_lname = "Last name is required!";
                                $error_check = True;
                            }
                            if(empty($nickname)) {
                                $error_nickname_empty = "Nickname is required!";
                                $error_check = True;
                            }
                            if(empty($email)) {
                                $error_email_empty = "Email is required!";
                                $error_check = True;
                            }
                            if(empty($confirm_password) || $confirm_password != $password) {
                                if ($confirm_password != $password) {
                                    $error_confirm = "";
                                } else {
                                $error_confirm = "";
                                }
                                $error_check = True;
                            }

                            if(empty($password) || $password != $confirm_password) {
                                if(empty($password)) {
                                    $error_pass = "Password is required!";
                                } else {
                                $error_pass = "Re-enter your password!";
                                }
                            }
                            if (!isset($error_check)) {
                                date_default_timezone_set('Asia/Hong_Kong');
                                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);
                                $sql = "INSERT INTO users (user_name, user_nickname, user_email, user_password, user_photo, registered_on) VALUES ( ?, ?, ?, ?, ?, ? )";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$full_name, $nickname, $email, $hash, 'default-logo.png', date('M j, Y') . ' at ' . date("g:i A")]);
                                $success = True;
                            }
                        }
                    ?>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="font-weight-light my-4">Create Account</h3></div>
                                    <div class="card-body">
                                        <form method="POST" action="signup.php">
                                            <?php 
                                                 if(isset($success)) {
                                                    echo "<p class='alert alert-success'>Successfully created an account. <a href='signin.php'>Sign in</a> now.</p>";
                                                }
                                            ?>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">First Name</label>
                                                        <input name="first-name" class="form-control py-4 <?php echo (isset($error_fname)) ? 'border border-danger' : '';?>" id="inputFirstName" type="text" placeholder="Enter first name" value="<?php echo (isset($_POST['first-name']) && !isset($success)) ? $_POST['first-name'] : ''; ?>"/>
                                                        <?php echo (isset($error_fname)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_fname}</p>" : '';?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputLastName">Last Name</label>
                                                        <input name="last-name" class="form-control py-4 <?php echo (isset($error_lname)) ? 'border border-danger' : '';?>" id="inputLastName" type="text" placeholder="Enter last name" value="<?php echo (isset($_POST['last-name']) && !isset($success)) ? $_POST['last-name'] : ''; ?>" />
                                                        <?php echo (isset($error_lname)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_lname}</p>" : '';?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputNickname">Nickname</label>
                                                <input name="nickname" class="form-control py-4 <?php echo (isset($error_nickname) || isset($error_nickname_empty)) ? 'border border-danger' : '';?>" id="inputNickname" type="text" placeholder="Enter nickname" value="<?php echo (isset($_POST['nickname']) && !isset($success)) ? $_POST['nickname'] : ''; ?>" />
                                                <?php echo (isset($error_nickname)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_nickname}</p>" : '';?>
                                                <?php echo (isset($error_nickname_empty)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_nickname_empty}</p>" : '';?>
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input name="email" class="form-control py-4 <?php echo (isset($error_email) || isset($error_email_empty)) ? 'border border-danger' : '';?>" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?php echo (isset($_POST['email']) && !isset($success)) ? $_POST['email'] : ''; ?>" />
                                                <?php echo (isset($error_email)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_email}</p>" : '';?>
                                                <?php echo (isset($error_email_empty)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_email_empty}</p>" : '';?>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">Password</label>
                                                        <input name="password" class="form-control py-4 <?php echo (isset($error_pass)) ? 'border border-danger' : '';?>" id="inputPassword" type="password" placeholder="Enter password" />
                                                        <?php echo (isset($error_pass)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_pass}</p>" : '';?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                                                        <input name="confirm-password" class="form-control py-4 <?php echo (isset($error_confirm)) ? 'border border-danger' : '';?>" id="inputConfirmPassword" type="password" placeholder="Confirm password" />
                                                        <?php echo (isset($error_confirm)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_confirm}</p>" : '';?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4 mb-0">
                                                <button type="submit" name="submit" class="btn btn-primary btn-block">Create Account</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="signin.php">Have an account? Go to signin</a></div>
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
