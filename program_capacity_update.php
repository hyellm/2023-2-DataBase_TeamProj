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
    $program_name = $row["program_name"];

    $sql1 ="SELECT *FROM program_capacity 
            WHERE program_serial='".$_GET['program_serial']."' AND capa_serial='".$_GET['capa_serial']."'";
    $ret1 = mysqli_query($con, $sql1);
    $row=mysqli_fetch_array($ret1);
    $program_serial=$row['program_serial'];
    $capa_serial=$row['capa_serial'];
    $capa_level = $row["capa_level"];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];
        $capa_serial=$_POST['capa_serial'];
        $capa_level = $_POST["capa_level"];
       
    
        $sql = "UPDATE program_capacity
                SET  capa_level='".$capa_level."'
                WHERE program_serial = '".$program_serial."' AND capa_serial='".$capa_serial."'";
        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 추가됨.";
            header('Location: side_bar.php?tab=program');
            exit;
        }
        else {
            echo "데이터 추가 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a? ";
        exit();
    }
?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
    <title>프로그램 역량</title>
</HEAD>
<BODY>
    <h1>프로그램 역량 수정</h1>
    <FORM METHOD="post" ACTION="">
        프로그램 번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램 이름 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?> readonly>
        <br>
        역량 번호:<INPUT TYPE = "text" NAME="capa_serial" VALUE=<?php echo $capa_serial?> readonly>
        <br>
        역량 레벨 : <INPUT TYPE="number" NAME="capa_level" min="1" max="5" value=<?php echo $capa_level?>><br>
        <br><br>
        <input Type="submit" VALUE="역량 수정">
    </FORM>

</BODY>
</HTML>


