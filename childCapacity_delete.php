<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM child_capacity
            WHERE child_serial='".$_GET['child_serial']."' AND capa_serial='".$_GET['capa_serial']."'";
    $ret = mysqli_query($con, $sql);

    $sql1 ="SELECT *FROM child WHERE child_serial='".$_GET['child_serial']."'";
    $ret1 =mysqli_query($con, $sql1);

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
    $row_ch=mysqli_fetch_array($ret1);

    $child_serial=$row['child_serial'];
    $child_name=$row_ch['child_name'];
    $capa_serial=$row['capa_serial'];
    $capa_level=$row['capa_level'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $child_serial=$_POST["child_serial"];
        $capa_serial=$_POST["capa_serial"];
    
        $sql="DELETE FROM child_capacity
                WHERE child_serial='".$child_serial."' AND capa_serial='".$capa_serial."'";
    
        $ret = mysqli_query($con, $sql);
        
        if($ret) {
            echo "데이터가 성공적으로 삭제됨.";
            header('Location: side_bar.php?tab=child');
            exit;
        }
        else {
            echo "데이터 삭제 실패 !"."<br>";
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
<title>아동 역량</title>
</HEAD>
<BODY>
    <h1>아동 역량 삭제</h1>
    <FORM METHOD="post" ACTION="">
        아동 번호 :<INPUT TYPE = "text" NAME="child_serial" VALUE=<?php echo $child_serial?> readonly>
        <br>
        아동 이름 : <INPUT TYPE = "text" NAME="child_name" VALUE=<?php echo $child_name?> readonly>
        <br>
        역량 번호 : <INPUT TYPE = "text" NAME="capa_serial" VALUE=<?php echo $capa_serial?> readonly>
        <br>
        역량 레벨 : <INPUT TYPE="number" NAME="capa_level" min="1" max="5" value=<?php echo $capa_level?> readonly>
        <br><br>
        <input Type="submit" VALUE="역량 삭제">
    </FORM>
</BODY>
</HTML>
