<?php
session_start();
$users = array(
    'admin' => array(
        'userId' => 1,
        'password' => 'admin',
        'isAdmin' => true,
        'name' => 'Admin'
    ),
    'user1' => array(
        'userId' => 2,
        'password' => 'user1',
        'isAdmin' => false,
        'name' => 'User 1'
    ),
    'user2' => array(
        'userId' => 3,
        'password' => 'user2',
        'isAdmin' => false,
        'name' => 'User 2'
    ),
);
if(isset($_SESSION['SessionUserId']) && $_SESSION['SessionUserId']) {
    header("location: dashboard.php"); 
    die();
}
if (isset($_POST) && isset($_POST['username'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  if (!$users[$username]) {
      $message = 'Invalid username or password';
  } else {
      $user = $users[$username];
      if ($password != $user['password']) {
          $message = 'Invalid username or password';
      } else {
          $_SESSION['SessionUserId'] = $user['userId'];
          $_SESSION['SessionIsAdmin'] = $user['isAdmin'];
          $_SESSION['SessionUserName'] = $user['name'];
          header("location: dashboard.php"); 
          exit();
      }
  }
}
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme-override.css" />
        <link rel="stylesheet" type="text/css" href="plugins/font-awesome/css/font-awesome.min.css" />
        <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js?"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>Login</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                  <form method="post" >
                      <input type="hidden" id="uname">
                      <input type="hidden" id="password">
                    <div class="container">
                      <label for="uname"><b>Username</b></label>
                      <input type="text" placeholder="Enter Username" name="username" id="username" required>
                      <label for="psw"><b>Password</b></label>
                      <input type="password" placeholder="Enter Password" name="password" id="password" required>
                      <button type="submit" onclick="">Login</button>
                    </div>
                    <div class="container" style="background-color:#f1f1f1">
                      <button type="button" class="cancelbtn">Cancel</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </body>
</html>