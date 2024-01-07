<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM program WHERE program_serial='".$_POST['ch']."'";
    $ret = mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_POST['ch']." 해당 시리얼의 프로그램이 없음". "<br>";
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
    $program_serial=$row['program_serial'];
    $program_name=$row["program_name"];

?>

<HTML>
    <HEAD>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>프로그램 시간</title>
    </HEAD>

    <BODY>
        <h1>프로그램 시간 추가</h1>
        <FORM METHOD="post" ACTION="program_time_insert_res.php">
            프로그램 번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
            <br>
            프로그램 이름 :  <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?> readonly>
            <br>
            요일:<select name="day">
                        <option value="월">월요일</option>
                        <option value="화">화요일</option>
                        <option value="수">수요일</option>
                        <option value="목">목요일</option>
                        <option value="금">금요일</option>
                        <option value="토">토요일</option>
                        <option value="일">일요일</option>
                    </select><br>
            시작 날짜:<INPUT TYPE = "date" NAME="start_date">
            <br>
            종료 날짜:<INPUT TYPE = "date" NAME="finish_date">
            <br>
            시작 시간:<INPUT TYPE = "time" NAME="start_time">
            <br>
            종료 시간:<INPUT TYPE = "time" NAME="finish_time">
            <br><br>
            <INPUT TYPE="submit" VALUE="시간 추가">
        </FORM>
    </BODY>

</HTML>
