<?php
    session_start();
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $year = $_GET['year'];  // 년도 값을 받아옵니다.
    $month = $_GET['month'];  // 월 값을 받아옵니다.
    $date = $year . '-' . $month . '-' . $_GET['selectedDate'];

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
    <head>
        <meta charset='utf-8'>
        <style>
            table, td, th {
                border: 2px solid #444;
                border-collapse: collapse;
            }
            td, th {
                width: 150px;
                font-size: 25px;
            }
            table {
                width: 100%; /* 표의 너비를 최대로 설정합니다. */
                table-layout: fixed;
                border-collapse: collapse; /* 테두리를 한 줄로 만듭니다. */
            }
            h2 {
                font-size: 45px;  /* 글꼴 크기를 변경합니다. */
                text-align: center;  /* 텍스트를 중앙 정렬합니다. */
                margin: 10px 0;  /* 마진을 조절합니다. */
            }
        </style>
        
    </head>
    
    <body>
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
                    echo '<td>' . $row['num_participants'] . '명</td>';
                        
                    echo '<td><a href="#" onclick="openCenteredWindow(\'user_prog_detail.php?program_serial=' . urlencode($row['program_serial'])
                    . '\'); return false;">'. '상세보기' . '</a></td>';
            
                    // 창 가운데 정렬을 위한 코드
                    echo '
                    <script>
                    function openCenteredWindow(url) {
                        var width = 500;
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
        </table>  
        <br><br>
    </body>
</HTML>
