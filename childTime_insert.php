<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql="SELECT *FROM child WHERE child_serial='".$_POST['ch']."'";
    $ret=mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_POST['ch']." 해당 시리얼의 아동이 없음". "<br>";
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
    $child_serial=$row['child_serial'];
    $child_name=$row['child_name'];
?>

<HTML>
    <HEAD>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <title>아동 시간</title>
    </HEAD>
    <BODY>
    <h1>아동 시간 추가</h1>
    <FORM method="post" action="childTime_insert_res.php">
        아동 번호 : <INPUT TYPE = "text" NAME="child_serial" VALUE=<?php echo $child_serial?> readonly>
        <br>
        아동 이름 : <INPUT TYPE = "text" NAME="child_name" VALUE=<?php echo $child_name?> readonly>
        <br>
        요일 : <select NAME="day">
                <option value="월">월요일</option>
                <option value="화">화요일</option>
                <option value="수">수요일</option>
                <option value="목">목요일</option>
                <option value="금">금요일</option>
                <option value="토">토요일</option>
                <option value="일">일요일</option>
            </select>
        <br>
        시작 시간 : <INPUT TYPE="time" NAME="start_time">
        <br>
        종료 시간 : <INPUT TYPE="time" NAME="finish_time">
        <BR><BR>
        <INPUT TYPE="submit" VALUE="시간 추가">
    </FORM>
    </BODY>
</HTML>