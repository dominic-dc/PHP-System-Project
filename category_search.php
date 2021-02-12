<?php $current_page = "Search result" ?>
<?php require_once("./includes/header.php"); ?>

        <div id="layoutDefault">
            <div id="layoutDefault_content">
                <main>
                    
                    <nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
                        <div class="container">
                            <a class="navbar-brand text-dark" href="index.php">TechBarik</a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><img src="img/menu.png" style="height:20px;width:25px" /><i data-feather="menu"></i></button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto mr-lg-5">
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.php">Home </a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link" href="contact.php">Contact</a>
                                    </li>
                                    <li class="nav-item dropdown no-caret">
                                        <a class="nav-link" href="about.php">About</a>
                                    </li>
                                </ul>
                                
                                <?php
                                    $curr_page = basename(__FILE__);
                                    require_once("./includes/auth.php"); 
                                ?>

                            </div>
                        </div>
                    </nav>

                    <?php
                        if(isset($_POST['search-keyword'])) {
                            $keyword = $_POST['search-keyword'];
                            $category_id = $_POST['category-id'];
                            $sql = "SELECT * FROM posts WHERE post_status = ? AND post_title LIKE ? AND post_category_id = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute(['Published', '%'. trim($keyword) .'%', $category_id]);
                            $post_found = 0;
                            $count = $stmt->rowCount();
                            if($count == 0) {
                                $post_found = 0;
                            } else {
                                $post_found = $count;
                            }
                        }
                    ?>

                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary">
                        <div class="page-header-content pt-10">
                            <div class="container text-center">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <h1 class="page-header-title mb-3">Search result for <?php echo $keyword; ?></h1>
                                        <p class="page-header-text">Total post found: <?php echo $post_found; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="svg-border-rounded text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </header>
                    <section class="bg-white py-10">
                        <div class="container">
                            
                            <h1>Search Result:</h1>
                            <hr />
                            <div class="row">

                                <?php
                                    $keyword = $_POST['search-keyword'];
                                    $category_id = $_POST['category-id'];
                                    $sql = "SELECT * FROM posts WHERE post_status = ? AND post_title LIKE ? AND post_category_id = ? ORDER BY post_id DESC LIMIT 6";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute(['Published', '%'. trim($keyword) .'%', $category_id]);
                                    $count = $stmt->rowCount();
                                    if($count == 0) {
                                        echo "No posts found! Try again!";
                                    } else {
                                        while($posts = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $post_id = $posts['post_id'];
                                            $post_title = $posts['post_title'];
                                            $post_detail = substr($posts['post_detail'], 0, 170); 
                                            $post_image = $posts['post_image']; 
                                            $post_date = $posts['post_date'];
                                            $post_author = $posts['post_author'];
                                            $post_views = $posts['post_views']; ?>

                                            <div class="col-md-6 col-xl-4 mb-5">
                                                <a class="card post-preview lift h-100" href="single.php?post_id=<?php echo $post_id; ?>"
                                                    ><img class="card-img-top" src="./img/<?php echo $post_image; ?>" alt="<?php echo $post_image; ?>" style="height: 40%"/>
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?php echo $post_title; ?></h5>
                                                        <p class="card-text"><?php echo $post_detail; ?>...</p>
                                                    </div>
                                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                                        <div class="post-preview-meta">
                                                            <img class="post-preview-meta-img" src="./img/mdabarik.jpg" />
                                                            <div class="post-preview-meta-details">
                                                                <div class="post-preview-meta-details-name"><?php echo $post_author; ?></div>
                                                                <div class="post-preview-meta-details-date"><?php echo $post_date; ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="post-preview-meta">
                                                            <i class="fas fa-eye mr-2" style="color: #000"></i><?php echo $post_views; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        <?php }
                                    }
                                ?>

                              
                            </div>

                            <nav aria-label="Page navigation example">
                                <ul class="pagination pagination-blog justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#!" aria-label="Previous"><span aria-hidden="true">&#xAB;</span></a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="#!">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#!">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#!">3</a></li>
                                    <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                                    <li class="page-item"><a class="page-link" href="#!">12</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#!" aria-label="Next"><span aria-hidden="true">&#xBB;</span></a>
                                    </li>
                                </ul>
                            </nav>

                        </div>

                        <div class="svg-border-rounded text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0" /></svg>
                        </div>
                    </section>
                </main>
            </div>
            <div id="layoutDefault_footer">
                <footer class="footer pt-4 pb-4 mt-auto bg-dark footer-dark">
                    <div class="container">
                        <hr class="my-1" />
                        <div class="row align-items-center">
                            <div class="col-md-6 small">Copyright &#xA9; Your Website 2020</div>
                            <div class="col-md-6 text-md-right small">
                                <a href="privacy_policy.php">Privacy Policy</a>
                                &#xB7;
                                <a href="terms_conditions.php">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

<?php require_once("./includes/footer.php"); ?>