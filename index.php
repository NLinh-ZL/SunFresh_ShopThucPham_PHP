
<?php 
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->
<?php
    // require_once "class/Database.php";
    require_once "class/Sanpham.php";
    require_once "class/Cart.php";
?>

<?php 
    // $database = new Database();
    // $pdo = $database->getConnect();

    $newsp=Sanpham::getLatestProducts($pdo,8);

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
                header("Location: index.php");
                exit;
            }
        }
    }
    // echo var_dump($newsp);
?>


<!-- Hero Start -->
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-12 col-lg-7">
                <h4 class="mb-3 text-secondary">100% Sản phẩm sạch</h4>
                <h1 class="mb-3 display-3 text-primary">Rau củ & Trái cây</h1>
                <h1 class="mb-3 display-3 text-primary">Thịt cá</h1>
                <div class="position-relative mx-auto">
                    <form action="sanpham.php" method="GET">
                        <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="search" name="search" placeholder="Tên sản phẩm">
                        <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Tìm kiếm</button>
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-5">
                <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active rounded">
                            <img src="inc/img/hero-img-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                            <a href="#" class="btn px-4 py-2 text-white rounded">Trái cây</a>
                        </div>
                        <div class="carousel-item rounded">
                            <img src="inc/img/hero-img-2.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            <a href="#" class="btn px-4 py-2 text-white rounded">Rau củ</a>
                        </div>
                        <div class="carousel-item rounded">
                            <img src="inc/img/hero-img-3.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            <a href="#" class="btn px-4 py-2 text-white rounded">Thịt cá</a>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->


<!-- Featurs Section Start -->
<div class="container-fluid featurs py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-car-side fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Miễn phí giao hàng</h5>
                        <p class="mb-0">Miễn phí với hóa đơn trên 100.000đ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Bảo mật thông tin</h5>
                        <p class="mb-0">100% bảo mật thông tin khách hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-exchange-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Sản phẩm an toàn</h5>
                        <p class="mb-0">Có chứng nhận an toàn thực phẩm</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fa fa-phone-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Hỗ trợ 24/7</h5>
                        <p class="mb-0">Tận tình hỗ trợ nhanh chóng mọi lúc</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Featurs Section End -->


<!-- Fruits Shop Start-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1 style="color: #81c408;">Sản phẩm mới</h1>
                </div>
            </div>
            <div class="tab-content">
                
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4">

                                    <?php foreach($newsp as $product): ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                                <div class="fruite-img">
                                                    <a href="chitietsp.php?id=<?= $product->idsp ?>"><img src="imgsp/<?php echo $product->hinh; ?>" class="w-100 rounded-top" alt="" style="height: 214px;"></a>
                                                </div>

                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">New</div>
                                                                                    
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <h4><a href="chitietsp.php?id=<?= $product->idsp ?>"><?php echo $product->tensp; ?></a></h4>
                                                    <p>&nbsp&nbspXuất xứ: <?php echo $product->xuatxu; ?></p>
                                                    <div class="d-flex justify-content-between flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold" style="min-height: 60px;">&nbsp&nbspGiá: <?php echo number_format($product->gia,0,',','.') . '&nbspVNĐ  / ' . $product->donvitinh; ?></p>
                                                        <?php if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "user"): ?>
                                                            <a href="index.php?action=addcart&proid=<?= $product->idsp ?>" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm giỏ hàng</a>
                                                        <?php else: ?>
                                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary" style="opacity: 0;"><i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm giỏ hàng</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

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
    require_once "inc/footer.php";
?>