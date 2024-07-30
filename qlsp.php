<?php 
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->


<?php
    require_once "class/Sanpham.php";
?>

<?php 
    // $sxsplist = null;
    // $searchTerm = null;
    $loaiId = isset($_GET['loaiId']) ? $_GET['loaiId'] : null;
    $sxsplist = isset($_GET['sxsplist']) ? $_GET['sxsplist'] : null;
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : null;

    // $tenloai = "";
    // if ($loaiId) {
    //     $tenloai = Loaisp::getCategoryNameById($pdo, $loaiId);
    // }

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

    $page=isset($_GET['page']) ? $_GET['page'] : 1; //Nếu tồn tại, nó sẽ trả về giá trị của biến đó ($_GET['page']), ngược lại nó sẽ trả về giá trị mặc định là 1
    
    $limit=6;
    $offset = ($page - 1) * $limit;

    if(isset($_GET['search'])) {
        // $searchTerm = $_GET['search'];
        $sanpham = Sanpham::searchProductsByName($pdo, $searchTerm, $limit, $offset, $cot, $kieusx);
    } else {
        $sanpham=Sanpham::getallProductsPage($pdo, $limit, $offset, $cot, $kieusx, $loaiId);
    }

    
    $tongsp=Sanpham::getallProducts($pdo);
    //Dùng để lấy tổng số trang của từng loại
    if ($loaiId) {
        foreach ($soluongsploai as $category) {
            if ($category->idloai == $loaiId) {
                $totalPages = ceil($category->soluong / $limit);
                break; // Đã tìm thấy số lượng sản phẩm của loại này, không cần duyệt tiếp
            }
        }
    }
    else if($_GET['search']){
        // $searchTerm = $_GET['search'];
        $totalSearch = Sanpham::countSearchResults($pdo, $searchTerm);
        $totalPages = ceil($totalSearch / $limit);
    }
    else{
        $totalPages = ceil(count($tongsp) / $limit);
    }
    

    
    // echo var_dump($sanpham);
?>


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Quản lý sản phẩm</h1>
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
                                    <form action="qlsp.php" method="GET"  class="d-flex">
                                        <input type="search" name="search" class="form-control p-3" placeholder="Tên sản phẩm" aria-describedby="search-icon-1" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                        <button type="submit" id="search-icon-1" class="input-group-text p-3" style=" border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-2">
                                <a href="themsp.php" class="btn btn-primary d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Thêm sản phẩm</a>
                            </div>

                            <div class="col-2">
                                <?php if($loaiId || $searchTerm){?>
                                    <a href="qlsp.php" class="btn btn-primary d-flex align-items-center justify-content-center" style="width:100%; height: 70%; font-size: larger; font-weight: bold;">Tất cả sản phẩm</a>
                                <?php } ?>
                            </div>

                            <div class="col-2">
                                <div class="nav-item dropdown bg-light rounded">
                                    <a href="#" class="nav-link dropdown-toggle py-3 " data-bs-toggle="dropdown" style="color: black;">Loại sản phẩm</a>
                                    <div class="dropdown-menu m-0 bg-light rounded-0" style="max-height: 200px; overflow-y: auto;">
                                        <?php foreach ($soluongsploai as $type): ?>
                                            <a href="qlsp.php?page=1&sxsplist=<?= $sxsplist ?>&loaiId=<?= $type->idloai; ?>" class="dropdown-item"><?php echo $type->tenloai; ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 ">
                                <div class="mb-4">
                                    <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                        <form id="sxspform" action="qlsp.php" method="GET">
                                            <label for="fruits">Sắp xếp theo:</label>
                                            <!-- Sử dụng onchange để gọi hàm JS khi có sự thay đổi -->
                                            <select id="sxsp" name="sxsplist" class="border-0 form-select-sm bg-light me-3" onchange="submitForm()">
                                                <option value="idgiam">Mới -> Cũ</option>
                                                <option value="idtang">Cũ -> Mới</option>
                                                <option value="a-z">A -> Z</option>
                                                <option value="z-a">Z -> A</option>
                                            </select>
                                            <?php 
                                                if(isset($loaiId)){ 
                                            ?>
                                                    <input type="hidden" name="loaiId" value="<?= $loaiId ?>">
                                            <?php
                                                }
                                            ?>
                                            <?php 
                                                if(isset($searchTerm)){ 
                                            ?>
                                                    <input type="hidden" name="search" value="<?= $searchTerm ?>">
                                            <?php
                                                }
                                            ?>
                                        </form>
                                    </div>
                                </div>
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
                                                        <th>Xuất xứ</th>
                                                        <th>Giá</th>
                                                        <th>Chi tiết</th>
                                                        <th>Xóa</th>
                                                        <th>Sửa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($sanpham as $product): ?>
                                                        <tr>
                                                            <td><img src="imgsp/<?php echo $product->hinh; ?>" class="img-fluid" alt="<?php echo $product->tensp; ?>" style="width: 200px; height: 125px;"></td>
                                                            <td><?php echo $product->tensp; ?></td>
                                                            <td><?php echo $product->xuatxu; ?></td>
                                                            <td><?php echo number_format($product->gia,0,',','.') . ' VNĐ/' . $product->donvitinh; ?></td>
                                                            <td><a href="chitietsp.php?id=<?= $product->idsp ?>" class="btn btn-warning">Chi tiết</a></td>
                                                            <td><a href="suasp.php?id=<?= $product->idsp ?>" class="btn btn-primary">Sửa</a></td>
                                                            <td><a href="xoasp.php?id=<?= $product->idsp ?>" class="btn btn-danger">Xóa</a></td>
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
                                                }else{
                                                    echo '<a href=" href="qlsp.php?page=1&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') .  '" class="rounded">&laquo;</a>';

                                                }

                                                // Hiển thị số trang
                                                for ($i = 1; $i <= $totalPages; $i++) {
                                                    if ($i == $page) {
                                                        echo '<a href="#" class="active rounded">' . $i . '</a>';
                                                    } else {
                                                        echo '<a href="qlsp.php?page=' . $i . '&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') .  '" class="rounded">' . $i . '</a>';
                                                    }
                                                }

                                                // Nút "Trang cuối cùng"
                                                if ($page == $totalPages) {
                                                    echo '<span class="pagination-disabled rounded">&raquo;</span>';
                                                }else{
                                                    echo '<a href="qlsp.php?page=' . $totalPages . '&sxsplist=' . (isset($_GET['sxsplist']) ? $_GET['sxsplist'] : 'idgiam') . (isset($loaiId) ? '&loaiId=' . $loaiId : '') . (isset($searchTerm) ? '&search=' . $searchTerm : '') .  '" class="rounded">&raquo;</a>';

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