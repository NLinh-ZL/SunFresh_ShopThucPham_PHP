<?php 
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->


<?php
    require_once "class/User.php";
    // $use=$_SESSION['logged_us'];
    // $iduser = $use['iduser'];

    $users=User::getAllUser($pdo);

    if (isset($_GET['action']) && isset($_GET['iduser'])) {
        $action = $_GET['action'];
        $idus = $_GET['iduser'];
        if ($action == 'deleteuser') {
            if (isset($_SESSION['logged_user'])) {
                User::deleteUser($pdo, $idus);
                header("Location: qluser.php");
            }
        }
    }

?>



        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Quản lý User</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">SunFresh</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <div class="col-xl-2"></div>
                            <div class="col-8"></div>
                            <div class="col-2">
                            <a href="dangky.php" class="btn btn-primary d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Thêm người dùng</a>
                            </div>
                        </div>

                        <div class="row g-4">

                            <div class="col-lg-12">
                                <div class="row g-4 ">

                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Tên user</th>
                                                    <th>Giới tính</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Xóa</th>
                                                    <th>Sửa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($users as $usr): ?>
                                                    <tr>
                                                        <form method="post">
                                                            <td><?php echo $usr['iduser']; ?></td>
                                                            <td><?php echo $usr['user']; ?></td>
                                                            <td><?php echo $usr['gioitinh']; ?></td>
                                                            <td><?php echo $usr['email']; ?></td>
                                                            <td><?php echo $usr['role']; ?></td>
                                                            <td><a href="qluser.php?action=deleteuser&iduser=<?= $usr['iduser'] ?>" class="btn btn-danger">Xóa</a></td>
                                                            <td><a href="suauser.php?id=<?= $usr['iduser'] ?>" class="btn btn-warning">Sửa</a></td>
                                                        </form>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fruits Shop End-->


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