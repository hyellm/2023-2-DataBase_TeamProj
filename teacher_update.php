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
        $center_serial=$_POST['cs'];
        $teacher_serial=$_POST['teacher_serial'];

        $teacher_name=$_POST['teacher_name'];
        $gender=$_POST['gender'];
        $teacher_tel=$_POST['teacher_tel'];
        $password=$_POST['password'];
        $address=$_POST['address'];
        $position=$_POST['position'];

        $sql = "UPDATE teacher
                SET teacher_name='".$teacher_name."', gender='".$gender."', teacher_tel='".$teacher_tel."',
                password='".$password."', address='".$address."', position='".$position."'
                WHERE teacher_serial = '".$teacher_serial."' AND center_serial='".$center_serial."'";

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
<title>선생님 수정</title>
</HEAD>
<BODY>
    <h1>선생님 정보 수정</h1>
    <FORM METHOD="post" ACTION="">
        <input type="hidden" name="cs" value="<?php echo $center_serial; ?>">

        선생님 번호 :<INPUT TYPE = "text" NAME="teacher_serial" VALUE=<?php echo $teacher_serial?> readonly>
        <br>
        이름 : <INPUT TYPE="text" NAME="teacher_name" VALUE=<?php echo $teacher_name?>>
        <br>
        성별 : <select name="gender">
                <option value="남" <?php if($gender == "남") echo "selected"; ?>>남</option>
                <option value="여" <?php if($gender == "여") echo "selected"; ?>>여</option>
            </select><br>
        전화번호 : <INPUT TYPE="tel" NAME="teacher_tel" VALUE=<?php echo $teacher_tel?>>
        <br>
        비밀번호 : <INPUT TYPE="text" NAME="password" VALUE=<?php echo $password?>>
        <br>
        주소 : <INPUT TYPE="text" NAME="address" VALUE=<?php echo $address?>>
        <br>
        직급 : <INPUT TYPE="text" NAME="position" VALUE=<?php echo $position?>>
        <BR><BR>
        <input Type="submit" VALUE="수정">
    </FORM>

</BODY>
</HTML>


