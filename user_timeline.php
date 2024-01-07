<?php
    session_start();
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    /* 시간 */
    $year = $_GET['year'];  // 년도 값을 받아옵니다.
    $month = $_GET['month'];  // 월 값을 받아옵니다.
    $date = $year . '-' . $month . '-' . $_GET['selectedDate'];
    $timestamp = strtotime($date);
    $day = date('N', $timestamp); // 'N'은 ISO-8601 요일 번호 (1=월요일, ..., 7=일요일)
    
    // 요일 번호를 한글 요일로 변환
    $day_of_week_num_to_kor = ['1' => '월', '2' => '화', '3' => '수', '4' => '목', '5' => '금', '6' => '토', '7' => '일'];
    $day = $day_of_week_num_to_kor[$day];

    $center_serial = $_SESSION['center_id']; // 세션에서 센터 ID를 가져옵니다.
    // 요일에 맞는 센터 운영 시간
    $sql_t = "SELECT start_time, finish_time FROM center_time
                WHERE day = '$day' AND center_serial='$center_serial'";
    $ret_t = mysqli_query($con, $sql_t);

    $row_t = mysqli_fetch_array($ret_t);
    if (empty($row_t)) {
        echo "오늘은 센터가 운영하지 않습니다.";
        exit(); // 시간 정보가 없으면 스크립트를 종료합니다.
    }

    $start_time = $row_t['start_time'];
    $finish_time = $row_t['finish_time'];

    $start = strtotime($start_time); // 문자열을 시간으로
    $finish = strtotime($finish_time);

    $timeArray = array(); // 배열 생성

    while($start < $finish){ // finish 시간은 포함하지 않음
        // Extract the time
        $time = date("H", $start); // hour만 가져옴

        $timeArray[] = $time; // 배열에 담고
        $start += 3600; // 1시간 추가 (초 단위)
    }

    include 'final_tbl.php';

    $tables = create_tables($con, $year, $month);
    $final_table = $tables[0];
    $in_table = $tables[1];

    // 데이터 가져오기
    $sql1 = "SELECT *
            FROM final_table P
            WHERE P.start_date <= '" . $date . "' AND P.finish_date >= '" . $date . "'";

    $sql2 = "SELECT *
            FROM in_table I
            WHERE I.start_date <= '" . $date . "' AND I.finish_date >= '" . $date . "'";

    $ret1 = mysqli_query($con, $sql1);
    $ret2 = mysqli_query($con, $sql2);
    if (!$ret1 || !$ret2) {
        die('데이터 가져오기 실패: ' . mysqli_error($con));
    }
?>

<HTML>
    <HEAD>
    <title>타임라인</title>
        <META http-equiv="content-type" content="text/html; charset=utf-8">
        <style>
            table, td, th {
                border: 2px solid #444;
                border-collapse: collapse;
            }
            td, th {
                font-size: 25px;
            }
            #TimeLineTable {
                width: 100%; /* TimeLineTable 테이블의 너비를 최대로 설정합니다. */
                table-layout: fixed;
            }
            #ProgramTable {
                width: 100%; /* ProgramTable 테이블의 너비를 80%로 설정합니다. */
                table-layout: fixed;
            }
            h2 {
                font-size: 45px;  /* 글꼴 크기를 변경합니다. */
                text-align: center;  /* 텍스트를 중앙 정렬합니다. */
                margin: 10px 0;  /* 마진을 조절합니다. */
            }
            h3 {
            font-size: 30px;  /* 글꼴 크기를 변경합니다. */
            margin: 10px 0;  /* 마진을 조절합니다. */
        }
        </style>
    </HEAD>

    <BODY>
    <h3>📌타임라인</h3>
    <table id="TimeLineTable">
        <tr>
            <th>시간</th>
            <th>프로그램</th>
        </tr>
        <?php
            for($i=0; $i<count($timeArray); $i++){
                echo '<tr>';
                echo '<td>'.$timeArray[$i].'</td>';
            
                $time = $timeArray[$i].':00:00';
                $program_serial=null;
            
                // 선택한 day와 선택한 시간에 맞는 프로그램 가져오기
                $sql_program = "SELECT F.program_serial, F.long_yn, F.start_date, F.finish_date FROM final_table F
                        WHERE F.start_date <= '$date' AND F.finish_date >= '$date'
                        AND F.start_time <= '$time' AND F.finish_time > '$time'
                        UNION
                        SELECT I.program_serial, I.long_yn, I.start_date, I.finish_date FROM in_table I
                        WHERE I.start_date <= '$date' AND I.finish_date >= '$date'
                        AND I.start_time <= '$time' AND I.finish_time > '$time'";
            
                $ret_program = mysqli_query($con, $sql_program);
                if (!$ret_program) {
                    die('쿼리 실패: ' . mysqli_error($con));
                }
                $row_program = mysqli_fetch_array($ret_program); // 이 줄을 추가합니다.
            
                if($row_program != NULL){
                    $program_serial = $row_program['program_serial'];
                    
                    // 프로그램 이름 가져오기
                    $sql_p = "SELECT program_name FROM program WHERE program_serial='$program_serial'";
                    $ret_p = mysqli_query($con, $sql_p);
                    $row_p = mysqli_fetch_array($ret_p);
                    
                    if($row_p != NULL){
                        if (!$row_program['long_yn'] == 1) {
                            echo '<td>'.$row_p['program_name'].'</td>';
                        }
                    } else {
                        echo '<td>' . "-" . '</td>';
                    }
                } else {
                    echo '<td>' . "-" . '</td>';
                }
            }            
        ?>
        <table id="ProgramTable">
            <tr>
                <th>프로그램 번호</th>
                <th>프로그램명</th>
                <th>날짜</th>
                <th>시간</th>
                <th>참여인원</th>
                <th></th>       
            </tr>
    
            <?php
                // 뷰에서 가져온 데이터를 담을 배열
                $rows = array();
                $selected_day_of_week = date('N', strtotime($date));  // 사용자가 선택한 날짜의 요일
                // 한글 요일을 숫자 요일로 변환
                $day_of_week_kor_to_num = ['월' => 1, '화' => 2, '수' => 3, '목' => 4, '금' => 5, '토' => 6, '일' => 7];

                while ($row = mysqli_fetch_assoc($ret1)) {
                    echo '<tr>';
                    echo '<td>' . $row['program_serial'] . '</td>';
                    echo '<td>' . $row['program_name'] . '</td>';
                    echo '<td>' . $row['start_date'] . ' ~ ' . $row['finish_date']. '</td>';
                    echo '<td>' . $row['start_time'] . ' ~ ' . $row['finish_time']. '</td>';
                    echo '<td>' . $row['num_participants'] . '명</td>';
                        
                    echo '<td><a href="#" onclick="openCenteredWindow(\'user_prog_detail.php?program_serial=' . urlencode($row['program_serial'])
                    . '\'); return false;">'. '상세보기' . '</a></td>';
            
                    // 창 가운데 정렬을 위한 코드
                    echo '
                    <script>
                    function openCenteredWindow(url) {
                        var width = 700;
                        var height = 500;
                        var left = (screen.width - width) / 2;
                        var top = (screen.height - height) / 2;
                        var params = 
                            "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=" 
                            + width + ", height=" + height + ", top=" + top + ", left=" + left;
                        window.open(url, "_blank", params);
                    }
                    </script>
                    ';
                
                    echo '</tr>';
                }

                while ($row = mysqli_fetch_assoc($ret2)) {
                    echo '<tr>';
                    echo '<td>' . $row['program_serial'] . '</td>';
                    echo '<td>' . $row['program_name'] . '</td>';
                    echo '<td>' . $row['start_date'] . ' ~ ' . $row['finish_date']. '</td>';
                    echo '<td>' . $row['start_time'] . ' ~ ' . $row['finish_time']. '</td>';
                    echo '<td>'.'  '.'</td>';    
                    echo '<td><a href="#" onclick="openCenteredWindow(\'user_prog_detail.php?program_serial=' . urlencode($row['program_serial'])
                    . '\'); return false;">'. '상세보기' . '</a></td>';
            
                    // 창 가운데 정렬을 위한 코드
                    echo '
                    <script>
                    function openCenteredWindow(url) {
                        var width = 700;
                        var height = 500;
                        var left = (screen.width - width) / 2;
                        var top = (screen.height - height) / 2;
                        var params = 
                            "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=" 
                            + width + ", height=" + height + ", top=" + top + ", left=" + left;
                        window.open(url, "_blank", params);
                    }
                    </script>
                    ';
                
                    echo '</tr>';
                }
            ?>
            <br>
            <h3>📌프로그램 목록</h3>
        </table>  
</BODY>
</HTML>