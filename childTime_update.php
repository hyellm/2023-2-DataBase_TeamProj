<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM child_time
            WHERE child_serial='".$_GET['child_serial']."' AND day='".$_GET['day']."'
            AND start_time='".$_GET['start_time']."'";

    $ret = mysqli_query($con, $sql);

    $sql1 ="SELECT *FROM child WHERE child_serial='".$_GET['child_serial']."'";
    $ret1 =mysqli_query($con, $sql1);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['child_serial']." 해당 시리얼의 아동이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);
    $row_ch=mysqli_fetch_array($ret1);

    $child_serial=$row['child_serial'];
    $child_name=$row_ch['child_name'];
    $day=$row['day'];
    $start_time=$row['start_time'];
    $finish_time=$row['finish_time'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $child_serial=$_POST["child_serial"];
        $original_day=$_POST["original_day"];
        $original_start_time=$_POST["original_start_time"];

        $day=$_POST["day"];
        $start_time=$_POST["start_time"];
        $finish_time=$_POST["finish_time"];

        $sql = "UPDATE child_time
                SET start_time='".$start_time."', finish_time='".$finish_time."', day='".$day."'
                WHERE child_serial = '".$child_serial."' AND day='".$original_day."'
                AND start_time='".$original_start_time."'";

        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 수정됨.";
            header('Location: side_bar.php?tab=child');
            exit;
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        
        echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
        exit();
    }

?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
<title>아동 시간</title>
</HEAD>
<BODY>
    <h1>아동 시간 수정</h1>
    <FORM METHOD="post" ACTION="">
        <input type="hidden" name="original_day" value="<?php echo $row['day']; ?>">
        <input type="hidden" name="original_start_time" value="<?php echo $row['start_time']; ?>">

        아동 번호 : <INPUT TYPE = "text" NAME="child_serial" VALUE=<?php echo $child_serial?> readonly>
        <br>
        아동 이름 : <INPUT TYPE = "text" NAME="child_name" VALUE=<?php echo $child_name?> readonly>
        <br>
        요일 : <select name="day">
                <option value="월" <?php if ($day == '월') echo 'selected'; ?>>월요일</option>
                <option value="화" <?php if ($day == '화') echo 'selected'; ?>>화요일</option>
                <option value="수" <?php if ($day == '수') echo 'selected'; ?>>수요일</option>
                <option value="목" <?php if ($day == '목') echo 'selected'; ?>>목요일</option>
                <option value="금" <?php if ($day == '금') echo 'selected'; ?>>금요일</option>
                <option value="토" <?php if ($day == '토') echo 'selected'; ?>>토요일</option>
                <option value="일" <?php if ($day == '일') echo 'selected'; ?>>일요일</option>
            </select>
        <br>
        시작 시간 : <INPUT TYPE="time" NAME="start_time" VALUE=<?php echo $start_time?>>
        <br>
        종료 시간 : <INPUT TYPE="time" NAME="finish_time" VALUE=<?php echo $finish_time?>>
        <br><br>
        <input Type="submit" VALUE="시간 수정">
    </FORM>

</BODY>
</HTML>


