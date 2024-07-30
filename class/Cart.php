<?php
class Cart{

    public static function getCartByUserTT($pdo, $iduser) {
        $sql = "SELECT crt.idcart, sp.hinh, sp.tensp, sp.gia, sp.donvitinh, crt.soluong 
                FROM cart crt
                LEFT JOIN sanpham sp ON crt.idsp = sp.idsp
                WHERE crt.iduser = :iduser
                ORDER BY crt.idcart DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCartByUser($pdo, $iduser, $offset, $limit) {
        $sql = "SELECT crt.idcart, sp.hinh, sp.tensp, sp.gia, sp.donvitinh, crt.soluong 
                FROM cart crt
                LEFT JOIN sanpham sp ON crt.idsp = sp.idsp
                WHERE crt.iduser = :iduser
                ORDER BY crt.idcart DESC
                LIMIT :offset, :limit"; // Thêm LIMIT để phân trang
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCartByUserForBill($pdo, $iduser) {
        $sql = "SELECT crt.idcart, sp.hinh, sp.tensp, sp.gia, crt.soluong 
                FROM cart crt
                LEFT JOIN sanpham sp ON crt.idsp = sp.idsp
                WHERE crt.iduser = :iduser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getOneCardByIdProduct($pdo, $proid,$loggedIdUser)
    { 
       
        $sql = "SELECT * FROM cart WHERE idsp =:proid AND iduser = :iduser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":proid", $proid, PDO::PARAM_INT);
        $stmt->bindParam(":iduser", $loggedIdUser, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $stmt -> setFetchMode(PDO::FETCH_CLASS,"Cart");
            return $stmt->fetch();
        } 
    }
    
    

    public static function getCartCountByUser($pdo, $iduser) {
        $sql = "SELECT COUNT(*) FROM cart WHERE iduser = :iduser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }    
    

    public static function insertCart($pdo,$Idproduct, $Quantity,$iduser) {
        $sql="INSERT INTO cart (soluong,iduser,idsp) VALUES ( '$Quantity', '$iduser','$Idproduct')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();        
        return true;
    }

    public static function deleteCartItem($pdo, $cartItemId) {
        $sql = "DELETE FROM cart WHERE idcart = :cartItemId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cartItemId', $cartItemId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public static function update($pdo,$cartid, $Quantity) {
        $sql = "UPDATE cart SET soluong = :newQuantity WHERE idcart = :cartid ";
            
            // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newQuantity', $Quantity, PDO::PARAM_INT);
        $stmt->bindParam(':cartid', $cartid, PDO::PARAM_INT);

        $stmt->execute();

       
        return true; // Trả về true nếu tên đăng nhập và mật khẩu đều đúng
    }

    public static function delete($pdo,$iduser) {
        // Kiểm tra nếu ID của mục giỏ hàng không hợp lệ

        $sql = "DELETE FROM cart Where iduser = :iduser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>