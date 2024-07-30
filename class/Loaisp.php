<?php 
class Loaisp{
    public $idloai;
    public $tenloai;

    public static function getAllProductTypes($pdo) {
        $sql = "SELECT * FROM loaisp";
        $stmt = $pdo->prepare($sql);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Loaisp");
            return $stmt->fetchAll();
        }
        return false;
    }

    public static function countProductsByCategory($pdo) {
        // Truy vấn SQL để đếm số lượng sản phẩm thuộc mỗi loại
        // LEFT JOIN được sử dụng để đảm bảo rằng tất cả các loại sản phẩm từ bảng loaisp sẽ được hiển thị
        $sql = "SELECT l.idloai, l.tenloai, COUNT(sl.id) AS soluong
                FROM loaisp l
                LEFT JOIN sanpham_loaisp sl ON l.idloai = sl.idloai
                GROUP BY l.idloai";

        // Chuẩn bị và thực thi truy vấn
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Lấy kết quả từ truy vấn
        $loaisp = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $loaisp;
    }

    public static function getCategoryNameById($pdo, $idloai) {
        $sql = "SELECT tenloai FROM loaisp WHERE idloai = :idloai";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idloai', $idloai, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category['tenloai'];
    }

    public static function getLoaiByProductId($pdo, $productId) {
        $sql = "SELECT loaisp.idloai, loaisp.tenLoai
                FROM loaisp
                INNER JOIN sanpham_loaisp ON loaisp.idloai = sanpham_loaisp.idloai
                WHERE sanpham_loaisp.idsp = :productId";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['productId' => $productId]);
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Loaisp");
        return $stmt->fetchAll();
    }

}

?>