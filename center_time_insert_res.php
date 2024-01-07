<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $day=$_POST['day'];
    $start_time=$_POST['start_time'];
    $finish_time=$_POST['finish_time'];
    // $school=$_POST['school'];
    // $grade=$_POST['grade'];
    // $child_tel=$_POST['child_tel'];

    $sql = "INSERT INTO center_time(center_serial, day, start_time, finish_time)
            VALUES(1, '".$day."', '".$start_time."', '".$finish_time."')";

    $ret=mysqli_query($con, $sql);

    if($ret) {
        echo "데이터가 성공적으로 수정됨.";
    }
    else {
        echo "데이터 수정 실패 !"."<br>";
        echo "실패 원인 :".mysqli_error($con);
    }
    mysqli_close($con);
    
    echo "<br> <a href='side_bar.php?tab=centerManage'> <--초기화면</a? ";
?>