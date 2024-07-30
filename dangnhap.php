<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_user'])) {
    header("Location: 404.php");
    exit;
}
?>

<!-- =============================================================================================== -->
<?php 
    require_once "class/User.php";
    $userError='';
    $passError='';
    $loginError='';

    $user='';
    $pass='';
    $us='';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user=$_POST['user'];
        $pass=$_POST['pass'];

        if(empty($user)){
            $userError='Hãy nhập tên đăng nhập';
        }

        if(empty($pass)){
            $passError='Hãy nhập mật khẩu';
        }

        if(empty($userError) && empty($passError)) {
            $loginResult = User::isValid($pdo, $user, $pass);
            if($loginResult === true)
            {
                $us=User::getUser($pdo,$user, $pass);
                $_SESSION['logged_us'] = $us;
                $_SESSION['logged_role'] = $us['role'];
                $_SESSION['logged_user'] = $user;
                if($_SESSION['logged_role']=="admin"){
                    header("Location: admin.php");
                    exit;
                }else{
                    header("Location: index.php");
                    exit;
                }
                
            }else{
                $loginError='Sai tên đăng nhập hoặc mật khẩu';
            }


            // header("Location: dangnhap.php");
            //exit;
        }
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Đăng nhập</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Đăng nhập</h1>

                <form method="post">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên đăng nhập<sup>*</sup></label>
                                <input type="text" class="form-control" id="user" name="user" value="<?= $user ?>">
                                <span class="text-danger"><?= $userError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Mật khẩu<sup>*</sup></label>
                                <input type="password" class="form-control" id="pass" name="pass">
                                <span class="text-danger"><?= $passError ?></span>

                                <span class="text-danger"><?= $loginError ?></span>
                                
                                </br>
                                <a href="quenmk.php">Quên mật khẩu ?</a>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Đăng nhập</button>
                            </div>

                        </div>
                         
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->


<!-- =============================================================================================== -->
<?php 
    require_once "inc/footer.php";
?>