<?php
function create_tables($conn, $year, $month) {
    $firstDayOfMonth = $year . '-' . $month . '-01';
    $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

    $queries = ["
        CREATE TEMPORARY TABLE month_time AS
        SELECT P.*, PT.start_time, PT.finish_time, PT.start_date, PT.finish_date, PT.day
        FROM program P
        JOIN program_time PT ON P.program_serial = PT.program_serial
        JOIN center_time CT ON P.center_serial = CT.center_serial AND PT.day = CT.day
        WHERE PT.start_date >= '$firstDayOfMonth' AND PT.finish_date <= '$lastDayOfMonth'
            AND PT.start_time >= CT.start_time AND PT.finish_time <= CT.finish_time;
    ", "
        CREATE TEMPORARY TABLE in_table AS
        SELECT MT.*
        FROM month_time MT
        WHERE MT.inout_sep = 0 OR MT.inout_sep = 1;
    ", "
        CREATE TEMPORARY TABLE long_program AS
        SELECT MT.*
        FROM month_time MT
        WHERE NOT EXISTS (
            SELECT 1 
            FROM in_table IT
            WHERE ( IT.long_yn = 0
                AND IT.day = IT.day
                AND ( (MT.start_date <= IT.start_date AND MT.finish_date > IT.start_date) 
                OR (MT.start_date < IT.finish_date AND MT.finish_date >= IT.finish_date)
                OR (MT.start_date = IT.start_date AND MT.finish_date = IT.finish_date) )
            AND ( (MT.start_time <= IT.start_time AND MT.finish_time > IT.start_time) 
                OR (MT.start_time < IT.finish_time AND MT.finish_time >= IT.finish_time) ) )
        );
    ", "
    CREATE TEMPORARY TABLE partici_num AS
    SELECT LP.*, 
        (SELECT COUNT(DISTINCT CT.child_serial) 
            FROM child C
            JOIN child_time CT ON CT.child_serial = C.child_serial
            WHERE CT.day = LP.day AND CT.start_time <= LP.start_time AND CT.finish_time >= LP.finish_time AND C.center_serial = LP.center_serial) as num_participants
    FROM long_program LP
    WHERE EXISTS (
        SELECT 1
        FROM child C
        JOIN child_time CT ON CT.child_serial = C.child_serial
        WHERE CT.day = LP.day AND CT.start_time <= LP.start_time AND CT.finish_time >= LP.finish_time AND C.center_serial = LP.center_serial
        GROUP BY LP.program_serial, LP.day, LP.start_time, LP.finish_time, LP.center_serial
        HAVING COUNT(DISTINCT CT.child_serial) >= LP.min_people
    );

    ", "
        CREATE TEMPORARY TABLE child_capa_inter AS
        SELECT PN.*,
            (SELECT AVG(CA.capa_level) 
            FROM child_capacity CA 
            JOIN child C ON PN.center_serial = C.center_serial
            JOIN program_capacity PC ON PN.program_serial = PC.program_serial
            WHERE C.child_serial = CA.child_serial AND CA.capa_serial = PC.capa_serial) 
            AS avg_capacity, 
            (SELECT AVG(CI.inter_level) 
            FROM child_interest CI 
            JOIN child C ON PN.center_serial = C.center_serial
            JOIN program_category PI ON PN.program_serial = PI.program_serial
            WHERE C.child_serial = CI.child_serial AND CI.cate_serial = PI.cate_serial) 
            AS avg_interest
        FROM partici_num PN; 
    ", "
        CREATE TEMPORARY TABLE diff_CCI AS
        SELECT CCI.*,
            (SELECT AVG(PC.capa_level) 
            FROM program_capacity PC 
            WHERE CCI.program_serial = PC.program_serial) AS prog_avg_capa,
            ABS(CCI.avg_capacity - (SELECT AVG(PC.capa_level) 
                                FROM program_capacity PC 
                                WHERE CCI.program_serial = PC.program_serial)) AS diff_avg_capa
        FROM child_capa_inter CCI;
    ", "
        CREATE TEMPORARY TABLE final_table AS
        SELECT *
        FROM diff_CCI 
        ORDER BY diff_avg_capa ASC, avg_interest DESC, num_participants ASC; 
    "];

    foreach ($queries as $query) {
        if ($conn->query($query) === FALSE) {
            echo "Error: " . $conn->error;
        }
    }

    return ['final_table', 'in_table'];
}

?>