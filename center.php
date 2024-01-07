<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['userid'])) {
        // 로그인 페이지로 리다이렉트
        header('Location: login.php');
        exit;
    }

    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $userid = $_SESSION['userid'];

    // 사용자 아이디에 해당하는 센터 시리얼 가져오기
    $sql_center_serial = "SELECT center_serial FROM teacher WHERE teacher_serial = '$userid'";
    $result = mysqli_query($con, $sql_center_serial);
    $row = mysqli_fetch_assoc($result);
    $center_serial = $row['center_serial'];

    $sql_center ="SELECT * FROM center WHERE center_serial = '$center_serial'";
    $ret_center = mysqli_query($con, $sql_center);

    $sql_center_time ="SELECT * FROM center_time WHERE center_serial = '$center_serial'";
    $ret_center_time = mysqli_query($con, $sql_center_time);
?>

<HTML>
    <HEAD>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel = "stylesheet" type="text/css" href="site.css"> 
    </HEAD>

    <BODY>
        <h3>📌 센터 정보</h3>
            <form method="post" id="center-table-form" action="side_bar.php?tab=centerManage">
                <button type="button" id="Cupdate-button">수정</button>
            <table>
                <tr>
                    <th>센터 번호</th>
                    <th>센터명</th>
                    <th>전화번호</th>
                    <th>주소</th>
                    <th>일일 최대 프로그램 수</th>
                </tr>

                <?php
                    while($row_center = mysqli_fetch_assoc($ret_center)){
                        echo '<tr>';
                        echo '<td>'.$row_center['center_serial'].'</td>';
                        echo '<td>'.$row_center['center_name'].'</td>';
                        echo '<td>'.$row_center['center_tel'].'</td>'; 
                        echo '<td>'.$row_center['center_address'].'</td>';
                        echo '<td>'.$row_center['oneday_program'].'</td>';
                        echo '</tr>';
                    }
                ?>
                
            </table>
            </form>

            <script>
                document.getElementById('Cupdate-button').addEventListener('click', function() {
                    window.location.href = 'center_update.php?center_serial=' + '<?php echo $center_serial; ?>';
                });
            </script>


        <br><br>

        <h3>📌 센터 시간</h3>
        <form method="post" id="centertime-table-form">
            <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>">
            <button type="submit" formaction="center_time_insert.php">추가</button>
            <button type="button" id="centertime-update-button">수정</button>
            <button type="button" id="centertime-delete-button">삭제</button>

            <table id="centertimeTable">
                <tr>
                    <th class="ratio"></th>
                    <th>요일</th>
                    <th>시작 시간</th>
                    <th>종료 시간</th>
                </tr>

                <?php
                    while($row_center_time = mysqli_fetch_assoc($ret_center_time)){
                        echo '<tr>';
                        echo '<td><input type="radio" name="day" value="'.$row_center_time['day'].'"></td>';
                        echo '<td>'.$row_center_time['day'].'</td>'; 
                        echo '<td>'.$row_center_time['start_time'].'</td>';
                        echo '<td>'.$row_center_time['finish_time'].'</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
        </form>

        <script>
            function getSelectedCenterTimeDay() {
                var radios = document.getElementsByName('day');
                for (var i = 0, length = radios.length; i < length; i++) {
                    if (radios[i].checked) {
                        return radios[i].value;
                    }
                }
                return null;
            }

            document.getElementById('centertime-update-button').addEventListener('click', function() {
                var centerTimeDay = getSelectedCenterTimeDay();
                if (centerTimeDay !== null) {
                    window.location.href = 'center_time_update.php?center_serial=' + '<?php echo $center_serial; ?>' + '&day=' + centerTimeDay;
                }
            });

            document.getElementById('centertime-delete-button').addEventListener('click', function() {
                var centerTimeDay = getSelectedCenterTimeDay();
                if (centerTimeDay !== null) {
                    window.location.href = 'center_time_delete.php?center_serial=' + '<?php echo $center_serial; ?>' + '&day=' + centerTimeDay;
                }
            });
        </script>
    </BODY>
</HTML>
