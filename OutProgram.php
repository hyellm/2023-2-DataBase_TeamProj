<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['userid'])) {
        // 로그인 페이지로 리다이렉트
        header('Location: login.php');
         exit;
    }
    /* 선생님 번호를 받아온다. */
    $teacher_serial = $_SESSION['userid'];
    
    /* MYSQL을 연동한다. */
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $program_serial = isset($_POST['program_serial']) ? $_POST['program_serial'] : null;

    $sql_t ="SELECT *FROM teacher WHERE teacher_serial = $teacher_serial";
    $ret_t = mysqli_query($con, $sql_t);
    if ($ret_t === false) {
        die("쿼리 실행 실패: " . mysqli_error($con));
    }
    $row_t = mysqli_fetch_assoc($ret_t);
    $center_serial = $row_t['center_serial'];

    /* 프로그램 테이블에서 프로그램을 가져온다. */
    $sql ="SELECT * FROM program WHERE center_serial = $center_serial AND( inout_sep = '1' OR inout_sep ='2' )";
    $ret = mysqli_query($con, $sql);


    /* 프로그램 역량 테이블에서 프로그램 역량을 가져온다. */
   $sql1="SELECT * FROM program_capacity p JOIN program pr ON p.program_serial = pr.program_serial
            WHERE pr.inout_sep IN ('1', '2') AND pr.center_serial = $center_serial";
    if ($program_serial !== null) {
        $sql1 .= " AND p.program_serial = $program_serial";
    }
    $ret1 = mysqli_query($con, $sql1);


    /* 프로그램 카테고리 테이블에서 프로그램 카테고리를 가져온다. */
    $sql2 ="SELECT *FROM program_category p JOIN program pr ON p.program_serial = pr.program_serial
            WHERE pr.inout_sep IN ('1', '2') AND pr.center_serial = $center_serial";
    if ($program_serial !== null) {
        $sql2 .= " AND pr.program_serial = $program_serial";
    }
    $ret2 = mysqli_query($con, $sql2);


    /* 프로그램 시간 테이블에서 프로그램 시간을 가져온다. */
    $sql3 ="SELECT *FROM program_time p JOIN program pr ON p.program_serial = pr.program_serial
            WHERE pr.inout_sep IN ('1', '2') AND pr.center_serial = $center_serial";
    if ($program_serial !== null) {
        $sql3 .= " AND pr.program_serial = $program_serial";
    }
    $ret3 = mysqli_query($con, $sql3);

?>

<HTML>
    <head>
        <meta charset='utf-8'>
        <link rel = "stylesheet" type="text/css" href="site.css"> 
        <title>프로그램</title>
    </head>
    
    <body>
        <h3>📌 프로그램 정보</h3>
        <form method="post" id="program-table-form" action="side_bar.php?tab=program">
            <input type="hidden" name="center_serial" value="<?php echo $center_serial; ?>">
            <button type="submit" formaction="program_insert.php">추가</button>
            <button type="button" id="program-update-button">수정</button>
            <button type="button" id="program-delete-button">삭제</button>
            <input type="submit" value="조회">
        <table>
            <tr>
                <th class="ratio"></th>
                <th>프로그램 번호</th>
                <th>프로그램명</th>
                <th>상세 설명</th>
                <th>내외부</th>
                <th>비용</th>
                <th>최소 인원</th>
                <th>장기 여부</th>          
            </tr>
    
            <?php
            while($row = mysqli_fetch_assoc($ret)){
                echo '<tr>';
                echo '<td><input type="radio" name="program_serial" value="'.$row['program_serial'] .'"></td>';
                echo '<td>' . $row['program_serial'] . '</td>';
                echo '<td>' . $row['program_name'] . '</td>';
                echo '<td>' . $row['program_detail'] . '</td>';
                echo '<td>' . (($row['inout_sep'] == 1) ? "내부" : "외부") . '</td>';
                echo '<td>' . $row['program_money'] . '</td>';
                echo '<td>' . $row['min_people'] . '</td>';
                echo '<td>' . (($row['long_yn'] == 0) ? "단기" : "장기") . '</td>';
                echo '</tr>';
            }
        ?>
    </table>  
    </form>

    <script>
        function getSelectedProgramSerial() {
            var radios = document.getElementsByName('program_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('program-update-button').addEventListener('click', function() {
            var programSerial = getSelectedProgramSerial();
            if (programSerial !== null) {
                window.location.href = 'program_update.php?program_serial=' + programSerial;
            }
        });

        document.getElementById('program-delete-button').addEventListener('click', function() {
            var programSerial = getSelectedProgramSerial();
            if (programSerial !== null) {
                window.location.href = 'program_delete.php?program_serial=' + programSerial;
            }
        });
    </script>


    <br><br><br>


    <h3>📌 프로그램 역량</h3>
    <form method="post" id="program-capacity-table-form">
        <input type="hidden" name="ch" value="<?php echo $program_serial; ?>">
        <button type="submit" formaction="program_capacity_insert.php">추가</button>
        <button type="button" id="program-capacity-update-button">수정</button>
        <button type="button" id="program-capacity-delete-button">삭제</button>
    <table id="capacityTable" >
       <tr>
            <th class="ratio"></th>
            <th>프로그램 번호</th>
            <th>역량 번호</th>
            <th>역량 레벨</th>
        </tr>
        
         <?php
            while($row1 = mysqli_fetch_assoc($ret1)){
                echo '<tr>';
                echo '<td><input type="radio" name="capa_serial" value="'.$row1['capa_serial'].'"></td>';
                echo '<td>' . $row1['program_serial'] . '</td>';
                echo '<td>' . $row1['capa_serial'] . '</td>';
                echo '<td>' . $row1['capa_level'] . '</td>'; 
                echo '</tr>';
            }
               
        ?>
    </table>
    </form>


    <script>
        function getSelectedProgramCapacitySerial() {
            var radios = document.getElementsByName('capa_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('program-capacity-update-button').addEventListener('click', function() {
            var capacitySerial = getSelectedProgramCapacitySerial();
            if (capacitySerial !== null) {
                window.location.href = 'program_capacity_update.php?program_serial=' + '<?php echo $program_serial; ?>' + '&capa_serial=' + capacitySerial;
            }
        });

        document.getElementById('program-capacity-delete-button').addEventListener('click', function() {
            var capacitySerial = getSelectedProgramCapacitySerial();
            if (capacitySerial !== null) {
                window.location.href = 'program_capacity_delete.php?program_serial=' + '<?php echo $program_serial; ?>' + '&capa_serial=' + capacitySerial;
            }
        });
    </script>
    
    <br><br><br>
    

    <h3>📌 프로그램 카테고리</h3>
        <form method="post" id="program-category-table-form">
        <input type="hidden" name="ch" value="<?php echo $program_serial; ?>">
        <button type="submit" formaction="program_category_insert.php">추가</button>
        <button type="button" id="program-category-delete-button">삭제</button>
        <table id="categoryTable" >
        <tr>
            <th class="ratio"></th>
            <th>프로그램 번호</th>
            <th>카테고리 번호</th>
        </tr>

        <?php
            while($row2 = mysqli_fetch_assoc($ret2)){
                echo '<tr>';
                echo '<td><input type="radio" name="cate_serial" value="'.$row2['cate_serial'].'"></td>';
                echo '<td>' . $row2['program_serial'] . '</td>';
                echo '<td>' . $row2['cate_serial'] . '</td>';
                echo '</tr>';
                
            }
        ?>
    </table>
    </form>
    
    <script>
        function getSelectedProgramCategorySerial() {
            var radios = document.getElementsByName('cate_serial');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    return radios[i].value;
                }
            }
            return null;
        }

        document.getElementById('program-category-delete-button').addEventListener('click', function() {
            var categorySerial = getSelectedProgramCategorySerial();
            if (categorySerial !== null) {
                window.location.href = 'program_category_delete.php?program_serial=' + '<?php echo $program_serial; ?>' + '&cate_serial=' + categorySerial;
            }
        });
    </script>


    <br><br><br>


    <h3>📌 프로그램 시간</h3>
        <form method="post" id="program-time-table-form">
        <input type="hidden" name="ch" value="<?php echo $program_serial; ?>">
        <button type="submit" formaction="program_time_insert.php">추가</button>
        <button type="button" id="program-time-update-button">수정</button>
        <button type="button" id="program-time-delete-button">삭제</button>
        <table id="timeTable" >
        <tr>
            <th class="ratio"></th>
            <th>프로그램 번호</th>
            <th>요일</th>
            <th>시작 날짜</th>
            <th>시작 시간</th>
            <th>종료 날짜</th>
            <th>종료 시간</th>
        </tr>

        <?php
            $timeIndex = 0;
            while($row3 = mysqli_fetch_assoc($ret3)){
                echo '<tr>';
                echo '<td><input type="radio" name="time_index" value="'.$timeIndex.'"></td>';
                echo '<td>' . $row3['program_serial'] . '</td>';
                echo '<td>' . $row3['day'] . '</td>'; 
                echo '<td class="start_date">' . $row3['start_date'] . '</td>';
                echo '<td class="start_time">' . $row3['start_time'] . '</td>';
                echo '<td>' . $row3['finish_date'] . '</td>';
                echo '<td>' . $row3['finish_time'] . '</td>';
                $timeIndex++;
            }
            
        ?>
    </table>
    </form>

    <script>
    // 프로그램 시간 수정 및 삭제 이벤트
        function getSelectedProgramTimeData() {
            var radios = document.getElementsByName('time_index');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    var row = radios[i].parentNode.parentNode;
                    return {
                        'start_time': row.getElementsByClassName('start_time')[0].innerText,
                        'start_date': row.getElementsByClassName('start_date')[0].innerText
                    };
                }
            }
            return null;
        }

        document.getElementById('program-time-update-button').addEventListener('click', function() {
            var timeData = getSelectedProgramTimeData();
            if (timeData !== null) {
                window.location.href = 'program_time_update.php?program_serial=' + '<?php echo $program_serial; ?>' + '&start_time=' + timeData.start_time + '&start_date=' + timeData.start_date;
            }
        });

        document.getElementById('program-time-delete-button').addEventListener('click', function() {
            var timeData = getSelectedProgramTimeData();
            if (timeData !== null) {
                window.location.href = 'program_time_delete.php?program_serial=' + '<?php echo $program_serial; ?>' + '&start_time=' + timeData.start_time;
            }
        });
    </script>

    <br><br><br>
        </form>
    </body>
</HTML>