<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql_center ="SELECT *FROM center WHERE center_serial='".$_GET['center_serial']."'";
    $ret_center = mysqli_query($con, $sql_center);

    if($ret_center) {
        $count=mysqli_num_rows($ret_center);
        if($count==0){
            echo $_GET['center_serial']." 해당 시리얼의 센터가 없음". "<br>";
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

    $row=mysqli_fetch_array($ret_center);
    $center_serial=$row['center_serial'];
    $center_name=$row['center_name'];
    $center_tel=$row['center_tel'];
    $center_address=$row['center_address'];
    $oneday_program=$row['oneday_program'];
    // $grade=$row['grade'];
    // $child_tel=$row['child_tel'];\
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $center_serial=$_POST["center_serial"];
        $center_name=$_POST["center_name"];
        $center_tel=$_POST['center_tel'];
        $center_address=$_POST['center_address'];
        $oneday_program=$_POST['oneday_program'];
    
        $sql_center = "UPDATE center
            SET center_name='".$center_name."', center_tel='".$center_tel."',
            center_address='".$center_address."', oneday_program='".$oneday_program."'
            WHERE center_serial = '".$center_serial."'";
        $ret_center = mysqli_query($con, $sql_center);
        
        if($ret_center) {
            echo "데이터가 성공적으로 수정됨.";
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
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
    <h1>센터 정보 수정</h1>
    <FORM METHOD="post" ACTION="">
        센터 번호 : <INPUT TYPE = "text" NAME="center_serial" VALUE=<?php echo $center_serial?> readonly>
        <br>
        센터명 : <INPUT TYPE = "text" NAME="center_name" VALUE=<?php echo $center_name?>>
        <br>
        전화번호 : <INPUT TYPE = "text" NAME="center_tel" VALUE=<?php echo $center_tel?>>
        <br>
        <!-- 주소 띄어쓰기 인식이 안 됨 -->
        주소 : <INPUT TYPE = "text" NAME="center_address" VALUE="<?php echo $center_address?>">
        <br>
        일일 최대 프로그램 수 : <INPUT TYPE = "text" NAME="oneday_program" VALUE=<?php echo $oneday_program?>>
        <br>
        
        <br><br>
        <input Type="submit" VALUE="정보 수정">
    </FORM>

</BODY>
</HTML>