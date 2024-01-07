<?php
    session_start();
    
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM program WHERE program_serial='".$_GET['program_serial']."'";
    $ret = mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['program_serial']." 해당 시리얼의 프로그램이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);
    $program_name = $row["program_name"];

    $sql1 ="SELECT *FROM program_time 
            WHERE program_serial='".$_GET['program_serial']."' AND start_date='".$_GET['start_date']."'AND start_time='".$_GET['start_time']."' ";

    $ret1 = mysqli_query($con, $sql1);
    $row=mysqli_fetch_array($ret1);
    $program_serial=$row["program_serial"];
    $start_time=$row["start_time"];
    $finish_time =$row['finish_time'];
    $start_date=$row['start_date'];
    $finish_date=$row['finish_date'];
    $day = $row["day"];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];
        $original_start_time=$_POST["original_start_time"];
        $original_start_date=$_POST["original_start_date"];
    
        $start_time=$_POST['start_time'];
        $finish_time =$_POST['finish_time'];
        $start_date=$_POST['start_date'];
        $finish_date=$_POST['finish_date'];
        $day = $_POST['day'];
       
        $sql = "UPDATE program_time
                SET start_time='".$start_time."', finish_time='".$finish_time."',
                start_date='".$start_date."', finish_date='".$finish_date."',day='".$day."'
                WHERE program_serial = '".$program_serial."' AND start_date='".$original_start_date."'
                AND start_time='".$original_start_time."'";

        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 수정됨.";
            header('Location: side_bar.php?tab=program');
            exit;
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a? ";
        exit();
    }

    // 현재 페이지 정보를 세션 변수에 저장
    $_SESSION['previous_page'] = 'program_time_update.php';

    
?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
    <title>프로그램 시간</title>
</HEAD>
<BODY>
    <h1>프로그램 시간 수정</h1>
    <FORM METHOD="post" ACTION="">
    <input type="hidden" name="original_start_time" value="<?php echo $row['start_time']; ?>">
    <input type="hidden" name="original_start_date" value="<?php echo $row['start_date']; ?>">

        프로그램 번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램 이름 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?> readonly>
        <br>
        실행 요일 : <select name="day">
                <option value="월" <?php if ($day == '월') echo 'selected'; ?>>월요일</option>
                <option value="화" <?php if ($day == '화') echo 'selected'; ?>>화요일</option>
                <option value="수" <?php if ($day == '수') echo 'selected'; ?>>수요일</option>
                <option value="목" <?php if ($day == '목') echo 'selected'; ?>>목요일</option>
                <option value="금" <?php if ($day == '금') echo 'selected'; ?>>금요일</option>
                <option value="토" <?php if ($day == '토') echo 'selected'; ?>>토요일</option>
                <option value="일" <?php if ($day == '일') echo 'selected'; ?>>일요일</option>
            </select>
        <br>
        시작 날짜 : <INPUT TYPE = "date" NAME="start_date" VALUE=<?php echo $start_date?>>
        <br>
        종료 날짜 : <INPUT TYPE = "date" NAME="finish_date" VALUE=<?php echo $finish_date?>>
        <br>
        시작 시간 : <INPUT TYPE = "time" NAME="start_time" VALUE=<?php echo $start_time?>>
        <br>
        종료 시간 : <INPUT TYPE = "time" NAME="finish_time" VALUE=<?php echo $finish_time?>>
        <br><br>
        <input Type="submit" VALUE="시간 수정">
    </FORM>

</BODY>
</HTML>

