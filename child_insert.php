<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['child_name'], $_POST['child_bday'], $_POST['gender'], $_POST['school'], $_POST['grade'], $_POST['child_tel'], $_POST['center_serial']) && strtotime($_POST['child_bday'])) {
        $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

        $child_name=$_POST['child_name'];
        $child_bday=$_POST['child_bday'];
        $gender=$_POST['gender'];
        $school = isset($_POST['school']) && $_POST['school'] !== '' ? $_POST['school'] : NULL;
        $grade = isset($_POST['grade']) && $_POST['grade'] !== '' ? $_POST['grade'] : NULL;
        $child_tel = isset($_POST['child_tel']) && $_POST['child_tel'] !== '' ? $_POST['child_tel'] : NULL;
        $center_serial = $_POST['center_serial'];
        
        $sql = "INSERT INTO child(center_serial, child_name, child_bday, gender, school, grade, child_tel)
                VALUES('".$center_serial."', '".$child_name."', '".$child_bday."', '".$gender."', "
                .($school !== NULL ? "'".$school."'" : "NULL").", "
                .($grade !== NULL ? "'".$grade."'" : "NULL").", "
                .($child_tel !== NULL ? "'".$child_tel."'" : "NULL").")";
        
        $ret=mysqli_query($con,$sql);

        if($ret) {
            echo "데이터가 성공적으로 입력됨.";
            header('Location: side_bar.php?tab=child');
            exit;
        }
        else {
            echo "데이터 입력 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=child'> <--초기화면</a? ";
        exit();
    }
?>

<HTML>
<HEAD>
    <META http-equiv="content-type" content="text/html; charset=utf-8">
    <title>아동 추가</title>
</HEAD>
<BODY>
    <h1>아동 정보 추가</h1>
    <FORM method="post" action="">
    <input type="hidden" name="center_serial" value="<?php echo $_POST['center_serial']; ?>">
        아동 이름 : <INPUT TYPE="text" NAME="child_name"> <br>
        생년월일 : <INPUT TYPE="date" NAME="child_bday" value="2010-01-01"> <br>
        성별 : <select name="gender">
                <option value="남">남</option>
                <option value="여">여</option>
            </select><br>
        학교 : <INPUT TYPE="text" NAME="school"><br>
        학년 : <INPUT TYPE="number" NAME="grade" min="1" max="6" value="1"><br>
        전화번호 : <INPUT TYPE="tel" NAME="child_tel">
        <BR><BR>
        <INPUT TYPE="submit" VALUE="아동 입력">
    </FORM>
</BODY>
</HTML>