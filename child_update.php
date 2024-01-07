<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM child WHERE child_serial='".$_GET['child_serial']."'";
    $ret = mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_GET['child_serial']." 해당 시리얼의 아동이 없음". "<br>";
            echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);
    $child_serial=$row['child_serial'];
    $child_name=$row['child_name'];
    $child_bday=$row['child_bday'];
    $gender=$row['gender'];
    $school=$row['school'];
    $grade=$row['grade'];
    $child_tel=$row['child_tel'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $child_serial=$_POST["child_serial"];
        $child_name=$_POST["child_name"];
        $child_bday=$_POST['child_bday'];
        $gender=$_POST['gender'];
        $school=isset($_POST['school']) && $_POST['school'] !== '' ? $_POST['school'] : NULL;
        $grade=isset($_POST['grade']) && $_POST['grade'] !== '' ? $_POST['grade'] : NULL;
        $child_tel=isset($_POST['child_tel']) && $_POST['child_tel'] !== '' ? $_POST['child_tel'] : NULL;

        $sql = "UPDATE child
                SET child_name='".$child_name."', child_bday='".$child_bday."',
                gender='".$gender."', school='".$school."', grade=".(!empty($grade) ? "'".$grade."'" : "NULL").", child_tel='".$child_tel."'
                WHERE child_serial = '".$child_serial."'";

        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 수정됨.";
            header('Location: side_bar.php?tab=child');
            exit;
        }
        else {
            echo "데이터 수정 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        echo "<br> <a href='side_bar.php?tab=child'> <--아동 목록으로</a? ";
        exit();
    }
?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
<title>아동 수정</title>
</HEAD>
<BODY>
    <h1>아동 정보 수정</h1>
    <FORM METHOD="post" ACTION="">
        아동 번호 :<INPUT TYPE = "text" NAME="child_serial" VALUE=<?php echo $child_serial?> readonly>
        <br>
        아동 이름 : <INPUT TYPE = "text" NAME="child_name" VALUE=<?php echo $child_name?>>
        <br>
        생년월일 : <INPUT TYPE = "date" NAME="child_bday" VALUE=<?php echo $child_bday?>>
        <br>
        성별 : <select name="gender">
            <option value="남" <?php if($gender == "남") echo "selected"; ?>>남</option>
            <option value="여" <?php if($gender == "여") echo "selected"; ?>>여</option>
            </select><br>
        학교 : <INPUT TYPE = "text" NAME="school" VALUE=<?php echo $school?>>
        <br>
        학년 : <INPUT TYPE="number" NAME="grade" VALUE=<?php echo $grade?>>
        <br>
        전화번호 : <INPUT TYPE = "text" NAME="child_tel" VALUE=<?php echo $child_tel?>>
        <br><br>
        <input Type="submit" VALUE="정보 수정">
    </FORM>

</BODY>
</HTML>

