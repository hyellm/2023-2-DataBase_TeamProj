<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM program WHERE program_serial='".$_GET['program_serial']."'";
    $ret = mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['program_serial']." 해당 시리얼의 프로그램이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--기존 일정 관리로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--기존 일정 관리로</a? ";
        exit();
    }
    
    $row=mysqli_fetch_array($ret);
    $program_serial=$row['program_serial'];
    $program_name = $row["program_name"];
    $detail = $row["program_detail"];
    $inout = $row["inout_sep"];
    $money = $row["program_money"];
    $people = $row["min_people"];
    $number = $row["number"];
    $long_yn = $row["long_yn"];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];
        $program_name = $_POST["program_name"];
        $detail = $_POST["program_detail"];
        $inout = $_POST["inout_sep"];
        $money=$_POST["program_money"];
        $people =$_POST["min_people"];
        $number = $_POST["number"];
        $long_yn = $_POST["long_yn"];
    
        $sql ="UPDATE program
                SET program_name='".$program_name."', program_detail='".$detail."',
                inout_sep='".$inout."', program_money=".(!empty($money) ? "'".$money."'" : "NULL").", min_people=".(!empty($people) ? "'".$people."'" : "NULL").",
                number='".$number."', long_yn='".$long_yn."' 
                WHERE program_serial = '".$program_serial."'";
    
    
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
        
        echo "<br> <a href='side_bar.php?tab=centerScheduleManage'> <--기존 일정 관리로</a> ";
        exit();
    }
?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
    <title>기존 일정 수정</title>
</HEAD>
<BODY>
    <h1>프로그램 정보 수정</h1>
    <FORM METHOD="post" ACTION="">
        프로그램 번호 :<INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램명 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $program_name?>>
        <br>
        상세설명 : <INPUT TYPE = "text" NAME="program_detail" VALUE=<?php echo $detail?>>
        <br>
        내외부 : <select name="inout_sep">
            <option value="0" <?php if($inout == "0") echo "selected"; ?>>기존</option>
            <option value="1" <?php if($inout == "1") echo "selected"; ?>>내부</option>
            <option value="2" <?php if($inout == "2") echo "selected"; ?>>외부</option>
            </select><br>   
        비용 : <INPUT TYPE = "number" NAME="program_money" VALUE=<?php echo $money?>>
        <br>
        최소인원 : <INPUT TYPE = "number" NAME="min_people" VALUE=<?php echo $people?>>
        <br>
        관련 프로그램 번호 : <INPUT TYPE = "number" NAME="number" min="0" VALUE=<?php echo $number?>>
        <br>
        장기여부:<select name="long_yn">
           <option value="0"<?php if($long_yn == "0") echo "selected"; ?>>단기</option>
           <option value="1"<?php if($long_yn == "1") echo "selected"; ?>>장기</option>
            </select>
        <br><br>
        <input Type="submit" VALUE="정보 수정">
    </FORM>

</BODY>
</HTML>
