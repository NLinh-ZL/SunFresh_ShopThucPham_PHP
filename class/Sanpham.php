<?php
class Sanpham{
    public $idsp;
    public $tensp;
    public $mota;
    public $gia;
    public $donvitinh;
    public $xuatxu;
    public $hinh;

    public static function getallProducts($pdo){
        $sql="SELECT * FROM sanpham";
        $stmt = $pdo->prepare($sql);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Sanpham");
            return $stmt->fetchAll();
        }
    }
        // if($stmt->execute()){
        //     $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     return $products;
        // }
    //}

    public static function getLatestProducts($pdo, $limit){
        $sql = "SELECT * FROM sanpham ORDER BY idsp DESC LIMIT :limit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    
        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Sanpham");
            return $stmt->fetchAll();
        }
    }

    // public static function getallProductsPage($pdo, $limit, $offset, $cot, $kieusx){
    //     $sql = "SELECT * FROM sanpham ORDER BY $cot $kieusx LIMIT :limit OFFSET :offset";
    //     $stmt = $pdo->prepare($sql);
    
    //     $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    //     if ($stmt->execute()) {
    //         $stmt->setFetchMode(PDO::FETCH_CLASS, "Sanpham");
    //         return $stmt->fetchAll();
    //     }
    // }

    public static function getallProductsPage($pdo, $limit, $offset, $cot, $kieusx, $loaiId) {
        $sql = "SELECT sanpham.* FROM sanpham";
        
        if ($loaiId !== null) {
            $sql .= " INNER JOIN sanpham_loaisp ON sanpham.idsp = sanpham_loaisp.idsp 
                      WHERE sanpham_loaisp.idloai = :loaiId";
        }
        
        $sql .= " ORDER BY $cot $kieusx LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        
        if ($loaiId !== null) {
            $stmt->bindValue(':loaiId', $loaiId, PDO::PARAM_INT);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Sanpham");
            return $stmt->fetchAll();
        }
    }

    public static function searchProductsByName($pdo, $searchTerm, $limit, $offset, $cot, $kieusx) {
        $sql = "SELECT * FROM sanpham WHERE tensp LIKE :searchTerm ORDER BY $cot $kieusx LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    
        $searchTerm = "%" . $searchTerm . "%"; // Tìm bất kỳ chuỗi nào chứa $searchTerm
        $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Sanpham");
            return $stmt->fetchAll();
        }
    }

    public static function countSearchResults($pdo, $searchTerm) {
        $sql = "SELECT COUNT(*) AS total FROM sanpham WHERE tensp LIKE :searchTerm";
        $stmt = $pdo->prepare($sql);
    
        $searchTerm = "%" . $searchTerm . "%"; // Tìm bất kỳ chuỗi nào chứa $searchTerm
        $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }
    }
    
    
    
    public static function getOneProductById($pdo, $id)
    {
        $sql="SELECT * FROM sanpham WHERE idsp=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Sanpham");
            return $stmt->fetch();
        }
    }

    public static function deleteProductById($pdo, $id)
    {
        $sql="DELETE FROM sanpham WHERE idsp=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Sanpham");
            return $stmt->fetch();
        }
    }

    

}
?>