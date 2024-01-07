<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $program_serial=$_POST["program_serial"];
    $capa_serial = $_POST["capa_serial"];
    $level = $_POST["capa_level"];

    $sql = " INSERT INTO program_capacity (program_serial, capa_serial, capa_level ) 
            VALUES('".$program_serial."', '".$capa_serial."','".$level."')";
    $ret = mysqli_query($con,$sql);

    echo "<h2> 프로그램 역량 추가 </h2>";
    if($ret) {
        echo "데이터가 성공적으로 추가됨.";
        header('Location: side_bar.php?tab=program');
        exit;
    }
    else {
        echo "데이터 추가 실패 !"."<br>";
        echo "실패 원인 :".mysqli_error($con);
    }
    mysqli_close($con);

    mysqli_close($con);

    echo "<br><a href = 'side_bar.php?tab=program'> <--프로그램 목록으로</a? ";
   
?>

