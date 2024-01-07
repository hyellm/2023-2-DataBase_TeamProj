<?php
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
    
    /* 트리거 백업 위한 테이블 생성 */
    $sql_backup_capacity = "CREATE TABLE IF NOT EXISTS backup_capacity(
                            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            child_serial INT NOT NULL,
                            capa_serial INT NOT NULL,
                            capa_level INT NOT NULL,
                            modType CHAR(2),
                            modDate DATE,
                            modUser VARCHAR(20))";
    mysqli_query($con, $sql_backup_capacity);

    $sql_backup_interest = "CREATE TABLE IF NOT EXISTS backup_interest(
                            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            child_serial INT NOT NULL,
                            cate_serial INT NOT NULL,
                            inter_level INT NOT NULL,
                            modType CHAR(2),
                            modDate DATE,
                            modUser VARCHAR(20))";
    mysqli_query($con, $sql_backup_interest);


    /* INSERT 트리거 */
    /* child_capacity INSERT 트리거 */
    $sql_drop_capacity_InsertTrg = "DROP TRIGGER IF EXISTS capacity_InsertTrg";
    if (!mysqli_query($con, $sql_drop_capacity_InsertTrg)) {
        echo "child_capacity INSERT 트리거 삭제 실패: " . mysqli_error($con);
    }

    $sql_capacity_InsertTrg = " CREATE TRIGGER capacity_InsertTrg
                                AFTER INSERT
                                ON child_capacity
                                FOR EACH ROW
                                BEGIN
                                    INSERT INTO backup_capacity(child_serial, capa_serial, capa_level, modType, modDate, modUser)
                                    VALUES(NEW.child_serial, NEW.capa_serial, NEW.capa_level,
                                            '추가', CURDATE(), CURRENT_USER());
                                END";
    if (!mysqli_query($con, $sql_capacity_InsertTrg)) {
        echo "child_capacity INSERT 트리거 생성 실패: " . mysqli_error($con);
    }


    /* child_interest INSERT 트리거 */
    $sql_drop_interest_InsertTrg = "DROP TRIGGER IF EXISTS interest_InsertTrg";
    if (!mysqli_query($con, $sql_drop_interest_InsertTrg)) {
        echo "child_interest INSERT 트리거 삭제 실패: " . mysqli_error($con);
    }
    
    $sql_interest_InsertTrg = "CREATE TRIGGER interest_InsertTrg
                                AFTER INSERT
                                ON child_interest
                                FOR EACH ROW
                                BEGIN
                                    INSERT INTO backup_interest(child_serial, cate_serial, inter_level, modType, modDate, modUser)
                                    VALUES(NEW.child_serial, NEW.cate_serial, NEW.inter_level,
                                            '추가', CURDATE(), CURRENT_USER());
                                END";
    if (!mysqli_query($con, $sql_interest_InsertTrg)) {
        echo "child_interest INSERT 트리거 생성 실패: " . mysqli_error($con);
    }
    

    /* UPDATE 트리거 */
    /* child_capacity UPDATE 트리거 */
    $sql_drop_capacity_UpdateTrg = "DROP TRIGGER IF EXISTS capacity_UpdateTrg";
    if (!mysqli_query($con, $sql_drop_capacity_UpdateTrg)) {
        echo "child_capacity UPDATE 트리거 삭제 실패: " . mysqli_error($con);
    }

    $sql_capacity_UpdateTrg = "CREATE TRIGGER capacity_UpdateTrg
                                AFTER UPDATE
                                ON child_capacity
                                FOR EACH ROW
                                BEGIN
                                    INSERT INTO backup_capacity(child_serial, capa_serial, capa_level, modType, modDate, modUser)
                                    VALUES(OLD.child_serial, OLD.capa_serial, OLD.capa_level,
                                            '수정', CURDATE(), CURRENT_USER());
                                END;";
    if (!mysqli_query($con, $sql_capacity_UpdateTrg)) {
        echo "child_capacity UPDATE 트리거 생성 실패: " . mysqli_error($con);
    }

    /* child_interest UPDATE 트리거 */
    $sql_drop_interest_UpdateTrg = "DROP TRIGGER IF EXISTS interest_UpdateTrg";
    if (!mysqli_query($con, $sql_drop_interest_UpdateTrg)) {
        echo "child_interest UPDATE 트리거 삭제 실패: " . mysqli_error($con);
    }
    $sql_interest_UpdateTrg = "CREATE TRIGGER interest_UpdateTrg
                                AFTER UPDATE
                                ON child_interest
                                FOR EACH ROW
                                BEGIN
                                    INSERT INTO backup_interest(child_serial, cate_serial, inter_level, modType, modDate, modUser)
                                    VALUES(OLD.child_serial, OLD.cate_serial, OLD.inter_level,
                                            '수정', CURDATE(), CURRENT_USER());
                                END;";
    if (!mysqli_query($con, $sql_interest_UpdateTrg)) {
        echo "child_interest UPDATE 트리거 생성 실패: " . mysqli_error($con);
    }

    ///////////////////////////////////////

    $child_serial = isset($_POST['child_serial']) ? $_POST['child_serial'] : null;

    $sql_t ="SELECT *FROM teacher WHERE teacher_serial = $teacher_serial";
    $ret_t = mysqli_query($con, $sql_t);
    if ($ret_t === false) {
        die("쿼리 실행 실패: " . mysqli_error($con));
    }
    $row_t = mysqli_fetch_assoc($ret_t);
    $center_serial = $row_t['center_serial'];

    $sql ="SELECT *FROM child WHERE center_serial = $center_serial";
    $ret = mysqli_query($con, $sql);

    $sql1 = "SELECT * FROM child_capacity c INNER JOIN child ch ON c.child_serial = ch.child_serial WHERE ch.center_serial = $center_serial";
    if ($child_serial !== null) {
        $sql1 .= " AND c.child_serial = $child_serial";
    }
    $ret1 = mysqli_query($con, $sql1);

    $sql2 = "SELECT * FROM child_interest c INNER JOIN child ch ON c.child_serial = ch.child_serial WHERE ch.center_serial = $center_serial";
    if ($child_serial !== null) {
        $sql2 .= " AND c.child_serial = $child_serial";
    }
    $ret2 = mysqli_query($con, $sql2);

    $sql3 = "SELECT * FROM child_time c INNER JOIN child ch ON c.child_serial = ch.child_serial WHERE ch.center_serial = $center_serial";
    if ($child_serial !== null) {
        $sql3 .= " AND c.child_serial = $child_serial";
    }
    $ret3 = mysqli_query($con, $sql3);


    

?>

<HTML>
    <HEAD>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <title> 아동 정보 </title>
        <link rel = "stylesheet" type="text/css" href="site.css"> 
    </HEAD>

    <BODY>

    <h3>📌 아동 정보</h3>
    <form method="post" id="child-table-form" action="side_bar.php?tab=child">
        <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>">
        <button type="submit" formaction="child_insert.php">추가</button>
        <button type="button" id="update-button">수정</button>
        <button type="button" id="delete-button">삭제</button>
        <input type="submit" value="조회">
    <table>
        <tr>
            <th class="ratio"></th>
            <th>아동 번호</th>
            <th>이름</th>
            <th>생년월일</th>
            <th>성별</th>
            <th>학교</th>
            <th>학년</th>
            <th>전화번호</th>
        </tr>

        <?php
            while($row = mysqli_fetch_assoc($ret)){
                echo '<tr>';
                echo '<td><input type="radio" name="child_serial" value="'.$row['child_serial'].'"></td>';
                echo '<td>'.$row['child_serial'].'</td>';
                echo '<td>'.$row['child_name'].'</td>'; 
                echo '<td>'.$row['child_bday'].'</td>';
                echo '<td>'.$row['gender'].'</td>';
                echo '<td>'.$row['school'].'</td>';
                echo '<td>'.$row['grade'].'</td>';
                echo '<td>'.$row['child_tel'].'</td>';
                echo '</tr>';
            }
        ?>
        
    </table>
    </form>

    
    <script>
        function getSelectedChildSerial() {
            var radios = document.getElementsByName('child_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('update-button').addEventListener('click', function() {
            var childSerial = getSelectedChildSerial();
            if (childSerial !== null) {
                window.location.href = 'child_update.php?child_serial=' + childSerial;
            }
        });

        document.getElementById('delete-button').addEventListener('click', function() {
            var childSerial = getSelectedChildSerial();
            if (childSerial !== null) {
                window.location.href = 'child_delete.php?child_serial=' + childSerial;
            }
        });
    </script>

    <br><br><br>

    <h3>📌 아동 역량</h3>
    <form method="post" id="capacity-table-form">
        <input type="hidden" name="ch" value="<?php echo $child_serial; ?>">
        <button type="submit" formaction="childCapacity_insert.php">추가</button>
        <button type="button" id="capacity-update-button">수정</button>
        <button type="button" id="capacity-delete-button">삭제</button>

        <table id="capacityTable">
            <tr>
                <th class="ratio"></th>
                <th>아동 번호</th>
                <th>역량 번호</th>
                <th>역량 레벨</th>
                <th>마지막 수정</th>
            </tr>

            <?php
                while($row1 = mysqli_fetch_assoc($ret1)){
                    echo '<tr>';
                    echo '<td><input type="radio" name="capa_serial" value="'.$row1['capa_serial'].'"></td>';
                    echo '<td>'.$row1['child_serial'].'</td>'; 
                    echo '<td>'.$row1['capa_serial'].'</td>'; 
                    echo '<td>'.$row1['capa_level'].'</td>';

                    if($child_serial!=null){
                        $sql_backup_capacity = "SELECT modDate FROM backup_capacity
                                                WHERE id =
                                                (SELECT MAX(id) FROM backup_capacity
                                                    WHERE child_serial = $child_serial AND capa_serial = ".$row1['capa_serial'].")";
                        $ret_backup_capacity = mysqli_query($con, $sql_backup_capacity);
                        if ($row_backup_capacity = mysqli_fetch_assoc($ret_backup_capacity)) {
                            echo '<td>'.$row_backup_capacity['modDate'].'</td>';
                        } else {
                            echo '<td></td>';
                        }
                     } else {
                        echo '<td></td>';
                     }

                    echo '</tr>';
                }
            ?>
        </table>
    </form>

    <script>
        function getSelectedCapacitySerial() {
            var radios = document.getElementsByName('capa_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('capacity-update-button').addEventListener('click', function() {
            var capacitySerial = getSelectedCapacitySerial();
            if (capacitySerial !== null) {
                window.location.href = 'childCapacity_update.php?child_serial=' + '<?php echo $child_serial; ?>' + '&capa_serial=' + capacitySerial;
            }
        });

        document.getElementById('capacity-delete-button').addEventListener('click', function() {
            var capacitySerial = getSelectedCapacitySerial();
            if (capacitySerial !== null) {
                window.location.href = 'childCapacity_delete.php?child_serial=' + '<?php echo $child_serial; ?>' + '&capa_serial=' + capacitySerial;
            }
        });
    </script>

    

    <br><br><br>
    
    <h3>📌 아동 흥미도</h3>
    <form method="post" id="interest-table-form">
        <input type="hidden" name="ch" value="<?php echo $child_serial; ?>">
        <button type="submit" formaction="childInterest_insert.php">추가</button>
        <button type="button" id="interest-update-button">수정</button>
        <button type="button" id="interest-delete-button">삭제</button>

        <table id="interestTable">
            <tr>
                <th class="ratio"></th>
                <th>아동 번호</th>
                <th>카테고리 번호</th>
                <th>흥미도 레벨</th>
                <th>마지막 수정</th>
            </tr>

            <?php
                while($row2 = mysqli_fetch_assoc($ret2)){
                    echo '<tr>';
                    echo '<td><input type="radio" name="cate_serial" value="'.$row2['cate_serial'].'"></td>';
                    echo '<td>'.$row2['child_serial'].'</td>'; 
                    echo '<td>'.$row2['cate_serial'].'</td>'; 
                    echo '<td>'.$row2['inter_level'].'</td>';

                    if($child_serial!=null){
                        $sql_backup_interest = "SELECT modDate FROM backup_interest
                                                WHERE id =
                                                (SELECT MAX(id) FROM backup_interest
                                                    WHERE child_serial = $child_serial AND cate_serial = ".$row2['cate_serial'].")";
                        $ret_backup_interest = mysqli_query($con, $sql_backup_interest);
                        if ($row_backup_interest = mysqli_fetch_assoc($ret_backup_interest)) {
                            echo '<td>'.$row_backup_interest['modDate'].'</td>';
                        } else {
                            echo '<td></td>';
                        }
                     } else {
                        echo '<td></td>';
                     }

                    echo '</tr>';
                }
            ?>
        </table>
    </form>

    <script>
        function getSelectedInterestSerial() {
            var radios = document.getElementsByName('cate_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('interest-update-button').addEventListener('click', function() {
            var interestSerial = getSelectedInterestSerial();
            if (interestSerial !== null) {
                window.location.href = 'childInterest_update.php?child_serial=' + '<?php echo $child_serial; ?>' + '&cate_serial=' + interestSerial;
            }
        });

        document.getElementById('interest-delete-button').addEventListener('click', function() {
            var interestSerial = getSelectedInterestSerial();
            if (interestSerial !== null) {
                window.location.href = 'childInterest_delete.php?child_serial=' + '<?php echo $child_serial; ?>' + '&cate_serial=' + interestSerial;
            }
        });
    </script>

    <br><br><br>
    
    <h3>📌 아동 시간</h3>
    <form method="post" id="time-table-form">
        <input type="hidden" name="ch" value="<?php echo $child_serial; ?>">
        <button type="submit" formaction="childTime_insert.php">추가</button>
        <button type="button" id="time-update-button">수정</button>
        <button type="button" id="time-delete-button">삭제</button>

        <table id="timeTable">
            <tr>
                <th class="ratio"></th>
                <th>아동 번호</th>
                <th>요일</th>
                <th>시작</th>
                <th>종료</th>
            </tr>

            <?php
                $timeIndex = 0;
                while($row3 = mysqli_fetch_assoc($ret3)){
                    echo '<tr>';
                    echo '<td><input type="radio" name="time_index" value="'.$timeIndex.'"></td>';
                    echo '<td>'.$row3['child_serial'].'</td>'; 
                    echo '<td class="day">'.$row3['day'].'</td>'; 
                    echo '<td class="start_time">'.$row3['start_time'].'</td>';
                    echo '<td class="finish_time">'.$row3['finish_time'].'</td>';
                    echo '</tr>';
                    $timeIndex++;
                }
            ?>
        </table>
    </form>

    <script>
        function getSelectedTimeData() {
            var radios = document.getElementsByName('time_index');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    var row = radios[i].parentNode.parentNode;
                    return {
                        'day': row.getElementsByClassName('day')[0].innerText,
                        'start_time': row.getElementsByClassName('start_time')[0].innerText,
                        'finish_time': row.getElementsByClassName('finish_time')[0].innerText
                    };
                }
            }
            return null;
        }

        document.getElementById('time-update-button').addEventListener('click', function() {
            var timeData = getSelectedTimeData();
            if (timeData !== null) {
                window.location.href = 'childTime_update.php?child_serial=' + '<?php echo $child_serial; ?>' + '&day=' + timeData.day + '&start_time=' + timeData.start_time;
            }
        });

        document.getElementById('time-delete-button').addEventListener('click', function() {
            var timeData = getSelectedTimeData();
            if (timeData !== null) {
                window.location.href = 'childTime_delete.php?child_serial=' + '<?php echo $child_serial; ?>' + '&day=' + timeData.day + '&start_time=' + timeData.start_time;
            }
        });
    </script>


    <br><br><br>

    </form>
    </BODY>
</HTML>