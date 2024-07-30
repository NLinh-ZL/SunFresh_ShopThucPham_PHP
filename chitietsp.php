<?php 
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
    require_once "class/Sanpham.php";
    require_once "class/Cart.php";

    if(! isset($_GET["id"])){
        die("Cần cung cấp thông tin sản phẩm !!!");
    }
    $id=$_GET["id"];

    $sp= Sanpham::getOneProductById($pdo,$id);

    if(! $sp){
        die("Id không hợp lệ !!!");
    }

    $loaiSanPham = Loaisp::getLoaiByProductId($pdo, $id);
?>

<?php 
   
   if (isset($_GET['action']) && isset($_GET['proid'])) {
    $action = $_GET['action'];
    $proid = $_GET['proid'];

    if ($action == 'addcart') {
        if (isset($_SESSION['logged_user'])) {
            $us = $_SESSION['logged_us'];
            $iduser= $us['iduser'];
            $Cart = Cart::getOneCardByIdProduct($pdo, $proid, $iduser);
            if ($Cart) {
                $Quantity = $Cart->soluong + 1;
                Cart::update($pdo, $Cart->idcart, $Quantity);
            } else {
                $Quantity = 1;
                Cart::insertCart($pdo, $proid, $Quantity, $iduser);
            }
            header("Location: cart.php");
            exit;
        }
    }
}
    // echo var_dump($sanpham);
?>


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6"><?= $sp->tensp ?></h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Single Product Start -->
        <div class="container-fluid py-5 mt-5">
            <div class="container py-5">
                <div class="row g-4 mb-5">
                    <div class="col-lg-8 col-xl-9">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded">
                                    <a href="#">
                                        <img src="imgsp/<?php echo $sp->hinh; ?>" class="rounded" alt="Image" width="470px" height="300px">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="fw-bold mb-3"><?= $sp->tensp ?></h4>
                                <p class="mb-3">Loại: 
                                <?php 
                                    $count = count($loaiSanPham);
                                    foreach ($loaiSanPham as $index => $loai) {
                                        echo $loai->tenLoai;
                                        if ($index < $count - 1) {
                                            echo ", ";
                                        }
                                        else{
                                            echo ". ";
                                        }
                                    }
                                ?>
                                </p>
                                <h5 class="fw-bold mb-3"><?php echo number_format($sp->gia,0,',','.')?> &nbspVNĐ</h5>
                                <!-- <div class="d-flex mb-4">
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star text-secondary"></i>
                                    <i class="fa fa-star"></i>
                                </div> -->
                                <?php if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "user") { ?>
                                <a href="chitietsp.php?id=<?= $sp->idsp ?>&action=addcart&proid=<?= $sp->idsp ?>" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm vào giỏ hàng</a>
                                <?php } ?>
                            </div>
                            <div class="col-lg-12">
                                <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Mô tả sản phẩm</button>
                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <p>&nbsp&nbsp<?= $sp->mota ?></p>
                                        <div class="px-2">
                                            <div class="row g-4">
                                                <div class="col-6">
                                                    <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Mã sản phẩm</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0"><?= $sp->idsp ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Đơn vị tính</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0"><?= $sp->donvitinh ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Xuất xứ</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0"><?= $sp->xuatxu ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row text-center align-items-center justify-content-center py-2">
                                                        <div class="col-6">
                                                            <p class="mb-0">Kiểm tra</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-0">An toàn</p>
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
                    <div class="col-lg-4 col-xl-3">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Product End -->


<!-- =============================================================================================== -->
<?php 
    require_once "inc/footer.php";
?>
