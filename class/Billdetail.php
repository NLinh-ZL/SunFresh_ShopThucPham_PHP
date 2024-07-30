<?php
class Billdetail {

    public static function insertBillDetails($pdo, $idbill, $iduser) {
        $sqlCart = "SELECT cart.idsp, sanpham.tensp, cart.soluong, sanpham.gia 
                    FROM cart
                    JOIN sanpham ON cart.idsp = sanpham.idsp
                    WHERE cart.iduser = :iduser";
        $stmtCart = $pdo->prepare($sqlCart);
        $stmtCart->bindParam(':iduser', $iduser);
        $stmtCart->execute();
        $cartDetails = $stmtCart->fetchAll();

        foreach ($cartDetails as $detail) {
            $sqlBillDetail = "INSERT INTO billdetail (idbill, idsp, tensp, soluong, gia) 
                                VALUES (:idbill, :idsp, :tensp, :soluong, :gia)";
            $stmtBillDetail = $pdo->prepare($sqlBillDetail);
            $stmtBillDetail->bindParam(':idbill', $idbill);
            $stmtBillDetail->bindParam(':idsp', $detail['idsp']);
            $stmtBillDetail->bindParam(':tensp', $detail['tensp']);
            $stmtBillDetail->bindParam(':soluong', $detail['soluong']);
            $stmtBillDetail->bindParam(':gia', $detail['gia']);
            $stmtBillDetail->execute();
        }

        $sqlDeleteCart = "DELETE FROM cart WHERE iduser = :iduser";
        $stmtDeleteCart = $pdo->prepare($sqlDeleteCart);
        $stmtDeleteCart->bindParam(':iduser', $iduser);
        $stmtDeleteCart->execute();

        return true;

    }

    public static function getDetailBillByID($pdo, $id) {
        $sql="SELECT * FROM billdetail WHERE idbill=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        }
    }
}
?>
