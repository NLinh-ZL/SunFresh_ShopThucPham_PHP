 <!-- Footer Start -->
 <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0">SunFresh</h1>
                                <p class="text-secondary mb-0">Sản phẩm sạch</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Chúng tôi là ?</h4>
                            <p class="mb-4">SunFresh là trang web bán thực phẩm bao gồm rau, củ, quả, trái cây, thịt, cá và thủy sản, trang web giúp cho
                        người dùng có thể dễ dàng mua thực phẩm chỉ bằng việc lên trang web và chọn mua.</p>
                            <a href="about.php" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Xem thêm</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Loại sản phẩm</h4>         
                            <?php foreach ($soluongsploai as $type): ?>
                                <a class="btn-link" href="sanpham.php?page=1&sxsplist=<?php echo isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam'; ?>&loaiId=<?php echo $type->idloai; ?>"><?php echo $type->tenloai; ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                        <?php if(isset($_SESSION['logged_user'])): ?>
                            <?php if($_SESSION['logged_role']=="user"): ?>
                                <h4 class="text-light mb-3">Liên kết</h4>
                                <a class="btn-link" href="index.php">Trang chủ</a>
                                <a class="btn-link" href="sanpham.php">Sản phẩm</a>
                                <a class="btn-link" href="cart.php">Giỏ hàng</a>
                                <a class="btn-link" href="about.php">Giới thiệu</a>
                            <?php elseif($_SESSION['logged_role']=="admin"): ?>
                                <h4 class="text-light mb-3">Liên kết</h4>
                                <a class="btn-link" href="admin.php">Admin</a>
                                <a class="btn-link" href="qlsp.php">Quản lý sản phẩm</a>
                                <a class="btn-link" href="qluser.php">Quản lý tài khoản</a>
                                <a class="btn-link" href="qlbill.php">Quản lý đơn hàng</a>
                            <?php endif; ?>

                            <?php else: ?>
                                <h4 class="text-light mb-3">Liên kết</h4>
                                <a class="btn-link" href="index.php">Trang chủ</a>
                                <a class="btn-link" href="sanpham.php">Sản phẩm</a>
                                <a class="btn-link" href="about.php">Giới thiệu</a>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Liên hệ</h4>
                            <p>Địa chỉ: 140 Đ. Lê Trọng Tấn, Tây Thạnh, Tân Phú, Thành phố Hồ Chí Minh</p>
                            <p>Email: linh2003116@gmail.com</p>
                            <p>Phone: 028 6270 6275</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>SunFresh</a> - Designed by linh2003116@gmail.com</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="inc/lib/easing/easing.min.js"></script>
    <script src="inc/lib/waypoints/waypoints.min.js"></script>
    <script src="inc/lib/lightbox/js/lightbox.min.js"></script>
    <script src="inc/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="inc/js/main.js"></script>
    </body>

</html>
<?php ob_end_flush(); ?>