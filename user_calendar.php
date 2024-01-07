<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>사용자</title>
    <style>
        /* 표의 스타일을 지정합니다. */
        table {
            width: 100%; /* 표의 너비를 최대로 설정합니다. */
            table-layout: fixed;
            border-collapse: collapse; /* 테두리를 한 줄로 만듭니다. */
        }
        td {
            height: 150px; /* 셀의 높이를 고정합니다. */
            border: 1px solid black; /* 셀의 테두리를 그립니다. */
            padding: 5px; /* 셀의 패딩을 추가합니다. */
            text-align: left; /* 텍스트를 왼쪽 정렬합니다. */
            vertical-align: top; /* 셀의 내용을 위쪽으로 정렬합니다. */
            font-size: 25px;
        }
        th {
            height: 30px; /* 헤더 셀의 높이를 다른 셀과 다르게 설정합니다. */
            border: 1px solid black; /* 셀의 테두리를 그립니다. */
            padding: 5px; /* 셀의 패딩을 추가합니다. */
            text-align: center; /* 텍스트를 왼쪽 정렬합니다. */
            vertical-align: top; /* 셀의 내용을 위쪽으로 정렬합니다. */
            background-color: #DAF1FF; /* 헤더의 배경색을 설정합니다. */
            font-size: 25px;
        }
        button {
            margin: 0; /* 버튼의 마진을 제거합니다. */
            padding: 0; /* 버튼의 패딩을 제거합니다. */
            border: none; /* 버튼의 테두리를 제거합니다. */
            background: none; /* 버튼의 배경색을 제거합니다. */
            font: inherit; /* 버튼의 글꼴을 상속받습니다. */
            cursor: pointer; /* 버튼 위에 마우스를 올리면 포인터로 바뀝니다. */
            outline: inherit; /* 버튼의 윤곽선을 상속받습니다. */
        }
        select, label {
            width: 110px;  /* 너비를 변경합니다. */
            height: 45px;  /* 높이를 변경합니다. */
            font-size: 28px;  /* 글꼴 크기를 변경합니다. */
        }

        /* 조회 버튼의 스타일을 변경합니다. */
        input[type="submit"] {
            width: 110px;  /* 너비를 변경합니다. */
            height: 45px;  /* 높이를 변경합니다. */
            font-size: 28px;  /* 글꼴 크기를 변경합니다. */
        }
        h2 {
            font-size: 40px;  /* 글꼴 크기를 변경합니다. */
            text-align: center;  /* 텍스트를 중앙 정렬합니다. */
            margin: 10px 0;  /* 마진을 조절합니다. */
        }
        h3 {
            font-size: 33px;  /* 글꼴 크기를 변경합니다. */
            text-align: center;  /* 텍스트를 중앙 정렬합니다. */
            margin: 10px 0;  /* 마진을 조절합니다. */
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .calendar, .programs {
            width: 49.5%; /* 캘린더와 프로그램 테이블의 너비를 조정합니다. */
        }
        #NextProgramTable td {
            text-align: center;
            vertical-align: middle;
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
            <h2>
                <?php
                    $mysqli = new mysqli("localhost", "root", "0708", "scheduler");

                    // 세션에서 센터 번호와 선생님명을 가져옴
                    $center_id = $_SESSION['center_id'];
                    $teacher_name = $_SESSION['name'];
                    
                    // 센터 번호를 이용하여 센터명을 조회
                    $q = "SELECT center_name FROM center WHERE center_serial = '$center_id'";
                    $result = $mysqli->query($q);
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    $center_name = $row['center_name'];

                    echo "센터명: " . $center_name . " / 선생님명: " . $teacher_name; // 센터명과 선생님명 출력
                ?>
            </h2>
        </div>
    <form method="post">
    <label for="year">년도:</label>
    <select name="year" id="year">
        <!-- 년도 옵션을 생성합니다. -->
        <?php for ($i = 2020; $i <= 2030; $i++): ?>
            <option value="<?= $i ?>" <?php if(isset($_POST['year']) && $_POST['year']==$i) echo 'selected="selected"'; ?>>
                <?= $i ?>
            </option>
        <?php endfor; ?>
    </select>

    <label for="month">월:</label>
    <select name="month" id="month">
        <!-- 월 옵션을 생성합니다. -->
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>" <?php if(isset($_POST['month']) && $_POST['month']==$i) echo 'selected="selected"'; ?>>
                <?= $i ?>
            </option>
        <?php endfor; ?>
    </select>
        <input type="submit" value="조회">
        <br><br>
   
    </form>
    <div class="container">  <!-- 컨테이너 div를 추가합니다. -->
        <div class="calendar">  <!-- 캘린더 div를 추가합니다. -->
        <?php
        // 폼이 제출된 경우
        include 'final_tbl.php';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // MySQL 데이터베이스에 연결합니다. 연결 정보를 자신의 환경에 맞게 수정하세요.
                $conn = new mysqli("localhost", "root", "0708", "scheduler");

                // 연결에 실패한 경우 에러 메시지를 출력합니다.
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // 사용자가 선택한 년도와 월을 가져옵니다.
                $year = $_POST['year'];
                $month = $_POST['month'];
                echo "<script>";
                echo "var year = $year;";
                echo "var month = $month;";
                echo "</script>";
                // 이번 달의 프로그램을 가져오는 쿼리를 작성합니다.+
                $tables = create_tables($conn, $year, $month);
                $final_table = $tables[0];
                $in_table = $tables[1];
                
                // final_table 에 대한 SELECT 쿼리를 실행합니다.
                $result_fq = "SELECT * FROM final_table;";
                $result_f = $conn->query($result_fq);

                $result_iq = "SELECT * FROM in_table;";
                $result_i = $conn->query($result_iq);

                // 쿼리가 성공적으로 실행된 경우
                if ($result_i && $result_f) {
                    $programs = [];
                    $program_count = []; // 각 날짜에 추가된 프로그램의 수를 저장합니다.
                    while ($row_i = $result_i->fetch_assoc()) {
                        if ($row_i['long_yn'] == 1) {
                            // 시작 날짜부터 종료 날짜까지 루프를 돌면서 프로그램을 추가합니다.
                            $start_date = new DateTime($row_i['start_date']);
                            $end_date = new DateTime($row_i['finish_date']);
                            
                            for ($date = $start_date; $date <= $end_date; $date->modify('+1 day')) {
                                $day = $date->format('d');
                                if (!isset($programs[$day])) {
                                    $programs[$day] = [];
                                }
                                $programs[$day][] = ['program_name' => $row_i['program_name'], 'program_serial' => $row_i['program_serial']];
                                // inout_sep가 1일 때만 카운트를 증가시킵니다.
                                if ($row_i['inout_sep'] == 1) {
                                    if (!isset($program_count[$day])) {
                                        $program_count[$day] = 0;
                                    }
                                    $program_count[$day]++;
                                }
                            }
                        } else {
                            // long_yn이 1이 아니면 기존과 같이 처리합니다.
                            $date = substr($row_i['start_date'], 8, 2);
                            if (!isset($programs[$date])) {
                                $programs[$date] = [];
                            }
                            $programs[$date][] = ['program_name' => $row_i['program_name'], 'program_serial' => $row_i['program_serial']];
                            // inout_sep가 1일 때만 카운트를 증가시킵니다.
                            if ($row_i['inout_sep'] == 1) {
                                if (!isset($program_count[$date])) {
                                    $program_count[$date] = 0;
                                }
                                $program_count[$date]++;
                            }
                        }
                    }
                
                    while ($row_f = $result_f->fetch_assoc()) {
                        $date = substr($row_f['start_date'], 8, 2);
                        
                        if (!isset($programs[$date])) {
                            $programs[$date] = [];
                        }
                
                        // 모든 날짜의 이전 프로그램과 현재 프로그램의 번호를 비교합니다.
                        $isDuplicate = false;
                        foreach ($programs as $dayPrograms) {
                            foreach ($dayPrograms as $program) {
                                if ($program['program_serial'] == $row_f['program_serial']) {
                                    $isDuplicate = true;
                                    break 2; // 두 개의 foreach 루프를 모두 종료합니다.
                                }
                            }
                        }
                
                        // 이전에 이미 추가된 프로그램이면 건너뜁니다.
                        if ($isDuplicate) {
                            continue;
                        }
                
                        // 프로그램 테이블에서 해당 프로그램 시리얼 번호에 해당하는 센터 시리얼 번호를 찾습니다.
                        $program_serial = $row_f['program_serial'];
                        $center_query = "SELECT center_serial FROM program WHERE program_serial = $program_serial;";
                        $center_result = $conn->query($center_query);
                        $center_row = $center_result->fetch_assoc();
                        $center_serial = $center_row['center_serial'];

                        // 센터 테이블에서 해당 센터 시리얼 번호에 해당하는 oneday_program 값을 찾습니다.
                        $oneday_program_query = "SELECT oneday_program FROM center WHERE center_serial = $center_serial;";
                        $oneday_program_result = $conn->query($oneday_program_query);
                        $oneday_program_row = $oneday_program_result->fetch_assoc();
                        $oneday_program = $oneday_program_row['oneday_program'];

                        // 이 날짜에 추가된 프로그램의 수가 oneday_program보다 크면 건너뜁니다.
                        if (isset($program_count[$date]) && $program_count[$date] >= $oneday_program) {
                            continue;
                        }

                        $time_query = "SELECT start_time, finish_time FROM program_time WHERE program_serial = $program_serial;";
                        $time_result = $conn->query($time_query);
                        $time_row = $time_result->fetch_assoc();
                        $start_time = $time_row['start_time'];
                        $end_time = $time_row['finish_time'];
                    
                        // 이 날짜에 이미 추가된 프로그램들과 현재 프로그램의 시간을 비교합니다.
                        foreach ($programs[$date] as $program) {
                            // 각 프로그램의 시작 시간과 종료 시간을 가져옵니다.
                            $existing_time_query = "SELECT start_time, finish_time FROM program_time WHERE program_serial = {$program['program_serial']};";
                            $existing_time_result = $conn->query($existing_time_query);
                            $existing_time_row = $existing_time_result->fetch_assoc();
                            $existing_start_time = $existing_time_row['start_time'];
                            $existing_end_time = $existing_time_row['finish_time'];
                    
                            // 현재 프로그램의 시작 시간 또는 종료 시간이 기존 프로그램의 시간과 겹치면 건너뜁니다.
                            if (($start_time >= $existing_start_time && $start_time < $existing_end_time) ||
                                ($end_time > $existing_start_time && $end_time <= $existing_end_time)) {
                                $isDuplicate = true;
                                break; // 현재 날짜의 프로그램들을 검사하는 루프를 종료합니다.
                            }
                        }
                    
                        // 시간이 겹치는 프로그램이면 건너뜁니다.
                        if ($isDuplicate) {
                            continue;
                        }
                
                        $programs[$date][] = ['program_name' => $row_f['program_name'], 'program_serial' => $row_f['program_serial']];
                        if (isset($program_count[$date])) {
                            $program_count[$date]++; // 프로그램을 추가할 때마다 카운트를 증가시킵니다.
                        } else {
                            $program_count[$date] = 1; // 해당 날짜에 프로그램이 처음 추가되는 경우
                        }
                    }

                    // 달력을 생성합니다.
                    $date = new DateTime("$year-$month-01");
                    $end = (clone $date)->modify('+1 month');
                    $interval = new DateInterval('P1D');
                    $period = new DatePeriod($date, $interval, $end);

                    // 달력을 출력합니다.
                    echo "<table>";
                    echo "<tr><th colspan=\"7\"><h2>" . $year . "년 " . $month . "월</h2></th></tr>";
                    echo "<tr><th>일</th><th>월</th><th>화</th><th>수</th><th>목</th><th>금</th><th>토</th></tr>";
                    echo "<tr>";

                    // 첫 주의 빈 셀을 추가합니다.
                    for ($i = 0; $i < $date->format('w'); $i++) {
                        echo "<td></td>";
                    }

                    foreach ($period as $dt) {
                        // 새 주를 시작하면 새 행을 추가합니다.
                        if ($dt->format('w') == 0 && $dt != $date) {
                            echo "</tr><tr>";
                        }

                        $day = $dt->format('d');
                        echo "<td>";
                        echo "<button type=\"button\" onclick=\"saveDate('$day')\">" . $day . "</button><br>";
                        if (isset($programs[$day])) {
                            echo implode('<br>', array_map(function($program) {
                                return $program['program_name'];
                            }, $programs[$day]));                    
                        }
                        echo "</td>";
                    }

                    // 마지막 주의 빈 셀을 추가합니다.
                    for ($i = $dt->format('w'); $i < 6; $i++) {
                        echo "<td></td>";
                    }

                    echo "</tr></table>";

                } else {
                    // 쿼리 실행에 실패한 경우 에러 메시지를 출력합니다.
                    echo "Error: " . $query . "<br>" . $conn->error;
                }
        ?>
        </div>
        <div class="programs">  <!-- 프로그램 테이블 div를 추가합니다. -->
        <?php
                $nextMonth = $month + 1;
                $nextYear = $year;
                
                if ($nextMonth == 13) {
                    $nextMonth = 1;
                    $nextYear += 1;
                }
                echo "<h3>" . "⭐" . $nextYear . "년 " . $nextMonth . "월 장기 프로그램 알림" . "⭐" . "</h3>";
                echo "<table id=\"NextProgramTable\">";
                echo "<tr>";
                echo "<th>프로그램번호</th>";
                echo "<th>프로그램명</th>";
                echo "<th>날짜</th>";
                echo "<th>최소인원</th>";
                echo "<th></th>";
                echo "</tr>";
        
                $sql = "CREATE OR REPLACE VIEW next_month AS
                SELECT P.program_serial, P.program_name, PT.start_date, PT.finish_date, P.min_people, P.program_detail
                FROM program P
                JOIN program_time PT ON P.program_serial = PT.program_serial
                WHERE P.inout_sep = 2 AND MONTH(PT.start_date) = $nextMonth AND P.long_yn = 1";
        
                $ret = mysqli_query($conn, $sql);
                if (!$ret) {
                    die('뷰 생성 실패: ' . mysqli_error($conn));
                }
            
                // 데이터 가져오기
                $sql1 = "SELECT program_serial, program_name, start_date, finish_date, min_people FROM next_month";
                $ret1 = mysqli_query($conn, $sql1);
                if (!$ret) {
                    die('데이터 가져오기 실패: ' . mysqli_error($conn));
                }
        
                while($row = mysqli_fetch_assoc($ret1)){
                    echo '<tr>';
                    echo '<td>' . $row['program_serial'] . '</td>';
                    echo '<td>' . $row['program_name'] . '</td>';
                    echo '<td>' . $row['start_date'] . '~' . $row['finish_date']. '</td>';
                    echo '<td>' . $row['min_people'] . '</td>';
                    echo '<td><a href="#" onclick="openCenteredWindow(\'user_prog_detail.php?program_serial=' . urlencode($row['program_serial']) . '\'); return false;">'. '상세보기' . '</a></td>';
                    echo '</tr>';
                }
                echo "</table>";

                $conn->close();
            }
        ?>
    </div>
    </div>
    
    <div id="selectedDate"></div>

    <script>
        function saveDate(selectedDate) {
            // 선택된 날짜를 처리합니다.
            console.log("선택된 날짜: " + selectedDate);
            console.log("년도: " + year);
            console.log("월: " + month);

            // 팝업으로 user.php 파일 열기
            window.open('user_timeline.php?selectedDate=' + selectedDate + '&year=' + year + '&month=' + month, '_blank', 'width=700,height=500,left=0,top=0');
        }
    </script>

    <script>
    function openCenteredWindow(url) {
        var width = 500;
        var height = 350;
        var left = window.screen.width / 2 - width / 2;
        var top = window.screen.height / 2 - height / 2;
        window.open(url, "", 
            "scrollbars=yes, width=" + width + ", height=" + height + ", top=" + top + ", left=" + left);
    }
</script>
</body>
</html>