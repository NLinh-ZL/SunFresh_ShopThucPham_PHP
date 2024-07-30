<?php 
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->


<?php
    require_once "class/Bill.php";
    require_once "class/Billdetail.php";

    if(! isset($_GET["id"])){
        die("Cần cung cấp thông tin sản phẩm !!!");
    }
    $id=$_GET["id"];

    $bill= Bill::getOneBillByID($pdo,$id);
    $detailbill = Billdetail::getDetailBillByID($pdo,$bill->idbill);

    var_dump($bill);
    var_dump($detailbill);

    if(! $bill){
        die("Id không hợp lệ !!!");
    }

    $totalBill = 0;
    foreach($detailbill as $dtb) {
        $totalBill += $dtb['gia'] * $dtb['soluong'];
    }
    
?>



        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Đơn hàng <?= $bill->idbill ?></h1>
        </div>
        <!-- Single Page Header End -->

<!-- Checkout Page Start -->
<div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Đơn Hàng</h1>
            <form method="post">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="row bg-light align-items-center text-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Id đơn hàng</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->idbill ?></p>
                            </div>
                        </div>
                        <div class="row text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Tên user</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->user ?></p>
                            </div>
                        </div>
                        <div class="row bg-light text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Tên người nhận</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->tennhan ?></p>
                            </div>
                        </div>
                        <div class="row text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Ngày đặt</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->ngaydat ?></p>
                            </div>
                        </div>
                        <div class="row text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Địa chỉ giao</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->diachigiao ?></p>
                            </div>
                        </div>
                        <div class="row text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Số điện thoại</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->sdt ?></p>
                            </div>
                        </div>
                        <div class="row text-center align-items-center justify-content-center py-2">
                            <div class="col-6">
                                <p class="mb-0">Ghi chú</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-0"><?= $bill->ghichu ?></p>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="table-responsive">
                            <table class="table" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($detailbill as $dtb): ?>
                                        <tr>
                                            <td class="py-5"><?php echo $dtb['tensp']; ?></td>
                                            <td class="py-5"><?php echo number_format($dtb['gia'],0,',','.') . ' VNĐ' ?></td>
                                            <td class="py-5 text-center"><?= $dtb['soluong'] ?></td>
                                            <td class="py-5"><b><?php echo number_format(($dtb['gia']*$dtb['soluong']),0,',','.') . ' VNĐ' ?></b></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    

                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-3">Tổng tiền</p>
                                        </td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark"><b><?php echo number_format($totalBill, 0, ',', '.') . ' VNĐ'; ?></b></p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-3">Phí Ship:</p>
                                        </td>
                                        <?php if($totalBill>100000): ?>
                                            <td colspan="3" class="py-5 border-bottom border-top">
                                                <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0" style="color: red;"><s>30.000</s> VNĐ</p>
                                                </div>
                                            </td>
                                        <?php else:?>
                                            <td colspan="3" class="py-5 border-bottom border-top">
                                            <div class="py-3 border-bottom border-top">
                                                    <p class="mb-0 text-dark">30.000 VNĐ</p>
                                                </div>
                                            </td>
                                        <?php endif;?>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark text-uppercase py-3"><b>THÀNH TIỀN</b></p>
                                        </td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark"><b><?php echo number_format($bill->tongtien, 0, ',', '.') . ' VNĐ'; ?></b></p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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