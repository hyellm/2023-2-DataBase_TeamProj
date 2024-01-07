<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && 
        isset($_POST['teacher_name'], $_POST['gender'], $_POST['teacher_tel'], $_POST['password'], $_POST['address'], $_POST['position']))
        {
        $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

        $center_serial=$_POST['center_serial'];
        $teacher_name=$_POST['teacher_name'];
        $gender=$_POST['gender'];
        $teacher_tel=$_POST['teacher_tel'];
        $password=$_POST['password'];
        $address=$_POST['address'];
        $position=$_POST['position'];

        $sql = "INSERT INTO teacher(center_serial, teacher_name, gender, teacher_tel, password, address, position)
                VALUES('".$center_serial."', '".$teacher_name."', '".$gender."', '".$teacher_tel."', '".$password."', '".$address."', '".$position."')";

        $ret=mysqli_query($con,$sql);

        if($ret) {
            echo "데이터가 성공적으로 입력됨.";
            header("Location: side_bar.php?tab=centerTeacherManage");
            exit();
        }
        else {
            echo "데이터 입력 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=centerTeacherManage'> <--선생님 목록으로</a? ";
        exit();
    }

    $center_serial=$_POST['center_serial'];
    
?>

<HTML>
    <HEAD>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <title>선생님 추가</title>
    </HEAD>
    <BODY>

    <h1>선생님 추가</h1>
    <FORM method="post" action="">
        <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>">

        이름 : <INPUT TYPE="text" NAME="teacher_name">
        <br>
        성별 : <select name="gender">
                <option value="남">남</option>
                <option value="여">여</option>
            </select><br>
        전화번호 : <INPUT TYPE="tel" NAME="teacher_tel">
        <br>
        비밀번호 : <INPUT TYPE="text" NAME="password">
        <br>
        주소 : <INPUT TYPE="text" NAME="address">
        <br>
        직급 : <INPUT TYPE="text" NAME="position">
        <BR><BR>
        <INPUT TYPE="submit" VALUE="추가">
    </FORM>
    </BODY>
</HTML>