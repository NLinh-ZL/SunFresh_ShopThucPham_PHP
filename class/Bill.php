<?php

class Bill {
    public static function getallBillsPage($pdo, $limit, $offset, $searchTerm = null, $colSearch = null) {
        $sql = "SELECT b.idbill, us.user, b.tennhan, b.ngaydat, b.diachigiao, b.sdt 
                FROM bill b 
                INNER JOIN user us ON b.iduser = us.iduser";
        
        if ($searchTerm !== null && $colSearch !== null) {
            $sql .= " WHERE $colSearch LIKE :searchTerm";
        }
        
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $pdo->prepare($sql);
        
        if ($searchTerm !== null && $colSearch !== null) {
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public static function getTotalBills($pdo, $searchTerm, $colSearch) {
        $sql = "SELECT COUNT(*) as total
                FROM bill b 
                INNER JOIN user us ON b.iduser = us.iduser";
        
        if ($searchTerm !== null && $colSearch !== null) {
            $sql .= " WHERE $colSearch LIKE :searchTerm";
        }
        
        $stmt = $pdo->prepare($sql);
        
        if ($searchTerm !== null && $colSearch !== null) {
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } else {
            return 0;
        }
    }

    public static function getOneBillByID($pdo, $id) {
        $sql = "SELECT b.idbill, us.user, b.tennhan, b.ngaydat, b.diachigiao, b.sdt, b.ghichu, b.tongtien
                FROM bill b 
                INNER JOIN user us ON b.iduser = us.iduser
                WHERE idbill=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"Bill");
            return $stmt->fetch();
        }
    }
    
}


?>