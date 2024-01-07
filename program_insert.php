<?php
// 데이터베이스 연결
$con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

// 프로그램 시리얼 번호의 최대값을 찾는 쿼리
$sql = "SELECT MAX(program_serial) AS max_serial FROM program";
$ret = mysqli_query($con, $sql);

// 쿼리 결과를 가져옵니다.
$row = mysqli_fetch_assoc($ret);
$max_serial = $row['max_serial'];

// 최대값에 1을 더합니다.
$default_serial = $max_serial + 1;
?>

<HTML>
    <HEAD>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>기존 일정 추가</title>
    </HEAD>

    <BODY>
        <h1>기존 일정 추가</h1>
        <FORM METHOD="post" ACTION="program_insert_result.php">
        <input type="hidden" name="center_serial" value="<?php echo $_POST['center_serial']; ?>">
            프로그램명:<INPUT TYPE = "text" NAME="program_name"><br>
            상세 설명:<INPUT TYPE = "text" NAME="program_detail"><br>
            비용:<INPUT TYPE = "number" NAME="program_money"><br>
            최소 인원:<INPUT TYPE = "number" NAME="min_people"><br>
            관련 프로그램 번호:<INPUT TYPE = "number" NAME="number" min="0" VALUE="<?php echo $default_serial; ?>"><br>
            장기 여부:<select name="long_yn">
           <option value="0">단기</option>
           <option value="1">장기</option>
            </select>
            <br><br>
            <INPUT TYPE="submit" VALUE="프로그램 입력">
        </FORM>
    </BODY>

</HTML>