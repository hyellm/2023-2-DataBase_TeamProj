<?php
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");
    $sql ="SELECT *FROM program_category 
            WHERE program_serial='".$_GET['program_serial']."' AND cate_serial='".$_GET['cate_serial']."'";
    $ret = mysqli_query($con, $sql);
    
    $sql1 ="SELECT *FROM program WHERE program_serial='".$_GET['program_serial']."'";
    $ret1 = mysqli_query($con, $sql1);

    if($ret) {
      $count=mysqli_num_rows($ret);
      if($count==0){
          echo $_GET['program_serial']." 해당 시리얼의 프로그램이 없음". "<br>";
          echo "<br> <a href='side_bar.php?tab=program'> <--프로그램목록으로</a? ";
          exit();
      }
  }
  else {
      echo "데이터 조회 실패"."<br>";
      echo "실패 원인 :".mysqli_error($con);
      echo "<br> <a href='side_bar.php?tab=program'> <--프로그램목록으로</a? ";
      exit();
  }

    $row=mysqli_fetch_array($ret);
    $row_pr=mysqli_fetch_array($ret1);

    $name=$row_pr['program_name'];
    $program_serial=$row['program_serial'];
    $cate_serial=$row['cate_serial'];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $program_serial=$_POST["program_serial"];
        $cate_serial=$_POST["cate_serial"];
    
        $sql="DELETE FROM program_category 
                WHERE program_serial='".$program_serial."' AND cate_serial='".$cate_serial."'";
    
        $ret = mysqli_query($con, $sql);
    
        if($ret) {
            echo "데이터가 성공적으로 삭제됨.";
            header('Location: side_bar.php?tab=program');
        }
        else {
            echo "데이터 삭제 실패 !"."<br>";
            echo "실패 원인 :".mysqli_error($con);
        }
        mysqli_close($con);
        
        echo "<br> <a href='side_bar.php?tab=program'> <--프로그램 목록으로</a> ";
        exit();
    }

?>

<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
<title>프로그램 카테고리</title>
</HEAD>
<BODY>
    <h1>프로그램 카테고리 삭제</h1>

    <FORM METHOD="post" ACTION="">
        프로그램 번호 : <INPUT TYPE = "text" NAME="program_serial" VALUE=<?php echo $program_serial?> readonly>
        <br>
        프로그램 이름 : <INPUT TYPE = "text" NAME="program_name" VALUE=<?php echo $name?> readonly>
        <br>   
        카테고리 번호 :<INPUT TYPE = "text" NAME="cate_serial" VALUE=<?php echo $cate_serial?> readonly>
        <br><br>
        <INPUT TYPE="submit" VALUE="카테고리 삭제">
    </FORM>
</BODY>
</HTML>
