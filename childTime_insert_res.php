<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $child_serial=$_POST['child_serial'];
    $day=$_POST['day'];
    $start_time=$_POST['start_time'];
    $finish_time=$_POST['finish_time'];

    $sql = "INSERT INTO child_time
            VALUES('".$child_serial."', '".$day."', '".$start_time."', '".$finish_time."')";

    $ret=mysqli_query($con,$sql);

    echo "<h2>아동 시간 추가</h2>";
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