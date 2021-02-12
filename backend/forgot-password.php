<?php require_once('../includes/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Recover Your Password || Admin Panel</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
        <script data-search-pseudo-elements defer src="js/all.min.js"></script>
        <script src="js/feather.min.js"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><h3 class="font-weight-light my-4">Password Recovery</h3></div>
                                    <div class="card-body">
                                    <div class="small mb-3 text-muted">Enter your email address and we will send you a link to reset your password.</div>
                                    
                                    <?php
                                        if(isset($_POST['reset'])) {
                                            $email = trim($_POST['email']);

                                            // Check if email field is empty
                                            if(empty($email)) {
                                                $error_email = "Email is required.";
                                            }
                                            
                                            // Make sure email field is not empty
                                            if(!isset($error_email)) {
                                                $sql = "SELECT * FROM users WHERE user_email = ?";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([$email]);
                                                $count = $stmt->rowCount();
                                            
                                                if($count == 1) {
                                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    $user_id = $user['user_id'];
                                                    $show = True;
                                                } else {
                                                    echo "<p class='alert alert-danger'>The email address does not exists in our database.</p>";
                                                }
                                            }
                                        }
                                        
                                        if(isset($_POST['update'])) {
                                            $password = trim($_POST['password']);
                                            $confirm_password = trim($_POST['confirm-password']);
                                            $user_id = $_POST['id'];
                                            $show = True;
                                            if($password == $confirm_password) {
                                                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost'=>10]);
                                                $sql = "UPDATE users SET user_password = ? WHERE user_id = ?";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute([$hash, $user_id]);
                                                echo "<p class='alert alert-success'>Password successfully updated. <a href='signin.php'>Login now.</a></p>";
                                            } else {
                                                echo "<p class='alert alert-danger'>Password does not match.</p>";
                                            }
                                        }
                                    ?>

                                    <?php
                                        if(!isset($show)) { ?>
                                            <form action="forgot-password.php" method="POST">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                    <input name="email" class="form-control py-4 <?php echo (isset($error_email)) ? 'border border-danger' : ''; ?>" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ''; ?>"/>
                                                    <?php echo (isset($error_email)) ? "<p class='small mt-2 ml-1 font-italic font-weight-light text-danger'>{$error_email}</p>" : '';?>
                                                </div>
                                                <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                    <a class="small" href="signin.php">Return to sign-in</a>
                                                    <button class="btn btn-primary" type="submit" name="reset">Reset Password</button>
                                                </div>
                                            </form>
                                       <?php } else { ?>
                                            <form action="forgot-password.php" method="POST">
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="<?php echo $user_id; ?>">
                                                    <label class="small mb-1" for="password">Password</label>
                                                    <input name="password" class="form-control py-4" id="password" type="password" placeholder="Enter new password" required="true" />
                                                </div>
                                                <div class="form-group">
                                                    <label class="small mb-1" for="confirm-password">Confirm Password</label>
                                                    <input name="confirm-password" class="form-control py-4" id="confirmpassword" type="password" placeholder="Confirm your password" required="true" />
                                                </div>
                                                <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                                    <button class="btn btn-primary" type="submit" name="update">Update Password</button>
                                                </div>
                                            </form>
                                     <?php  }
                                    ?>

                                        
                                        
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="signup.php">Need an account? Sign up!</a></div>
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
