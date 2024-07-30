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

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
    $colSearch = isset($_GET['colSearch']) ? $_GET['colSearch'] : null;

    $page=isset($_GET['page']) ? $_GET['page'] : 1; //Nếu tồn tại, nó sẽ trả về giá trị của biến đó ($_GET['page']), ngược lại nó sẽ trả về giá trị mặc định là 1
    
    $limit=6;
    $offset = ($page - 1) * $limit;

    $bill=Bill::getallBillsPage($pdo,$limit, $offset, $searchTerm, $colSearch);

    $totalbill=Bill::getTotalBills($pdo, $searchTerm, $colSearch);
    $totalPages = ceil($totalbill / $limit);

    var_dump($totalSearch);

    if (isset($_GET['action']) && isset($_GET['iduser'])) {
        $action = $_GET['action'];
        $idus = $_GET['iduser'];
        if ($action == 'deleteuser') {
            if (isset($_SESSION['logged_user'])) {
                User::deleteUser($pdo, $idus);
                header("Location: qluser.php");
            }
        }
    }

?>



        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Quản lý đơn hàng</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <h1 class="mb-4">SunFresh</h1>
                <div class="row g-4">
                    <div class="col-lg-12">
                    <form action="qlbill.php" method="GET">
                            <div class="row g-4">
                                <div class="col-6"></div>
                                <div class="col-xl-3 ">
                                    <div class="mb-4">
                                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                                            
                                                <label for="fruits">Tìm kiếm theo:</label>
                                                <!-- Sử dụng onchange để gọi hàm JS khi có sự thay đổi -->
                                                <select name="colSearch" class="border-0 form-select-sm bg-light me-3">
                                                    <option value="idbill" <?= $colSearch == 'idbill' ? 'selected' : '' ?>>ID</option>
                                                    <option value="user" <?= $colSearch == 'user' ? 'selected' : '' ?>>Tên User</option>
                                                    <option value="sdt" <?= $colSearch == 'sdt' ? 'selected' : '' ?>>Số điện thoại</option>
                                                    <option value="tennhan" <?= $colSearch == 'tennhan' ? 'selected' : '' ?>>Tên người nhận</option>
                                                </select>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                    <div class="input-group w-100 mx-auto d-flex">
                                        <div class="d-flex">
                                            <input type="search" name="search" class="form-control p-3" placeholder="Tên sản phẩm" aria-describedby="search-icon-1" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                            <button type="submit" id="search-icon-1" class="input-group-text p-3" style=" border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row g-4">

                            <div class="col-lg-12">
                                <div class="row g-4 ">

                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Tên user</th>
                                                    <th>Người nhận</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Địa chỉ giao</th>
                                                    <th>Số điện thoại</th>
                                                    <th>Chi tiết</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($bill as $bi): ?>
                                                    <tr>
                                                        <form method="post">
                                                            <td><?php echo $bi['idbill']; ?></td>
                                                            <td><?php echo $bi['user']; ?></td>
                                                            <td><?php echo $bi['tennhan']; ?></td>
                                                            <td><?php echo $bi['ngaydat']; ?></td>
                                                            <td><?php echo $bi['diachigiao']; ?></td>
                                                            <td><?php echo $bi['sdt']; ?></td>
                                                            <td><a href="chitietbill.php?id=<?= $bi['idbill'] ?>" class="btn btn-warning">Chi tiết</a></td>
                                                        </form>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="pagination d-flex justify-content-center mt-5">
                                            <?php
                                                // Nút "Trang đầu tiên"
                                                if ($page == 1) {
                                                    echo '<span class="pagination-disabled rounded">&laquo;</span>';
                                                }else{
                                                    echo '<a href=" href="qlbill.php?page=1&' . (isset($searchTerm) ? '&search=' . $searchTerm : '') . (isset($colSearch) ? '&colSearch=' . $colSearch : '') . '" class="rounded">&laquo;</a>';

                                                }

                                                // Hiển thị số trang
                                                for ($i = 1; $i <= $totalPages; $i++) {
                                                    if ($i == $page) {
                                                        echo '<a href="#" class="active rounded">' . $i . '</a>';
                                                    } else {
                                                        echo '<a href="qlbill.php?page=' . $i . '&' . (isset($searchTerm) ? '&search=' . $searchTerm : '') . (isset($colSearch) ? '&colSearch=' . $colSearch : '') .  '" class="rounded">' . $i . '</a>';
                                                    }
                                                }

                                                // Nút "Trang cuối cùng"
                                                if ($page == $totalPages) {
                                                    echo '<span class="pagination-disabled rounded">&raquo;</span>';
                                                }else{
                                                    echo '<a href="qlbill.php?page=' . $totalPages . '&' . (isset($searchTerm) ? '&search=' . $searchTerm : '') . (isset($colSearch) ? '&colSearch=' . $colSearch : '') .  '" class="rounded">&raquo;</a>';

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