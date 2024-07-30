<?php

class User{
    public static function addUser($pdo, $user, $gioitinh, $email, $pass, $role) {
        $stmt = $pdo->prepare("INSERT INTO user (user, gioitinh, email, pass, role) VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $gioitinh);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $pass);
        $stmt->bindParam(5, $role);

        $stmt->execute();
        return true;
    }

    public static function updateUser($pdo, $id, $user, $gioitinh, $email, $role) {
        $stmt = $pdo->prepare("UPDATE user SET user = ?, gioitinh = ?, email = ?, role = ? WHERE iduser = ?");
        
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $gioitinh);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $role);
        $stmt->bindParam(5, $id);
    
        return $stmt->execute();
    }

    public static function isUsernameExists($pdo, $username, $lm) {
        // Sử dụng prepared statement để kiểm tra tên người dùng trong cơ sở dữ liệu
        $sql_check = "SELECT COUNT(*) AS count FROM user WHERE BINARY user = :username";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindValue(':username', $username);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        // Lấy số lượng bản ghi có tên người dùng cụ thể
        $count = $result_check['count'];

        if($count>$lm){
            return true;
        }
        else {
            return false;
        }
    }

    public static function isEmailExists($pdo, $email, $lm) {
        $sql_check = "SELECT COUNT(*) AS count FROM user WHERE BINARY email= :email";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindValue(':email', $email);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        $count = $result_check['count'];

        if($count>$lm){
            return true;
        }
        else {
            return false;
        }
    }
    

    public static function isValid($pdo, $username, $password) {
        $sql = "SELECT * FROM user WHERE BINARY user = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pass'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUser($pdo, $username, $password) {
        $sql = "SELECT * FROM user WHERE user = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['pass'])) {
            return $user;
        } else {
            return null;
        }
    }
    
    public static function logout() {
        session_start();
        unset($_SESSION['logged_role']);
        unset($_SESSION['logged_user']);
        header("location: index.php"); 
        exit;
    }

    public static function getAllUser($pdo) {
        $sql = "SELECT * FROM user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteUser($pdo, $userid) {
        $sql = "DELETE FROM user WHERE iduser = :userid";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function getOneUserById($pdo, $id)
    {
        $sql="SELECT * FROM user WHERE iduser=:id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":id",$id,PDO::PARAM_INT);

        if($stmt->execute()){
            $stmt->setFetchMode(PDO::FETCH_CLASS,"User");
            return $stmt->fetch();
        }
    }

    public static function changePassword($pdo, $username, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET pass = :password WHERE user = :username");

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    public static function isValidMail($pdo, $username, $email) {
        $sql = "SELECT * FROM user WHERE BINARY user = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $email == $user['email']) {
            return true;
        } else {
            return false;
        }
    }

    public static function changePasswordByEmail($pdo, $username, $email, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET pass = :password WHERE user = :username AND email = :email");

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }
}

?>