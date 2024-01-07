<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM teacher
            WHERE teacher_serial='".$_GET['teacher_serial']."' AND center_serial='".$_GET['center_serial']."'";

    $ret = mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['child_serial']." 해당 시리얼의 선생님이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=centerTeacherManage'> <--선생님 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=centerTeacherManage'> <--선생님 목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);

    $center_serial=$_GET['center_serial'];
    $teacher_serial=$row['teacher_serial'];
    $teacher_name=$row['teacher_name'];
    $gender=$row['gender'];
    $teacher_tel=$row['teacher_tel'];
    $password=$row['password'];
    $address=$row['address'];
    $position=$row['position'];
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $teacher_serial=$_POST["teacher_serial"];
        $center_serial=$_POST["center_serial"];

        $sql="DELETE FROM teacher WHERE teacher_serial='".$teacher_serial."' AND center_serial='".$center_serial."'";

        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 수정됨.";
            header("Location: side_bar.php?tab=centerTeacherManage");
            exit();
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=centerTeacherManage'> <--선생님 목록으로</a? ";
        exit();
    }

?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
<title>선생님 삭제</title>
</HEAD>
<BODY>
    <h1>선생님 삭제</h1>
    <FORM METHOD="post" ACTION="">
        <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>"><br>
        선생님 번호 :<INPUT TYPE = "text" NAME="teacher_serial" VALUE=<?php echo $teacher_serial?> readonly>
        <br>
        이름 : <INPUT TYPE="text" NAME="teacher_name" VALUE=<?php echo $teacher_name?> readonly> 
        <br>
        성별 : <INPUT TYPE="text" NAME="gender" VALUE=<?php echo $gender?> readonly> 
        <br>
        전화번호 : <INPUT TYPE="tel" NAME="teacher_tel" VALUE=<?php echo $teacher_tel?> readonly>
        <br>
        비밀번호 : <INPUT TYPE="text" NAME="password" VALUE=<?php echo $password?> readonly>
        <br>
        주소 : <INPUT TYPE="text" NAME="address" VALUE=<?php echo $address?> readonly>
        <br>
        직급 : <INPUT TYPE="text" NAME="position" VALUE=<?php echo $position?> readonly>
        <BR><BR>
        <input Type="submit" VALUE="삭제">
    </FORM>

</BODY>
</HTML>


