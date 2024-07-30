<?php
class Sanpham_loaisp {
    public static function insertSp_loaisp($pdo, $idloai, $idsp) {
        $sql = "INSERT INTO sanpham_loaisp (idloai, idsp) VALUES (:idloai, :idsp)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idloai', $idloai, PDO::PARAM_INT);
        $stmt->bindParam(':idsp', $idsp, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }

    public static function deleteLoaiByProductId($pdo, $productId) {
        $sql = "DELETE FROM sanpham_loaisp WHERE idsp = :productId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();
        
        return true;
    }
}
?>