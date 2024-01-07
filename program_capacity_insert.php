<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM program WHERE program_serial='".$_POST['ch']."'";
    $ret = mysqli_query($con, $sql);
    
    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_POST['ch']." 해당 시리얼의 프로그램이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=program'> <--프로그램목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);
    $program_serial=$row['program_serial'];
    $program_name=$row["program_name"];

?>

<HTML>
    <HEAD>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>프로그램 역량</title>
    </HEAD>

    <BODY>
        <h1>프로그램 역량 추가</h1>
        <FORM METHOD="post" ACTION="program_capacity_insert_res.php">
            프로그램번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
            <br>
            프로그램이름 :  <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?> readonly>
            <br>
            역량 번호 : <INPUT TYPE = "number" NAME="capa_serial" min="1" value="1">
            <br>
            역량 레벨 : <INPUT TYPE="number" NAME="capa_level" min="1" max="5" value="1">
            <br><br>
            <INPUT TYPE="submit" VALUE="역량 추가">
        </FORM>
    </BODY>

</HTML>

