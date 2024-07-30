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
    require_once "class/Billdetail.php";
    $use=$_SESSION['logged_us'];
    $iduser = $use['iduser'];
    $cart=Cart::getCartByUserForBill($pdo, $iduser);


    $totalBill = 0;
    foreach($cart as $crt) {
        $totalBill += $crt['gia'] * $crt['soluong'];
    }
    if($totalBill>100000)
    {
        $totalFullBill = $totalBill-30000;
    }
    else{
        $totalFullBill = $totalBill;
    }

    $tennhanError='';
    $diachiError='';
    $sdtError='';
    

    $tennhan='';
    $diachi='';
    $sdt='';
    $ghichu='';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $tennhan =$_POST['tennhan'];
        $diachi =$_POST['diachi'];
        $sdt =$_POST['sdt'];
        $ghichu =$_POST['ghichu'];


        if(empty($tennhan)){
            $tennhanError='Phải nhập tên người nhận';
        }
        else if(ucwords($tennhan) !== $tennhan) {
            $tennhanError='Chữ đầu của từng từ phải viết hoa';
        }
    
        if(empty($diachi)){
            $diachiError='Phải nhập địa chỉ nhận hàng';
        }
    
        if(empty($sdt)){
            $sdtError='Phải nhập số điện thoại nhận hàng';
        }else if(!ctype_digit($sdt) || strlen($sdt) !== 10) {
            $sdtError='Số điện thoại phải là 10 số';
        }
    
        if(!$tennhanError && !$diachiError && !$sdtError)
        {
            $sql = "INSERT INTO bill (iduser, tennhan, diachigiao, sdt, ghichu, tongtien) 
            VALUES (:iduser, :tennhan, :diachi, :sdt, :ghichu, :totalFullBill)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':iduser', $iduser);
            $stmt->bindParam(':tennhan', $tennhan);
            $stmt->bindParam(':diachi', $diachi);
            $stmt->bindParam(':sdt', $sdt);
            $stmt->bindParam(':ghichu', $ghichu);
            $stmt->bindParam(':totalFullBill', $totalFullBill);
            $stmt->execute();

            $newBillId = $pdo->lastInsertId();

            Billdetail::insertBillDetails($pdo, $newBillId, $iduser);

            header("Location: thanhcong.php");
            exit;
        }
    }
    
?>



        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Thanh toán</h1>
        </div>
        <!-- Single Page Header End -->

<!-- Checkout Page Start -->
<div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Đơn Thanh Toán</h1>
            <form method="post">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="form-item">
                            <label class="form-label my-3">Tên người nhận<sup>*</sup></label>
                            <input type="text" id="tennhan" name="tennhan" class="form-control" value="<?= $tennhan ?>">
                            <span class="text-danger"><?= $tennhanError ?></span>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Địa chỉ giao<sup>*</sup></label>
                            <input type="text" id="diachi" name="diachi" class="form-control" value="<?= $diachi ?>">
                            <span class="text-danger"><?= $diachiError ?></span>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Số điện thoại<sup>*</sup></label>
                            <input type="text" id="sdt" name="sdt" class="form-control" value="<?= $sdt ?>">
                            <span class="text-danger"><?= $sdtError ?></span>
                        </div>
                        </br>
                        <div class="form-item">
                            <textarea id="ghichu" name="ghichu" class="form-control" spellcheck="false" cols="30" rows="11" placeholder="Ghi chú cho shipper"></textarea>
                        </div>
                    </div>
                    

                    <div class="col-md-12 col-lg-6 col-xl-6">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Ảnh</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cart as $crt): ?>
                                        <tr>
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img src="imgsp/<?php echo $crt['hinh']; ?>" class="img-fluid rounded-circle" style="width: 90px; height: 90px;" alt="">
                                                </div>
                                            </th>
                                            <td class="py-5"><?php echo $crt['tensp']; ?></td>
                                            <td class="py-5"><?php echo number_format($crt['gia'],0,',','.') . ' VNĐ' ?></td>
                                            <td class="py-5 text-center"><?= $crt['soluong'] ?></td>
                                            <td class="py-5"><b><?php echo number_format(($crt['gia']*$crt['soluong']),0,',','.') . ' VNĐ' ?></b></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    

                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5"></td>
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
                                            <p class="mb-0 text-dark text-uppercase py-3">THÀNH TIỀN</p>
                                        </td>
                                        <td class="py-5"></td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark"><b><?php echo number_format($totalFullBill, 0, ',', '.') . ' VNĐ'; ?></b></p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Thanh toán</button>
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