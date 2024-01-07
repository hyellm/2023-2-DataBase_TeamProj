<?php
    function connectDatabase() {
        $con = mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
        return $con;
    }
?>
