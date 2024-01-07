<?php
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
    $program_serial=$row["program_serial"];
    $name=$row["program_name"];
    $detail = $row["program_detail"];
    $inout = $row["inout_sep"];
    $money = $row["program_money"];
    $people = $row["min_people"];
    $number = $row["number"];
    $long_yn = $row["long_yn"];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];

        $sql="DELETE FROM program WHERE program_serial='".$program_serial."'";
    
        $ret = mysqli_query($con, $sql);
    
        if($ret) {
            echo "데이터가 성공적으로 삭제됨.";
            header('Location: side_bar.php?tab=program');
            exit;
        }
        else {
            echo "데이터 삭제 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);

        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a> ";
        exit();
    }
?>

<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
<title>프로그램 삭제</title>
</HEAD>
<BODY>
    <h1>프로그램 정보 삭제</h1>
    <FORM METHOD="post" ACTION="">
        
        프로그램 번호 :<INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램명 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $name?> readonly>
        <br>
        상세 설명 : <INPUT TYPE = "text" NAME="program_detail" VALUE=<?php echo $detail?> readonly>
        <br>
        외부 여부 : <INPUT TYPE = "text" NAME="inout_sep" VALUE=<?php echo $inout?> readonly>
        <br>
        관련 프로그램 번호 : <INPUT TYPE = "number" NAME="number" VALUE=<?php echo $number?> readonly>
        <br>
        장기여부:<INPUT TYPE = "text" NAME="long_yn" VALUE=<?php echo $long_yn?> readonly>
        <br><br>
        <INPUT TYPE="submit" VALUE="프로그램 삭제">
    </FORM>
</BODY>
</HTML>