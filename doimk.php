<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role'])) {
?>

<!-- =============================================================================================== -->
<?php 
    require_once "class/User.php";
    $userError='';
    $passcError='';
    $passmError='';
    $passmcfError='';
    $loginError='';

    $user='';
    $passc='';
    $passm='';
    $passmcf='';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user=$_POST['user'];
        $passc=$_POST['passc'];
        $passm=$_POST['passm'];
        $passmcf=$_POST['passmcf'];

        if(empty($user)){
            $userError='Hãy nhập tên đăng nhập';
        }

        if(empty($passc)){
            $passcError='Hãy nhập mật khẩu';
        }

        if(empty($passm)){
            $passmError='Hãy nhập mật khẩu mới';
        }
        elseif(!preg_match("/^.{8,}$/", $passm)){
            $passmError = "Mật khẩu phải có ít nhất 8 ký tự";
        }

        if(empty($passmcf)){
            $passmcfError='Hãy nhập lại mật khẩu';
        }
        elseif($passm != $passmcf){
            $passmcfError = "Mật khẩu nhập lại không khớp";
        }

        if(empty($userError) && empty($passcError)&& empty($passmError)&& empty($passmcfError)) {
            $loginResult = User::isValid($pdo, $user, $passc);
            if($loginResult === true)
            {
                $us=User::getUser($pdo,$user, $passc);
                User::changePassword($pdo, $user, $passm);
                if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
            }else{
                $loginError='Sai tên đăng nhập hoặc mật khẩu';
            }


            
        }
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Đổi mật khẩu</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Đổi mật khẩu</h1>

                <form method="post">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên đăng nhập<sup>*</sup></label>
                                <input type="text" class="form-control" id="user" name="user" value="<?= $user ?>">
                                <span class="text-danger"><?= $userError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Mật khẩu cũ<sup>*</sup></label>
                                <input type="password" class="form-control" id="passc" name="passc">
                                <span class="text-danger"><?= $passcError ?></span>

                            </div>

                            <div class="form-item">
                                <label class="form-label my-3">Mật khẩu mới<sup>*</sup></label>
                                <input type="password" class="form-control" id="passm" name="passm">
                                <span class="text-danger"><?= $passmError ?></span>
                            </div>

                            <div class="form-item">
                                <label class="form-label my-3">Nhập lại mật khẩu mới<sup>*</sup></label>
                                <input type="password" class="form-control" id="passmcf" name="passmcf">
                                <span class="text-danger"><?= $passmcfError ?></span>

                                <span class="text-danger"><?= $loginError ?></span>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Xác nhận</button>
                            </div>

                        </div>
                         
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->

<!-- =============================================================================================== -->

<?php
} else {
    header("Location: 404.php");
    exit;
}
?>

<!-- =============================================================================================== -->
<?php 
    require_once "inc/footer.php";
?>