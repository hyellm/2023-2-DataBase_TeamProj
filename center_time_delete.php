
<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql_center_time = "SELECT * FROM center_time
                        WHERE center_serial='" . $_GET['center_serial'] . "'
                        AND day='" . $_GET['day'] . "'";
    $ret_center_time = mysqli_query($con, $sql_center_time);

    if($ret_center_time) {
        $count=mysqli_num_rows($ret_center_time);
        if($count==0){
            echo $_GET['center_serial']." 해당 시리얼의 센터 시간이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=centerManage'> <--초기화면</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=centerManage'> <--초기화면</a? ";
        exit();
    }

    while($row = mysqli_fetch_assoc($ret_center_time))
    {
        $center_serial = $row['center_serial'];
        $day = $row['day'];
        $start_time = $row['start_time'];
        $finish_time = $row['finish_time'];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $center_serial=$_POST["center_serial"];
        $day=$_POST["day"];
        $start_time=$_POST["start_time"];
    
        $sql="DELETE FROM center_time
            WHERE center_serial = '".$center_serial."' AND day='".$day."' AND start_time='".$start_time."'";
    
        $ret = mysqli_query($con, $sql);
    
        if($ret) {
            echo "데이터가 성공적으로 삭제됨.";
        }
        else {
            echo "데이터 삭제 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        
        echo "<br> <a href='side_bar.php?tab=centerManage'> <--초기화면</a? ";
        exit();
    }

?>

<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
</HEAD>
<BODY>
    <h1>센터 정보 삭제</h1>
    <FORM METHOD="post" ACTION="">
        <INPUT TYPE = "hidden" NAME="center_serial" VALUE=<?php echo $center_serial?>>
        요일 : <INPUT TYPE = "text" NAME="day" VALUE=<?php echo $day?> readonly>
        <br>
        시작 시간 : <INPUT TYPE = "time" NAME="start_time" VALUE=<?php echo $start_time?> readonly>
        <br>
        종료 시간 : <INPUT TYPE = "time" NAME="finish_time" VALUE=<?php echo $finish_time?> readonly>
        <br>

        <br><br>
        위 시간 정보를 삭제하시겠습니까?
        <INPUT TYPE="submit" VALUE="시간 삭제">
    </FORM>


</BODY>
</HTML>
