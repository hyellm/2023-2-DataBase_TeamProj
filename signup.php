<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        <title>회원가입</title>
        <!-- 연결한 css 파일 부분 -->
        <link rel = "stylesheet" type="text/css" href="signup_style.css">      
        <script>
            function showForm(formId) 
            {
                // 'directorForm'라는 id를 가진 요소에 hidden 클래스를 추가하여 해당 폼을 숨기기
                document.getElementById('directorForm').classList.add('hidden');
                // 'teacherForm'라는 id를 가진 요소에 hidden 클래스를 추가하여 해당 폼을 숨기기
                document.getElementById('teacherForm').classList.add('hidden');
                // formId에 해당하는 id를 가진 요소에서 hidden 클래스를 제거하여 해당 폼을 보여주기
                document.getElementById(formId).classList.remove('hidden');
            }
        </script>
    </head>
    <body>
        <div class="header">
            <img src="image.jpg" width="100">
            <h1>회원가입</h1>
            <img src="image.jpg" width="100">
        </div>
        <div class="button-group">
            <button onclick="showForm('directorForm')">센터장으로 가입하기</button>
            <button onclick="showForm('teacherForm')">선생님으로 가입하기</button>
        </div>
        <div class="form-container">
            <form id="directorForm" class="hidden" action="" method="post">
                <!-- 센터장 회원가입 폼 -->
                <br> 센터명: <input type="text" name="center_name"><br>
                센터 전화번호: <input type="text" name="center_tel"><br>
                센터 주소: <input type="text" name="center_address"><br>
                <br> 이름: <input type="text" name="teacher_name"><br>
                성별: 
                    <select name="gender">
                        <option value="남">남</option>
                        <option value="여">여</option>
                    </select>
                <br> 전화번호: <input type="text" name="teacher_tel"><br>
                비밀번호: <input type="password" name="password"><br>
                주소: <input type="text" name="address"><br><br>
                <button type="submit" name="submit_director">가입하기</button>
            </form>

            <form id="teacherForm" class="hidden" action="" method="post">
                <!-- 선생님 회원가입 폼 -->
                <br> 이름: <input type="text" name="teacher_name"><br>
                성별: 
                    <select name="gender">
                    <option value="남">남</option>
                    <option value="여">여</option>
                </select>
                <br> 전화번호: <input type="text" name="teacher_tel"><br>
                비밀번호: <input type="password" name="password"><br>
                주소: <input type="text" name="address"><br>
                센터: 
                    <select name="center_serial">
                        <?php
                            // 데이터베이스 연결
                            $mysqli = new mysqli('localhost', 'root', '0708', 'child_time');

                            // 연결 오류 확인
                            if ($mysqli->connect_error) {
                            die('Connect Error: ' . $mysqli->connect_error);
                            }

                            // 쿼리 실행
                            $result = $mysqli->query('SELECT * FROM center');

                            // 결과를 각각의 옵션으로 변환
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['center_serial'] . '">' . 	$row['center_name'] . '</option>';
                            }

                            // 연결 종료
                            $mysqli->close();
                        ?>
                    </select><br><br>
                <button type="submit" name="submit_teacher">가입하기</button>
            </form>
        </div>
    </body>
</html>


<?php
    // 데이터베이스 연결
    $mysqli = new mysqli('localhost', 'root', '0708', 'child_time');

    // 연결 오류 확인
    if ($mysqli->connect_error) {
        die('Connect Error: ' . $mysqli->connect_error);
    }

    if (isset($_POST['submit_director'])) {
        $center_name = $_POST['center_name'];
        $center_tel = $_POST['center_tel'];
        $center_address = $_POST['center_address'];
        $teacher_name = $_POST['teacher_name'];
        $gender = $_POST['gender'];
        $teacher_tel = $_POST['teacher_tel'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        // 모든 필드가 채워졌는지 확인
        if (empty($center_name) || empty($center_tel) || empty($center_address) || empty($teacher_name) || empty($gender) || empty($teacher_tel) || empty($password) || empty($address)) {
            echo "<script>alert('모든 필드를 채워주세요.');</script>";
        } 
        else {
            // 센터 정보 저장
            $stmt = $mysqli->prepare('INSERT INTO center (center_name, center_tel, center_address) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $center_name, $center_tel, $center_address);

            // 쿼리 실행
            if (!$stmt->execute()) {
                echo '센터 정보 저장 실패: ' . $stmt->error;
                $stmt->close();
                exit;
            }

            // 저장된 센터의 ID 가져오기
            $center_id = $mysqli->query('SELECT LAST_INSERT_ID() as center_serial');
            $row = $center_id->fetch_assoc();
            $center_id = $row['center_serial'];

            // 센터장 정보 저장
            $stmt = $mysqli->prepare('INSERT INTO teacher (teacher_name, gender, teacher_tel, password, address, center_serial, position) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $position = "센터장";
            $stmt->bind_param('sssssis', $teacher_name, $gender, $teacher_tel, $password, $address, $center_id, $position);

            // 쿼리 실행
            if ($stmt->execute()) {
                echo '<br><br>회원가입 성공!';
                header('Location: home.php');
            } 
            else {
                echo '<br><br>센터장 정보 저장 실패: ' . $stmt->error;
            }

            // 연결 종료
            $stmt->close();
        }
    }

    if (isset($_POST['submit_teacher'])) {
        $teacher_name = $_POST['teacher_name'];
        $gender = $_POST['gender'];
        $teacher_tel = $_POST['teacher_tel'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $center_id = $_POST['center_serial'];

        // 모든 필드가 채워졌는지 확인
        if (empty($teacher_name) || empty($gender) || empty($teacher_tel) || empty($password) || empty($address) || empty($center_id)) {
            echo "<script>alert('모든 필드를 채워주세요.');</script>";
        } 
        else {
            // 쿼리 준비
            $stmt = $mysqli->prepare('INSERT INTO teacher (teacher_name, gender, teacher_tel, password, address, center_serial, position) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $position = "선생님";
            $stmt->bind_param('sssssis', $teacher_name, $gender, $teacher_tel, $password, $address, $center_id, $position);

            // 쿼리 실행
            if ($stmt->execute()) {
                echo '<br><br>회원가입 성공!';
                header('Location: home.php');
            } 
            else {
                echo '<br><br>회원가입 실패: ' . $stmt->error;
            }

            // 연결 종료
            $stmt->close();
        }
    }

    $mysqli->close();
?>