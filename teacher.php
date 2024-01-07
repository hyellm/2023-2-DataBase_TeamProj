<?php
// SQL 연동
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['userid'])) {
    // 로그인 페이지로 리다이렉트
    header('Location: login.php');
    exit;
}
$teacher_serial = $_SESSION['userid'];

$con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

$sql_t ="SELECT *FROM teacher WHERE teacher_serial=$teacher_serial";
$ret_t = mysqli_query($con, $sql_t);
$row_t = mysqli_fetch_assoc($ret_t);
$center_serial = $row_t['center_serial'];

// 센터 테이블
$sql_center = "SELECT *FROM center WHERE center_serial=$center_serial";
$ret_center = mysqli_query($con, $sql_center);
$row_center = mysqli_fetch_assoc($ret_center);

// 선생님 테이블. 센터 시리얼 해당하는 선생님만.
$sql_teacher ="SELECT * from teacher WHERE center_serial =$center_serial AND position='선생님'";
$ret_teacher = mysqli_query($con, $sql_teacher);

?>

<html>
    <head>
        <title>센터 선생님 정보</title>
        <link rel = "stylesheet" type="text/css" href="site.css"> 
    </head>
    <body>
    <!-- 각 센터의 선생님 정보를 출력. -->
        <h3>📌 선생님 정보</h3>
        <form method="post" id="teacher-table-form" action="side_bar.php?tab=centerTeacherManage">
            <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>">
            <button type="submit" formaction="teacher_insert.php">추가</button>
            <button type="button" id="tupdate-button">수정</button>
            <button type="button" id="tdelete-button">삭제</button>
        <table>
            <tr>
                <th class="ratio"></th>
                <th>선생님 번호</th>
                <th>이름</th>
                <th>성별</th>
                <th>전화번호</th>
                <th>주소</th>
            </tr>
            <?php
                while($row_teacher = mysqli_fetch_assoc($ret_teacher)){
                    echo '<tr>';
                    echo '<td><input type="radio" name="teacher_serial" value="'.$row_teacher['teacher_serial'].'"></td>';
                    echo '<td>'.$row_teacher['teacher_serial'].'</td>';
                    echo '<td>'.$row_teacher['teacher_name'].'</td>';
                    echo '<td>'.$row_teacher['gender'].'</td>';
                    echo '<td>'.$row_teacher['teacher_tel'].'</td>';
                    echo '<td>'.$row_teacher['address'].'</td>';
                    echo '</tr>';
                }
            ?>
        </table>
    </form>

    <script>
            function getSelectedteacherSerial() {
                var radios = document.getElementsByName('teacher_serial');
                for (var i = 0, length = radios.length; i < length; i++) {
                    if (radios[i].checked) {
                        return radios[i].value;
                    }
                }
                return null;
            }
            document.getElementById('tupdate-button').addEventListener('click', function() {
                var teacherSerial = getSelectedteacherSerial();
                if (teacherSerial !== null) {
                    window.location.href = 'teacher_update.php?teacher_serial=' + teacherSerial + '&center_serial=' + <?php echo $center_serial; ?>;
                }
            })

            document.getElementById('tdelete-button').addEventListener('click', function() {
                var teacherSerial = getSelectedteacherSerial();
                if (teacherSerial !== null) {
                    window.location.href = 'teacher_delete.php?teacher_serial=' + teacherSerial + '&center_serial=' + <?php echo $center_serial; ?>;
                }
            });


        </script>
    </body>
</html>
