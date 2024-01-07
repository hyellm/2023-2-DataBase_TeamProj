<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>로그인</title>
    <!-- 연결한 css 파일 부분 -->
    <link rel = "stylesheet" type="text/css" href="login_style.css">   
  </head>

  <body>
    <form method="post" action="login.php" class="loginForm">
      <div class="header">
          <img src="image.jpg" width="100">
          <h1>로그인</h1>
          <img src="image.jpg" width="100">
      </div>
      <div class="idForm">
        선생님번호 : <input type="text" name="id" class="id"><br>
      </div>
      <div class="passForm">
        비밀번호 : <input type="password" name="pw" class="pw">
      </div><br>
      <button type="submit" class="btn" onclick="button()">
        로그인
      </button>
    </form>
    <?php
  session_start(); // 세션 시작 
  // 데이터베이스 연결
  
  $mysqli = new mysqli('localhost', 'root', '0708', 'scheduler');

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // login.php에서 입력받은 id, password
    $userid = $_POST['id'];
    $userpass = $_POST['pw'];
    $q = "SELECT * FROM teacher WHERE teacher_serial = '$userid' AND password = '$userpass'";
    $result = $mysqli->query($q);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    // 결과가 존재하면 세션 생성
    if ($row != null) {
      $_SESSION['userid'] = $row['teacher_serial'];
      $_SESSION['name'] = $row['teacher_name'];
      $_SESSION['position'] =  $row['position'];
      $_SESSION['center_id'] =  $row['center_serial'];
      
      // 센터장인 경우 side_bar.php로 바로 이동
      if ($row['position'] == '센터장') {
        echo "<script>location.replace('side_bar.php');</script>";
        exit;
      } else {
        // 선생님인 경우 팝업 창을 통해 선택할 수 있는 옵션 제공
        echo "<script>
          if (confirm('확인을 누르시면 관리자로 이동합니다. 취소를 누르시면 사용자로 갑니다.')) {
            location.replace('side_bar.php');
          } else {
            location.replace('user_calendar.php');
          }
        </script>";
        exit;
      }
    }
    // 결과가 존재하지 않으면 로그인 실패
    if($row == null){
      echo "<script>alert('아이디/비밀번호가 틀렸습니다.')</script>";
      echo "<script>location.replace('login.php');</script>";
      exit;
    }
  }
?>
  </body>
</html>

