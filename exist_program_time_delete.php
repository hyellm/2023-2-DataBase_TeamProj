<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';

    $sql ="SELECT *FROM program_time 
            WHERE program_serial='".$_GET['program_serial']."' 
            AND start_time='".$_GET['start_time']."'";

    if(!empty($start_date)) {
        $sql .= " AND start_date='".$start_date."'";
    }

    $ret = mysqli_query($con, $sql);

    $sql1 ="SELECT *FROM program WHERE program_serial='".$_GET['program_serial']."'";
    $ret1 = mysqli_query($con, $sql1);
    
    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['program_serial']." 해당 시리얼의 프로그램이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--프로그램 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--프로그램 목록으로</a? ";
        exit();
    }
    
    $row=mysqli_fetch_array($ret);
    $row_pr=mysqli_fetch_array($ret1);
    
    $program_name=$row_pr['program_name'];
    $program_serial=$row["program_serial"];
    $start_time=$row["start_time"];
    $finish_time =$row['finish_time'];
    $start_date=$row['start_date'];
    $finish_date=$row['finish_date'];
    $day = $row["day"];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];
        $start_date=$_POST["start_date"];
        $start_time=$_POST["start_time"];
    
        $sql="DELETE FROM program_time 
                WHERE program_serial='".$program_serial."' AND start_time='".$start_time."' AND start_date='".$start_date."'";
    
        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 수정됨.";
            header('Location: side_bar.php?tab=centerScheduleManage');
            exit;
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--기존 일정 관리로</a? ";
        exit();
    }
?>

<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
    <title>프로그램 시간</title>
</HEAD>
<BODY>
    <h1>프로그램 시간 삭제</h1>
    <FORM METHOD="post" ACTION="">
        프로그램 번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램 이름 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?> readonly>
        <br>
        시작 날짜 : <INPUT TYPE = "date" NAME="start_date" VALUE=<?php echo $start_date?> readonly>
        <br>
        요일 : <INPUT TYPE = "text" NAME="day" VALUE=<?php echo $day?> readonly>
        <br>
        종료 날짜 : <INPUT TYPE = "date" NAME="finish_date" VALUE=<?php echo $finish_date?> readonly>
        <br>
        시작 시간 : <INPUT TYPE = "time" NAME="start_time" VALUE=<?php echo $start_time?> readonly>
        <br>
        종료 시간 : <INPUT TYPE = "time" NAME="finish_time" VALUE=<?php echo $finish_time?> readonly>
        <br><br>
        <INPUT TYPE="submit" VALUE="시간 삭제">
    </FORM>
</BODY>
</HTML>