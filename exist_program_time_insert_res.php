<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
     
    $program_serial=$_POST["program_serial"];
    $start_time = $_POST["start_time"];
    $finish_time = $_POST["finish_time"];
    $start_date = $_POST["start_date"];
    $finish_date = $_POST["finish_date"];
    $day = $_POST['day'];
  
    $sql = " INSERT INTO program_time (program_serial, start_time, finish_time, start_date, finish_date, day ) 
            VALUES('".$program_serial."', '".$start_time."','".$finish_time."','".$start_date."','".$finish_date."','".$day."')";
    $ret = mysqli_query($con,$sql);

    echo "<h2> 프로그램 시간 추가 </h2>";
    if($ret) {
        echo "데이터가 성공적으로 추가됨.";
        header('Location: side_bar.php?tab=centerScheduleManage');
        exit;
    }
    else {
        echo "데이터 추가 실패!<br>";
        echo "실패 원인 :".mysqli_error($con);
    }
    mysqli_close($con);

    echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--기존 일정 관리로</a> ";

?>