<!DOCTYPE html>
<?php require(__DIR__.'/../config.php'); ?>
<html lang="en-US">
<head>
  <title>Middlesex Moodle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-highway.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <style>
    div {
      font-family: "Open Sans",sans-serif;
    }
  </style>
</head>

<body>
  <div class="w3-container w3-half">
    <div class="w3-card-4 w3-margin">
      <div class="w3-container w3-highway-red">
        <h1 style="text-align:center"><i class="material-icons" style="font-size:36px">school</i> MX Moodle</h1>
    </div>
      <form class="w3-container" action="<?php echo $CFG->wwwroot ?>/login/index.php" method="post">
        <p>Please sign in with your network credentials:</p>
        <input class="w3-input w3-border w3-round-large" type="text" name="username" placeholder="Username"><br>
        <input class="w3-input w3-border w3-round-large" type="password" name="password" placeholder="Password"><br><br>
        <button class="w3-btn w3-highway-red w3-margin w3-hover-shadow">Sign In to Moodle</button>
      </form>
    </div>
  </div>

  <div class="w3-container w3-half">
    <div class="w3-card-4 w3-margin">
      <div class="w3-container w3-highway-red">
        <h1 style="text-align:center">Welcome</h1>
      </div>
      <p class="w3-margin">You must sign in with your network credentials to access our Moodle site.
        You may use your username or your full email address and your network password.</p>
      <p class="w3-margin">MX Moodle is owned by <a href="https://mxschool.edu/">Middlesex School</a>, an independent,
        non-denominational, residential, college-preparatory school that, for over one hundred years, has been committed
        to excellence in the intellectual, ethical, creative, and physical development of young people.
        If you have questions regarding our Moodle site, please connect with <a href="mailto:chuck@mxschool.edu">Chuck
        in the IT Department.</a></p>
      <p class="w3-margin">Cookies must be enabled for this website.</p></br>
    </div>
  </div>

</body>
</html>