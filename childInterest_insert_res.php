<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $child_serial=$_POST['child_serial'];
    $cate_serial=$_POST['cate_serial'];
    $inter_level=$_POST['inter_level'];

    $sql = "INSERT INTO child_interest
            VALUES('".$child_serial."', '".$cate_serial."', '".$inter_level."')";

    $ret=mysqli_query($con,$sql);

    echo "<h2>아동 흥미도 추가</h2>";
    if($ret) {
        echo "데이터가 성공적으로 추가됨.";
        header('Location: side_bar.php?tab=child');
        exit;
    }
    else {
        echo "데이터 추가 실패 !"."<br>";
        echo "실패 원인 :".mysqli_error($con);
    }
    mysqli_close($con);
    
    echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
?>