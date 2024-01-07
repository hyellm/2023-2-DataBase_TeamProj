<?php
    // session_start();
    
    $con=mysqli_connect("localhost", "root", "0708", "scheduler") or die("MySQL 접속 실패 !!");

    $sql_program = "SELECT program_serial, program_name, program_detail
                    FROM program
                    WHERE program_serial ='".$_GET['program_serial']."'" ;

    $ret_program = mysqli_query($con, $sql_program);
    $row_program=mysqli_fetch_array($ret_program);
    $serial=$row_program["program_serial"];
    $program_name=$row_program["program_name"];
    $program_detail =$row_program['program_detail'];


    $sql_capa = "SELECT P.capa_serial, C.capa_name, P.capa_level
             FROM program_capacity P
             JOIN capacity C ON P.capa_serial = C.capa_serial
             WHERE P.program_serial = '".$_GET['program_serial']."'" ;
    $ret_capa = mysqli_query($con, $sql_capa);


    $sql_cate = "SELECT C.cate_name, P.cate_serial
             FROM program_category P
             JOIN category C ON P.cate_serial = C.cate_serial
             WHERE P.program_serial = '".$_GET['program_serial']."'" ;
    $ret_cate = mysqli_query($con, $sql_cate);
?>


<HTML>
<HEAD>
<META http-equiv="content-type" content="text/html; charset=utf-8">
    <title><?php echo $program_name?></title>
    <style>
        #CapaTable {
            border: 2px solid #444;
            border-collapse: collapse;
        }
        #CapaTable th, #CapaTable td {
            border: 1px solid #444;
            padding: 5px;
            font-size: 25px;
        }
        .CateTable {
            /* border: 2px solid #444;
            border-collapse: collapse; */
        }
        * {
            font-size: 30px;  /* 글꼴 크기를 변경합니다. */
        }
    </style>
</HEAD>
<BODY>
    <h2>📌 프로그램 상세보기</h2>
    <b>프로그램 이름(식별번호) : </b><?php echo $program_name?> (<?php echo $serial?>)
    <br><br>
    <b>프로그램 상세 설명 : </b><?php echo $program_detail?> 

    <br><br>

    <table id="CapaTable" >
    <b>< 역량정보 ></b>
        <tr>
            <th>역량 번호</th>
            <th>역량 정보</th>
            <th>레벨</th>
        </tr>

        <?php
            while($row_capa = mysqli_fetch_assoc($ret_capa)){
                echo '<tr>';
                echo '<td>' . $row_capa['capa_serial'] . '</td>';
                echo '<td>' . $row_capa['capa_name'] . '</td>';
                echo '<td>' . $row_capa['capa_level'] . '</td>';
                echo '</tr>';
            }
        ?>
    </table>
    
    <br><br>
    <table id="CateTable" >
    <b>< 카테고리 정보 ></b>
        <?php
            while($row_cate = mysqli_fetch_assoc($ret_cate)){
                echo '<tr>';
                echo '<td>' . '- ' . $row_cate['cate_name'] . '</td>';
                echo '<td>' . '( ' . $row_cate['cate_serial'] . ' )' . '</td>';
                echo '</tr>';
            }
        ?>
    </table>


</BODY>
</HTML>
