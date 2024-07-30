
<?php 
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->

<!-- Hero Start -->
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-12 col-lg-7">
                <h4 class="mb-3 text-secondary">Hello !!!</h4>
                <h1 class="mb-3 display-3 text-primary">Chào mừng đến với</h1>
                <h1 class="mb-3 display-3 text-primary">Trang Admin</h1>
            </div>
            <div class="col-md-12 col-lg-5">
                <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active rounded">
                            <img src="inc/img/hero-img-2.jpg" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                            <a href="qlsp.php" class="btn px-4 py-2 text-white rounded">Sản phẩm</a>
                        </div>
                        <div class="carousel-item rounded">
                            <img src="inc/img/user.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            <a href="qluser.php" class="btn px-4 py-2 text-white rounded">Tài khoản</a>
                        </div>
                        <div class="carousel-item rounded bg-light">
                            <img src="inc/img/bill.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            <a href="qlbill.php" class="btn px-4 py-2 text-white rounded">Đơn hàng</a>
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