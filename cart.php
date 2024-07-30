<?php 
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "user") {
?>

<!-- =============================================================================================== -->


<?php
    require_once "class/Cart.php";
    $use=$_SESSION['logged_us'];
    $iduser = $use['iduser'];
    $limit = 6;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $cart=Cart::getCartByUser($pdo, $iduser, $offset, $limit);
    $totalItems = Cart::getCartCountByUser($pdo, $iduser);
    if($totalItems==0){
        $totalPages=1;
    }
    else{
        $totalPages = ceil($totalItems / $limit);
    }
    
    if(isset($_POST['update'])) {
        $cartid = $_POST['cartid'];
        $newQuantity = $_POST['qty'];
        Cart::update($pdo, $cartid, $newQuantity);
        header("Location: cart.php");
    }

    if (isset($_GET['action']) && isset($_GET['cartid'])) {
        $action = $_GET['action'];
        $cartid = $_GET['cartid'];
        if ($action == 'deletecart') {
            if (isset($_SESSION['logged_user'])) {
                Cart::deleteCartItem($pdo, $cartid);
                header("Location: cart.php");
            }
        }
    }

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $us = $_SESSION['logged_us'];
        $iduser= $us['iduser'];
        if ($action == 'deleteall') {
            if (isset($_SESSION['logged_user'])) {
                Cart::delete($pdo, $iduser);
                header("Location: cart.php");
            }
        }
    }

    $totalBill = 0;
    $crtt=Cart::getCartByUserTT($pdo, $iduser);
    foreach($crtt as $crt) {
        $totalBill += $crt['gia'] * $crt['soluong'];
    }
?>



        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Giỏ hàng</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">SunFresh</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="row g-4">
                            <div class="col-xl-2">
                            <a href="cart.php?action=deleteall" class="btn btn-danger d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Xóa hết</a>
                            </div>

                            <div class="col-5"></div>
                            <div class="col-3 text-center">
                                <span class="text-dark fs-5 fw-bold">Tổng tiền: <?php echo number_format($totalBill, 0, ',', '.') . ' VNĐ'; ?></span>
                            </div>
                            <div class="col-2">
                            <?php 
                            if($totalItems==0){
                            ?>
                                <a href="#" class="btn btn-warning d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold; opacity: 0">Thanh toán</a>
                            <?php 
                            }
                            else{
                            ?>
                                <a href="bill.php" class="btn btn-warning d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Thanh toán</a>
                            <?php 
                            }
                            ?>
                            </div>
                        </div>

                        <div class="row g-4">

                            <div class="col-lg-12">
                                <div class="row g-4 ">

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead>
                                                    <tr>
                                                        <th>Hình ảnh</th>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Giá</th>
                                                        <th>Số lượng</th>
                                                        <th>Tổng tiền</th>
                                                        <th>Cập nhật</th>
                                                        <th>Xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($cart as $crt): ?>
                                                        <tr>
                                                            <form method="post">
                                                                <input type="hidden" name="cartid" value="<?= $crt['idcart'] ?>" />
                                                                <td><img src="imgsp/<?php echo $crt['hinh']; ?>" class="img-fluid" alt="<?php echo $crt['tensp']; ?>" style="width: 200px; height: 125px;"></td>
                                                                <td><?php echo $crt['tensp']; ?></td>
                                                                <td><?php echo number_format($crt['gia'],0,',','.') . ' VNĐ/' . $crt['donvitinh']; ?></td>
                                                                <td>
                                                                    <input type="number" value="<?= $crt['soluong'] ?>" name="qty" min="1" style="width: 50px" />
                                                                </td>
                                                                <td><?php echo number_format(($crt['gia']*$crt['soluong']),0,',','.') . ' VNĐ' ?></td>
                                                                <td>
                                                                    <input type="submit" name="update" value="cập nhật" class="btn btn-primary" />
                                                                </td>
                                                                <td><a href="cart.php?action=deletecart&cartid=<?= $crt['idcart'] ?>" class="btn btn-danger">Xóa</a></td>
                                                            </form>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="pagination d-flex justify-content-center mt-5">
                                                <?php
                                                    // Nút "Trang đầu tiên"
                                                    if ($page == 1) {
                                                        echo '<span class="pagination-disabled rounded">&laquo;</span>';
                                                    } else {
                                                        echo '<a href="cart.php?page=1" class="rounded">&laquo;</a>';
                                                    }

                                                    // Hiển thị số trang
                                                    for ($i = 1; $i <= $totalPages; $i++) {
                                                        if ($i == $page) {
                                                            echo '<a href="#" class="active rounded">' . $i . '</a>';
                                                        } else {
                                                            echo '<a href="cart.php?page=' . $i . '" class="rounded">' . $i . '</a>';
                                                        }
                                                    }

                                                    // Nút "Trang cuối cùng"
                                                    if ($page == $totalPages) {
                                                        echo '<span class="pagination-disabled rounded">&raquo;</span>';
                                                    } else {
                                                        echo '<a href="cart.php?page=' . $totalPages . '" class="rounded">&raquo;</a>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
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