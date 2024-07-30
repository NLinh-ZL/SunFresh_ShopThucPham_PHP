<?php
require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
require_once "class/Sanpham.php";
require_once "class/Sanpham_loaisp.php";
require_once "class/Cart.php";
?>

<?php
$loaiId = isset($_GET['loaiId']) ? $_GET['loaiId'] : null;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

$tenloai = "";
if ($loaiId) {
    $tenloai = Loaisp::getCategoryNameById($pdo, $loaiId);
}

$cot = ""; // Biến lưu trữ trường sắp xếp
$kieusx = ""; // Biến lưu trữ hướng sắp xếp

if (isset($_GET['sxsplist'])) {
    $sxsplist = $_GET['sxsplist'];
} else {
    $sxsplist = 'idgiam';
}

switch ($sxsplist) {
    case 'idtang':
        $cot = "idsp";
        $kieusx = "ASC";
        break;
    case 'idgiam':
        $cot = "idsp";
        $kieusx = "DESC";
        break;
    case 'a-z':
        $cot = "tensp";
        $kieusx = "ASC";
        break;
    case 'z-a':
        $cot = "tensp";
        $kieusx = "DESC";
        break;
}


$page = isset($_GET['page']) ? $_GET['page'] : 1; //Nếu tồn tại, nó sẽ trả về giá trị của biến đó ($_GET['page']), ngược lại nó sẽ trả về giá trị mặc định là 1

$limit = 6;
$offset = ($page - 1) * $limit;

if (isset($_GET['search'])) {
    // $searchTerm = $_GET['search'];
    $sanpham = Sanpham::searchProductsByName($pdo, $searchTerm, $limit, $offset, $cot, $kieusx);
} else {
    $sanpham = Sanpham::getallProductsPage($pdo, $limit, $offset, $cot, $kieusx, $loaiId);
}

$tongsp = Sanpham::getallProducts($pdo);



if ($loaiId) {
    foreach ($soluongsploai as $category) {
        if ($category->idloai == $loaiId) {
            $totalPages = ceil($category->soluong / $limit);
            break; // Đã tìm thấy số lượng sản phẩm của loại này, không cần duyệt tiếp
        }
    }
} elseif (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $totalSearch = Sanpham::countSearchResults($pdo, $searchTerm);
    $totalPages = ceil($totalSearch / $limit);
} else {
    $totalPages = ceil(count($tongsp) / $limit);
}

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
            header("Location: sanpham.php");
            exit;
        }
    }
}



// echo var_dump($sanpham);
?>


<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Sản phẩm</h1>
</div>
<!-- Single Page Header End -->


<!-- Fruits Shop Start-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <h1 class="mb-4">SunFresh</h1>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="input-group w-100 mx-auto d-flex">
                            <form action="sanpham.php" method="GET" class="d-flex">
                                <input type="search" name="search" class="form-control p-3" placeholder="Tên sản phẩm" aria-describedby="search-icon-1" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button type="submit" id="search-icon-1" class="input-group-text p-3" style=" border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="col-4"></div>
                    <div class="col-2">
                        <?php if ($loaiId || $searchTerm) { ?>
                            <a href="sanpham.php" class="btn btn-primary d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Tất cả sản phẩm</a>
                        <?php } ?>
                    </div>

                    <div class="col-xl-3">
                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                            <form id="sxspform" action="sanpham.php" method="GET">
                                <label for="fruits">Sắp xếp theo:</label>
                                <!-- Sử dụng onchange để gọi hàm JS khi có sự thay đổi -->
                                <select id="sxsp" name="sxsplist" class="border-0 form-select-sm bg-light me-3" onchange="submitForm()">
                                    <option value="idgiam">Mới -> Cũ</option>
                                    <option value="idtang">Cũ -> Mới</option>
                                    <option value="a-z">A -> Z</option>
                                    <option value="z-a">Z -> A</option>
                                </select>
                                <?php
                                if (isset($loaiId)) {
                                ?>
                                    <input type="hidden" name="loaiId" value="<?= $loaiId ?>">
                                <?php
                                }
                                ?>
                                <?php
                                if (isset($searchTerm)) {
                                ?>
                                    <input type="hidden" name="search" value="<?= $searchTerm ?>">
                                <?php
                                }
                                ?>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Loại sản phẩm</h4>
                                    <hr>
                                    <ul class="list-unstyled fruite-categorie">
                                        <?php foreach ($soluongsploai as $type) : ?>
                                            <li>
                                                <div class="d-flex justify-content-between fruite-name">
                                                    <a href="sanpham.php?page=1&sxsplist=<?php echo isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam'; ?>&loaiId=<?php echo $type->idloai; ?>"><i class="fas fa-sun me-2"></i><?php echo $type->tenloai; ?></a>
                                                    <span>(<?php echo $type->soluong ? $type->soluong : 0; ?>)</span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <hr>

                            <div class="col-lg-12">
                                <div class="position-relative">
                                    <img src="inc/img/banner-fruits.jpg" class="img-fluid w-100 rounded" alt="">
                                    <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                        <h3 class="text-secondary fw-bold">Sạch<br>Tươi<br>Mới</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row g-4 ">

                            <?php foreach ($sanpham as $product) : ?>
                                <div class="col-md-6 col-lg-6 col-xl-4 text-center">
                                    <div class="rounded position-relative fruite-item">
                                        <div class="fruite-img">
                                            <!-- Sử dụng đường dẫn hình ảnh của sản phẩm -->
                                            <a href="chitietsp.php?id=<?= $product->idsp ?>"><img src="imgsp/<?php echo $product->hinh; ?>" class="w-100 rounded-top" alt="" style="height: 214px;"></a>
                                        </div>

                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <!-- Sử dụng tên sản phẩm, mô tả, giá của sản phẩm -->
                                            <h4><a href="chitietsp.php?id=<?= $product->idsp ?>"><?php echo $product->tensp; ?></a></h4>
                                            <p>&nbsp&nbspXuất xứ: <?php echo $product->xuatxu; ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold" style="min-height: 60px;">&nbsp&nbspGiá: <?php echo number_format($product->gia, 0, ',', '.') . '&nbspVNĐ  / ' . $product->donvitinh; ?></p>

                                                <?php if (isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "user") : ?>
                                                    <a href="sanpham.php?action=addcart&proid=<?= $product->idsp ?>" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm giỏ hàng</a>
                                                <?php else : ?>
                                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary" style="opacity: 0;"><i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm giỏ hàng</a>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="pagination d-flex justify-content-center mt-5">
                                        <?php
                                        // Nút "Trang đầu tiên"
                                        if ($page == 1) {
                                            echo '<span class="pagination-disabled rounded">&laquo;</span>';
                                        } else {
                                            echo '<a href="sanpham.php?page=1&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') . '" class="rounded">&laquo;</a>';
                                        }

                                        // Hiển thị số trang
                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            if ($i == $page) {
                                                echo '<a href="#" class="active rounded">' . $i . '</a>';
                                            } else {
                                                echo '<a href="sanpham.php?page=' . $i . '&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') . '" class="rounded">' . $i . '</a>';
                                            }
                                        }

                                        // Nút "Trang cuối cùng"
                                        if ($page == $totalPages) {
                                            echo '<span class="pagination-disabled rounded">&raquo;</span>';
                                        } else {
                                            echo '<a href="sanpham.php?page=' . $totalPages . '&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') . '" class="rounded">&raquo;</a>';
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
require_once "inc/footer.php";
?>

<script>
    function submitForm() {
        // Lấy giá trị đã chọn từ dropdown menu
        var selectedValue = document.getElementById("sxsp").value;
        // Lưu giá trị đã chọn vào Local Storage
        localStorage.setItem('selectedSortOption', selectedValue);
        // Gán giá trị đã chọn vào biểu mẫu
        document.getElementById("sxspform").submit();
    }

    // Kiểm tra xem có giá trị đã lưu trong Local Storage hay không khi trang được load
    window.onload = function() {
        var storedValue = localStorage.getItem('selectedSortOption');
        if (storedValue) {
            // Nếu có, gán giá trị đó cho dropdown menu
            document.getElementById("sxsp").value = storedValue;
        }
    };
</script>