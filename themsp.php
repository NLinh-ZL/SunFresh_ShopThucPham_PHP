<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->

<?php
if(isset($_SESSION['logged_role']) && $_SESSION['logged_role'] === "admin") {
?>

<!-- =============================================================================================== -->

<?php
    // require_once "class/Database.php";
    require_once "class/Sanpham_loaisp.php";
    
?>

<?php
    // require_once "class/Database.php";
    // require_once "class/Loaisp.php";
    // $database = new Database();
    // $pdo = $database->getConnect();

    $loaisp=Loaisp::getAllProductTypes($pdo);
    
?>

<?php 
    // $database = new Database();
    // $pdo = $database->getConnect();

    // $loaisp=Loaisp::getAllProductTypes($pdo);


    $tenError='';
    $giaError='';
    $dvtError='';
    $xuatxuError='';
    $motaError='';
    $hinhError='';
    

    $ten='';
    $gia='';
    $dvt='';
    $xuatxu='';
    $mota='';

    $anh='';
    $tenanh='';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $ten =$_POST['ten'];
        $gia =$_POST['gia'];
        $dvt =$_POST['dvt'];
        $xuatxu =$_POST['xuatxu'];
        $mota =$_POST['mota'];

        //Kiểm trả tên sản phẩm đã có chưa
        $tenLowerCase = strtolower($ten);
        $sql_check = "SELECT COUNT(*) AS count FROM sanpham WHERE LOWER(tensp) = :tensp";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindValue(':tensp', $tenLowerCase);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if(empty($ten)){
            $tenError='Phải nhập tên sản phẩm';
        }
        else if($result_check['count'] > 0){
            $tenError='Tên sản phẩm đã tồn tại, bạn hãy nhập tên sản phẩm khác';
        }else if(ucfirst($ten) !== $ten) {
            $tenError='Chữ đầu của tên sản phẩm phải viết hoa';
        }
    
        if(empty($gia)){
            $giaError='Phải nhập giá sản phẩm';
        }
        else if($gia%1000 != 0){
            $giaError='Giá phải chia hết cho 1000';
        }
    
        if(empty($dvt)){
            $dvtError='Phải nhập đơn vị tính sản phẩm';
        }else if(ucfirst($dvt) !== $dvt) {
            $dvtError='Chữ đầu của đơn vị tính phải viết hoa';
        }
    
        if(empty($xuatxu)){
            $xuatxuError='Phải nhập xuất xứ sản phẩm';
        }else if(ucfirst($xuatxu) !== $xuatxu) {
            $xuatxuError='Chữ đầu của xuất xứ phải viết hoa';
        }

        if(empty($mota)){
            $motaError='Phải nhập mô tả sản phẩm';
        }
    
        if(!$tenError && !$giaError && !$dvtError && !$xuatxuError && !$motaError)
        {
            if(isset($_FILES['hinh']) && $_FILES['hinh']['error'] !== UPLOAD_ERR_NO_FILE){
                $anh = $_FILES['hinh'];
                var_dump($anh);
                $tenanh = $anh['name'];
                $file_size = $anh['size'];

                switch($anh['error']){
                    case UPLOAD_ERR_OK:
                        break;
                    default:
                        $hinhError = 'Đã có lỗi';
                }

                $max_file_size = 2 * 1024 * 1024; // 2MB
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // Lấy phần mở rộng của tệp tin
                $file_extension = strtolower(pathinfo($tenanh, PATHINFO_EXTENSION));
                $allowed_extensions = ['gif', 'jpeg', 'jpg', 'png'];
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                $dest = "imgsp/" . $tenanh;
                $i = 1;
                while(file_exists($dest)) {
                    $dest = "imgsp/" . "$i-" . $tenanh;
                    $i++;
                }
                

                if ($file_size > $max_file_size) {
                    $hinhError = 'Kích thước của ảnh quá lớn. Vui lòng chọn ảnh nhỏ hơn 2MB.';
                }
                elseif (!in_array($file_extension, $allowed_extensions)) {
                    $hinhError = "Phải upload 1 hình ảnh định dạng GIF, JPEG, JPG hoặc PNG.";
                } 
                else {
                    move_uploaded_file($anh['tmp_name'], $dest);
                }
            } else {
                $tenanh = "noImage.jpg";
            } 

            if(!$hinhError)
            {
                $row = array(
                    'tensp' => $ten,
                    'gia' => $gia,
                    'donvitinh' => $dvt,
                    'xuatxu' => $xuatxu,
                    'mota' => $mota,
                    'hinh' => $tenanh
                );
                $sql = "INSERT INTO sanpham (tensp, gia, donvitinh, xuatxu, mota, hinh) VALUES (:tensp, :gia, :donvitinh, :xuatxu, :mota, :hinh)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($row);
                // Lấy ID của sản phẩm mới được chèn
                $newproductId = $pdo->lastInsertId();
    
                if (isset($_POST['idloai']) && is_array($_POST['idloai'])) {
                    foreach ($_POST['idloai'] as $categoryId) {
                        // Xử lý dữ liệu của mỗi category id đã được chọn ở đây
                        Sanpham_loaisp::insertSp_loaisp($pdo, $categoryId, $newproductId);
                    }
                }
                header("Location: qlsp.php");
                exit;
                // Tiếp tục xử lý tệp ảnh và các dữ liệu khác
            }

        }
        
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Thêm sản phẩm</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Thêm sản phẩm</h1>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên sản phẩm<sup>*</sup></label>
                                <input type="text" class="form-control" id="ten" name="ten" value="<?= $ten ?>">
                                <span class="text-danger"><?= $tenError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Giá sản phẩm<sup>*</sup></label>
                                <input type="text" class="form-control" id="gia" name="gia" value="<?= $gia ?>">
                                <span class="text-danger"><?= $giaError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Đơn vị tính <sup>*</sup></label>
                                <input type="text" class="form-control" id="dvt" name="dvt" value="<?= $dvt ?>">
                                <span class="text-danger"><?= $dvtError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Xuất xứ<sup>*</sup></label>
                                <input type="text" class="form-control" id="xuatxu" name="xuatxu" value="<?= $xuatxu ?>">
                                <span class="text-danger"><?= $xuatxuError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Hình ảnh sản phẩm<sup>*</sup></label>
                                <input type="file" class="form-control" id="hinh" name="hinh">
                                <span class="text-danger"><?= $hinhError ?></span>
                            </div>


                            <hr>
                            <div class="form-item">
                                <label class="form-label my-3">Loại sản phẩm<sup>*</sup></label>
                            </div>

                                <?php 
                                $count = 0;
                                foreach ($loaisp as $type) {
                                    // Bắt đầu một hàng mới sau khi hiển thị hai checkbox
                                    if ($count % 2 == 0) {
                                ?>
                                        <div class="row">
                                <?php 
                                    }
                                ?>
                                    <div class="col-md-6">
                                        <div class="form-check my-3 w-100">
                                        <?php
                                            // Kiểm tra nếu ID loại sản phẩm tồn tại trong dữ liệu gửi đi từ form
                                            $checked = '';
                                            if(isset($_POST['idloai']) && in_array($type->idloai, $_POST['idloai'])) {
                                                $checked = 'checked';
                                            }
                                        ?>
                                            <input type="checkbox" class="form-check-input bg-primary border-0" id="<?= $type->idloai ?>" name="idloai[]" value="<?= $type->idloai ?>" <?= $checked ?>>
                                            <label class="form-check-label" for="<?= $type->idloai ?>"><?= $type->tenloai ?></label>
                                        </div>
                                    </div>
                                <?php 
                                    // Đóng một hàng nếu hiển thị hai checkbox hoặc đến hàng cuối
                                    if ($count % 2 == 1 || $count == count($loaisp) - 1) {
                                ?>
                                        </div>
                                <?php
                                    }
                                    $count++;
                                }
                                ?>
                            <hr>

                            <div class="form-item">
                                <label class="form-label my-3">Mô tả<sup>*</sup></label>
                                <textarea class="form-control" spellcheck="false" cols="30" rows="11" placeholder="Mô tả sản phẩm" id="mota" name="mota"><?= $mota ?></textarea>
                                <span class="text-danger"><?= $motaError ?></span>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Thêm</button>
                            </div>

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














<!-- <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-check my-3 w-100">
                                        <input type="checkbox" class="form-check-input bg-primary border-0" id="Account-1" name="Accounts" value="Accounts">
                                        <label class="form-check-label" for="Account-1">Create an account?</label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-check my-3 w-100">
                                        <input type="checkbox" class="form-check-input bg-primary border-0" id="Account-1" name="Accounts" value="Accounts">
                                        <label class="form-check-label" for="Account-1">Create an account?</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-check my-3 w-100">
                                        <input type="checkbox" class="form-check-input bg-primary border-0" id="Account-1" name="Accounts" value="Accounts">
                                        <label class="form-check-label" for="Account-1">Create an account?</label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-check my-3 w-100">
                                        <input type="checkbox" class="form-check-input bg-primary border-0" id="Account-1" name="Accounts" value="Accounts">
                                        <label class="form-check-label" for="Account-1">Create an account?</label>
                                    </div>
                                </div>
                            </div> -->


