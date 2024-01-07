<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql="SELECT *FROM child WHERE child_serial='".$_POST['ch']."'";
    $ret=mysqli_query($con, $sql);

    if($ret) {
        $count=mysqli_num_rows($ret);
        if($count==0){
            echo $_POST['ch']." 해당 시리얼의 아동이 없음". "<br>";
            echo "<br> <a href='child.php'> <--아동 목록으로</a? ";
            exit();
        }
    }
    else {
        echo "데이터 조회 실패"."<br>";
        echo "실패 원인 :".mysqli_error($con);
        echo "<br> <a href='child.php'> <--아동 목록으로</a? ";
        exit();
    }

    $row=mysqli_fetch_array($ret);
    $child_serial=$row['child_serial'];
    $child_name=$row['child_name'];
?>

<HTML>
    <HEAD>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <title>아동 흥미도</title>
    </HEAD>
    <BODY>

    <h1>아동 흥미도 추가</h1>
    <FORM method="post" action="childInterest_insert_res.php">
        아동 번호 : <INPUT TYPE = "text" NAME="child_serial" VALUE=<?php echo $child_serial?> readonly>
        <br>
        아동 이름 : <INPUT TYPE = "text" NAME="child_name" VALUE=<?php echo $child_name?> readonly>
        <br>
        카테고리 번호 : <INPUT TYPE = "number" NAME="cate_serial"  min="1" value="1">
        <br>
        흥미도 레벨 : <INPUT TYPE="number" NAME="inter_level" min="1" max="5" value="1"><br>
        <BR><BR>
        <INPUT TYPE="submit" VALUE="흥미도 추가">
    </FORM>
    </BODY>
</HTML>