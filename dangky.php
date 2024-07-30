<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "user") {
    header("Location: 404.php");
    exit;
}
?>

<!-- =============================================================================================== -->

<?php 
    require_once "class/User.php";
    $userError='';
    $gioitinhError='';
    $emailError='';
    $passError='';
    $passcfError='';

    $user='';
    $gioitinh='';
    $email='';
    $pass='';
    $passcf='';
    $role='';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user=$_POST['user'];
        $gioitinh=$_POST['gioitinh'];
        $email=$_POST['email'];
        $pass=$_POST['pass'];
        $passcf=$_POST['passcf'];
        $role=$_POST['role'];

        if(empty($user)){
            $userError='Hãy nhập tên đăng nhập';
        }elseif(User::isUsernameExists($pdo, $user, 0)){
            $userError='Tên đăng nhập đã tồn tại';
        }

        if(empty($email)){
            $emailError='Hãy nhập email';
        }
        elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
            $emailError='Email không hợp lệ';
        }elseif(User::isEmailExists($pdo, $email, 0)){
            $emailError='Email đã tồn tại';
        }

        if(empty($pass)){
            $passError='Hãy nhập mật khẩu';
        }
        elseif(!preg_match("/^.{8,}$/", $pass)){
            $passError = "Mật khẩu phải có ít nhất 8 ký tự";
        }

        if(empty($gioitinh)){
            $gioitinhError='Hãy chọn giới tính';
        }

        if(empty($passcf)){
            $passcfError='Hãy nhập lại mật khẩu';
        }
        elseif($pass != $passcf){
            $passcfError = "Mật khẩu nhập lại không khớp";
        }

        if(empty($userError) && empty($emailError) && empty($passError) && empty($passcfError)&& empty($roleError)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            User::addUser($pdo,$user,$gioitinh,$email,$hashed_pass,$role);
        
            if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
                header("Location: qluser.php");
            } else {
                header("Location: dangnhap.php");
            }
            
            //exit;
        }
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Đăng ký</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Đăng ký</h1>

                <form method="post">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên đăng nhập<sup>*</sup></label>
                                <input type="text" class="form-control" id="user" name="user" value="<?= $user ?>">
                                <span class="text-danger"><?= $userError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Email<sup>*</sup></label>
                                <input type="text" class="form-control" id="email" name="email" value="<?= $email ?>">
                                <span class="text-danger"><?= $emailError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Giới tính <sup>*</sup></label>
                                <select class="form-control" id="gioitinh" name="gioitinh">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                                <span class="text-danger"><?= $gioitinhError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Mật khẩu<sup>*</sup></label>
                                <input type="password" class="form-control" id="pass" name="pass">
                                <span class="text-danger"><?= $passError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Nhập lại mật khẩu<sup>*</sup></label>
                                <input type="password" class="form-control" id="passcf" name="passcf">
                                <span class="text-danger"><?= $passcfError ?></span>
                            </div>

                            <?php if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") { ?>
                            <div class="form-item">
                                <label class="form-label my-3">Role<sup>*</sup></label>
                                <select name="role" id="role" class="form-control">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <?php } else { ?>
                            <input type="hidden" name="role" value="user">
                            <?php } ?>
                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Đăng ký</button>
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