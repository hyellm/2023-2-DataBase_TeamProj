<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $program_name = $_POST["program_name"];
    $detail = $_POST["program_detail"];
    $money = isset($_POST["program_money"]) && $_POST["program_money"] !== '' ? $_POST["program_money"] : NULL; 
    $people = isset($_POST["min_people"]) && $_POST["min_people"] !== '' ? $_POST["min_people"] : NULL; 
    $number = $_POST["number"];
    $long_yn = $_POST["long_yn"];
    $center_serial=$_POST['center_serial'];

    $sql = "INSERT INTO program (center_serial, program_name, program_detail, inout_sep, program_money, min_people, number, long_yn) 
            VALUES('".$center_serial."', '".$program_name."', '".$detail."', 2, "
            .($money !== NULL ? "'".$money."'" : "NULL").", "
            .($people !== NULL ? "'".$people."'" : "NULL").", '".$number."', '".$long_yn."')";
    
    $ret = mysqli_query($con,$sql);

    if($ret){
        $program_serial = mysqli_insert_id($con);
        echo "데이터가 성공적으로 입력됨. Program Serial: $program_serial";
        header('Location: side_bar.php?tab=program');
        exit;
    }
    else {
        echo "실패 원인 :".mysqli_error($con);
    }
    mysqli_close($con);

    echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a> ";

?>
