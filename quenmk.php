<?php
    // require_once "class/Loaisp.php";
    require_once "inc/headers.php";
?>
<!-- =============================================================================================== -->
<?php
if(isset($_SESSION['logged_user'])) {
    header("Location: 404.php");
    exit;
}
?>

<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    require_once "class/User.php";
    $emailError='';
    $userError='';

    $user='';
    $email='';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user=$_POST['user'];
        $email=$_POST['email'];

        if(empty($user)){
            $userError='Hãy nhập tên đăng nhập';
        }elseif(!User::isUsernameExists($pdo, $user, 0)){
            $userError='Tên đăng nhập này không tồn tại';
        }

        $IsvalidResult = User::isValidMail($pdo, $user, $email);
        if(empty($email)){
            $emailError='Hãy nhập email';
        }elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)){
            $emailError='Email không hợp lệ';
        }elseif($IsvalidResult === false){
            $emailError='Email này không phải Email bạn đã đăng ký với chúng tôi';
        }


        if(empty($emailError)&& empty($userError)) {
            
            $mkmoi = substr(md5(rand(0,999999)),0,8);
            User::changePasswordByEmail($pdo, $user, $email, $mkmoi);

            $mail = new PHPMailer(true);
            try {
                // Cấu hình máy chủ SMTP
                // $mail->SMTPDebug = 2;
                $mail->isSMTP(); // Sử dụng SMTP để gửi mail
                $mail->Host = 'smtp.gmail.com'; // Server SMTP của gmail
                $mail->SMTPAuth = true; // Bật xác thực SMTP
                $mail->Username = 'linh2003116@gmail.com'; // Tài khoản email
                $mail->Password = 'uhlb mwkn ijkf mlar'; // Mật khẩu ứng dụng ở bước 1 hoặc mật khẩu email
                $mail->SMTPSecure = 'ssl'; // Mã hóa SSL
                $mail->Port = 465; // Cổng kết nối SMTP là 465
    
                //Recipients
                $mail->setFrom('linh2003116@gmail.com', 'SunFresh'); // Địa chỉ email và tên người gửi
                $mail->addAddress($email, 'Khach'); // Địa chỉ mail và tên người nhận
    
                // Nội dung email
                $mail->isHTML(true);
                $mail->Subject = 'Yeu cau reset Passwork';
                $mail->Body = 'Mật khẩu của bạn:' . $mkmoi;
                $mail->AltBody = 'Mật khẩu của bạn:' . $mkmoi;
    
                // Gửi email
                $mail->send();
                // $sendemail= 'Email đã được gửi. Vui lòng kiểm tra email của bạn để reset mật khẩu.';
                header("Location: dangnhap.php");
                exit;
            } catch (Exception $e) {
                $sendemail= 'Không thể gửi email. ';
            }
    

            // header("Location: dangnhap.php");
            //
        }
    }
?>

        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Quên mật khẩu</h1>
        </div>
        <!-- Single Page Header End -->


        <!-- Checkout Page Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Quên mật khẩu</h1>

                <form method="post">
                    <div class="row g-5">

                        <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">

                            <div class="form-item">
                                <label class="form-label my-3">Tên đăng nhập<sup>*</sup></label>
                                <input type="text" class="form-control" id="user" name="user" value="<?= $user ?>">
                                <span class="text-danger"><?= $userError ?></span>
                            </div>
                            <div class="form-item">
                                <label class="form-label my-3">Email<sup>*</sup></label>
                                <input type="text" class="form-control" id="email" name="email" value="<?= $email ?>" placeholder="Hãy nhập email bạn đã đăng ký với user ở trên">
                                <span class="text-danger"><?= $emailError ?></span>
                            </div>

                            <div class="row g-4 text-center align-items-center justify-contenrt-center pt-4" style="margin-top: 10px;">
                                <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Xác nhận</button>
                            </div>


                        </div>
                         
                    </div>
                </form>
            </div>
        </div>
        <!-- Checkout Page End -->


<!-- =============================================================================================== -->
<?php 
    require_once "inc/footer.php";
?>