<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->
<?php
    require_once "class/User.php";

    if(! isset($_GET["id"])){
        die("Cần cung cấp thông tin sản phẩm !!!");
    }
    $id=$_GET["id"];

    $us= User::getOneUserById($pdo,$id);

    if(! $us){
        die("Id không hợp lệ !!!");
    }
?>

<?php 
    require_once "class/User.php";
    $userError='';
    $gioitinhError='';
    $emailError='';

    $user='';
    $gioitinh='';
    $email='';

    $role='';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user=$_POST['user'];
        $gioitinh=$_POST['gioitinh'];
        $email=$_POST['email'];
        $role=$_POST['role'];

        if(empty($user)){
            $userError='Hãy nhập tên đăng nhập';
        }elseif(User::isUsernameExists($pdo, $user, 1)){
            $userError='Tên đăng nhập đã tồn tại';
        }

        if(empty($email)){
            $emailError='Hãy nhập email';
        }
        elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
            $emailError='Email không hợp lệ';
        }

        if(empty($userError) && empty($emailError) && empty($passError) && empty($passcfError)&& empty($roleError)) {
            User::updateUser($pdo, $id, $user,$gioitinh,$email,$role);
        
            header("Location: qluser.php");
            //exit;
        }
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6"><?= $us->user ?></h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Sửa tài khoản</h1>

                <form method="post">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên đăng nhập<sup>*</sup></label>
                                <input type="text" class="form-control" id="user" name="user" value="<?= $us->user ?>">
                                <span class="text-danger"><?= $userError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Email<sup>*</sup></label>
                                <input type="text" class="form-control" id="email" name="email" value="<?= $us->email ?>">
                                <span class="text-danger"><?= $emailError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Giới tính <sup>*</sup></label>
                                <select class="form-control" id="gioitinh" name="gioitinh">
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam" <?= ($us->gioitinh === 'Nam') ? 'selected' : '' ?>>Nam</option>
                                    <option value="Nữ" <?= ($us->gioitinh === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                                </select>
                                <span class="text-danger"><?= $gioitinhError ?></span>
                            </div>

                            <div class="form-item">
                                <label class="form-label my-3">Role<sup>*</sup></label>
                                <select name="role" id="role" class="form-control">
                                    <option value="user" <?= ($us->role === 'user') ? 'selected' : '' ?> >User</option>
                                    <option value="admin" <?= ($us->role === 'admin') ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Sửa</button>
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