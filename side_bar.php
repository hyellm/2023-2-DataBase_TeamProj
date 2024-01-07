<?php
  session_start(); // 세션 시작

  $tab = $_GET['tab'] ?? 'default';

  // 사용자 로그인이 안되면 로그인 페이지로 다시 보내는 기능
  if(!isset($_SESSION['userid'])) {
    echo "<script>location.replace('login.php');</script>";            
    exit; // 스크립트 종료
  }

  $teacher_serial = $_SESSION['userid'];

  $mysqli = new mysqli('localhost', 'root', '0708', 'scheduler');

  // 연결 오류 확인 - 오류가 있으면 오류 띄우고 종료
  if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
    exit; // 스크립트 종료
  }

  // 선생님의 센터 정보 가져오기
  $stmt = $mysqli->prepare('SELECT teacher.teacher_name, center.* FROM teacher JOIN center ON teacher.center_serial = center.center_serial WHERE teacher.teacher_serial = ?');
  $stmt->bind_param('i', $teacher_serial);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $center_name = $row['center_name'];
  $teacher_name = $row['teacher_name'];

  // 연결 종료
  $stmt->close();
  $mysqli->close();
?>

<!DOCTYPE html>
  <html>
    <head>
      <meta charset = "utf-8">
      <title>관리자</title>
      <!-- 연결한 css 파일 부분 -->
      <link rel = "stylesheet" type="text/css" href="side_bar_style.css">     
    </head>

  <body>
    <!-- 사이드바 -->
    <div id="mySidebar" class="sidebar"> 
      <!-- 센터 (하위 내용이 있기 때문에 '센터'는 버튼으로만!) -->
      <!-- 센터명, 선생님명 따로 쓰기 위해서 상자로 묵음 -->
      <p><?php echo $center_name; ?></p>
      <p><?php echo $teacher_name; ?> <?php echo $_SESSION['position'] === '센터장' ? '센터장님' : '선생님'; ?> </p>

      <hr>
      <button class="center-btn">센터
        <i class="fa fa-caret-down"></i> </button>
        <!-- 센터 버튼을 눌렀을 때 -->
      <div class="dropdown-container">
        <a href="javascript:void(0)" onclick="showContentWithPermission('centerManageContent', 'side_bar.php?tab=centerManage');">센터관리</a>
        <a href="javascript:void(0)" onclick="showContentWithPermission('centerTeacherManageContent', 'side_bar.php?tab=centerTeacherManage');">센터선생님관리</a>
        <a href="javascript:void(0)" onclick="showContent('centerScheduleManageContent'); history.pushState(null, '', 'side_bar.php?tab=centerScheduleManage');">센터기존일정관리</a>
        <!-- 그 외에 아동, 프로그램 -->
      </div>
      <a href="javascript:void(0)" onclick="showContent('childContent'); history.pushState(null, '', 'side_bar.php?tab=child');">아동</a>
      <a href="javascript:void(0)" onclick="showContentWithPermission('programContent',  'side_bar.php?tab=program');">프로그램</a>
    </div>
    

    <div id="centerManageContent" class="content">
      <!-- '센터관리' 메뉴의 내용 -->
      <?php include 'center.php'; ?>
    </div>
    <div id="centerTeacherManageContent" class="content">
      <!-- '센터선생님관리' 메뉴의 내용 -->
      <?php include 'teacher.php'; ?>
    </div>
    <div id="centerScheduleManageContent" class="content">
      <!-- '센터기존일정관리' 메뉴의 내용 -->
      <?php include 'ExistProgram.php'; ?>
    </div>
    <div id="childContent" class="content">
      <!-- '아동' 메뉴의 내용 -->
      <?php include 'child.php'; ?>
    </div>
    <div id="programContent" class="content">
      <!-- '프로그램' 메뉴의 내용 -->
      <?php include 'OutProgram.php'; ?>
    </div>



    <script>
      var dropdown = document.getElementsByClassName("center-btn"); 
      var i;

      for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
          this.classList.toggle("active");
          var dropdownContent = this.nextElementSibling;
          if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
          } else {
            dropdownContent.style.display = "block";
          }
        });
      }

      var position = '<?php echo $_SESSION['position']; ?>';

      function showContentWithPermission(id, url) {
        if (position !== '센터장') {
          alert('권한이 없습니다.');
        } else {
          showContent(id);
          history.pushState(null, '', url);
        }
      }

      function showContent(id) {
        var contents = document.getElementsByClassName('content');
        for (var i = 0; i < contents.length; i++) {
          contents[i].style.display = 'none';
        }
        document.getElementById(id).style.display = 'block';
      }
    </script>


    <script>
      window.onload = function() {
        var tab = '<?php echo $tab; ?>';
        if (tab === 'child') {
          showContent('childContent');
        } else if (tab === 'program') {
          showContent('programContent');
        } else if (tab === 'centerManage') {
          showContent('centerManageContent');
        } else if (tab === 'centerTeacherManage') {
          showContent('centerTeacherManageContent');
        } else if (tab === 'centerScheduleManage') {
          showContent('centerScheduleManageContent');
        } 
      };
    </script>

  </body>
</html>