<!DOCTYPE html>
<?php ob_start(); ?>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>SunFresh Nguyen Nhat Linh</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="inc/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="inc/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="inc/css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="inc/css/style.css" rel="stylesheet">
    </head>

    <body>
<?php
session_start();
require_once "class/Database.php";
require_once "class/Loaisp.php";
require_once "class/Cart.php";
$database = new Database();
$pdo = $database->getConnect();

$soluongsploai = Loaisp::countProductsByCategory($pdo);
// $loaisp=Loaisp::getAllProductTypes($pdo);

?>
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div> -->
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">140 Đ. Lê Trọng Tấn, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh</a></small>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">linh2003116@gmail.com</a></small>
                        <small class="me-3"><i class="fas fa-phone me-2 text-secondary"></i><a href="#" class="text-white">028 6270 6275</a></small>
                    </div>
                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <?php if(isset($_SESSION['logged_user'])): ?>
                        <?php if($_SESSION['logged_role']=="admin"): ?>
                            <a href="admin.php" class="navbar-brand"><h1 class="text-primary display-6">SunFresh</h1></a>
                        <?php else: ?>
                            <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">SunFresh</h1></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">SunFresh</h1></a>
                    <?php endif; ?>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            
                        <?php if(isset($_SESSION['logged_user'])): ?>
                            <?php if($_SESSION['logged_role']=="user"): ?>
                                    <a href="index.php" class="nav-item nav-link active">Trang chủ</a>
                                    <a href="sanpham.php" class="nav-item nav-link">Sản phẩm</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Loại sản phẩm</a>
                                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <?php foreach ($soluongsploai as $type): ?>
                                            <a href="sanpham.php?page=1&sxsplist=<?php echo isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam'; ?>&loaiId=<?php echo $type->idloai; ?>" class="dropdown-item"><?php echo $type->tenloai; ?></a>
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <a href="about.php" class="nav-item nav-link">Giới thiệu</a>
                                </div>
                                <div class="d-flex m-3 me-0">
                                    <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                                    <a href="cart.php" class="position-relative me-4 my-auto">
                                        <i class="fa fa-shopping-bag fa-2x"></i>
                                    </a>
                            <?php elseif($_SESSION['logged_role']=="admin"): ?>
                                    <a href="admin.php" class="nav-item nav-link active">Admin</a>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Loại sản phẩm</a>
                                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <?php foreach ($soluongsploai as $type): ?>
                                            <a href="qlsp.php?page=1&sxsplist=<?php echo isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam'; ?>&loaiId=<?php echo $type->idloai; ?>" class="dropdown-item"><?php echo $type->tenloai; ?></a>
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="nav-item dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Quản lý</a>
                                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                            <a href="qlsp.php" class="dropdown-item">Quản lý sản phẩm</a>
                                            <a href="qluser.php" class="dropdown-item">Quản lý tài khoản</a>
                                            <a href="qlbill.php" class="dropdown-item">Quản lý đơn hàng</a>
                                        </div>
                                    </div>
                                    <a href="about.php" class="nav-item nav-link">Giới thiệu</a>
                                </div>
                                <div class="d-flex m-3 me-0">
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="index.php" class="nav-item nav-link active">Trang chủ</a>
                            <a href="sanpham.php" class="nav-item nav-link">Sản phẩm</a>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Loại sản phẩm</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <?php foreach ($soluongsploai as $type): ?>
                                        <a href="sanpham.php?page=1&sxsplist=<?php echo isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam'; ?>&loaiId=<?php echo $type->idloai; ?>" class="dropdown-item"><?php echo $type->tenloai; ?></a>
                                    <?php endforeach; ?>
                                    </div>
                                </div>
                                <a href="about.php" class="nav-item nav-link">Giới thiệu</a>
                            </div>
                            <div class="d-flex m-3 me-0">
                                <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <?php endif; ?>

                            <?php if(isset($_SESSION['logged_user'])): ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><?= $_SESSION['logged_user'] ?></a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="doimk.php" class="dropdown-item">Đổi mật khẩu</a>
                                        <a href="dangxuat.php" class="dropdown-item">Đăng xuất</a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Tài khoản</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="dangnhap.php" class="dropdown-item">Đăng nhập</a>
                                        <a href="dangky.php" class="dropdown-item">Đăng ký</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                               
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">               
                            <h5 class="modal-title" id="exampleModalLabel">Tìm kiếm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <form action="sanpham.php" method="GET"  class="form-control p-3 d-flex">
                                <input type="search" class="form-control p-3"  name="search" placeholder="Tên sản phẩm" aria-describedby="search-icon-1" >
                                <button type="submit" id="search-icon-1" class="input-group-text p-3" style="margin-left: 5px;"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->