<?php
    class Database{
        public function getConnect(){
            $host="localhost";
            $db="ql_raucu";
            $username="db_admin";
            $password="-IKjfb.Xo0ddqPhG";

            $dsn="mysql:host=$host;dbname=$db;charset=UTF8";

            try{
                $pdo = new PDO($dsn, $username, $password);
                if($pdo){
                    return $pdo;
                }
            } catch (PDOException $ex){
                echo $ex->getMessage();
                exit;
            }
        }
    }
?>