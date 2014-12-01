<?php
session_start();

function log_error($message) {
  if (empty($_SESSION['message_error'])) {
    $_SESSION['message_error'] = '';
  }
  $_SESSION['message_error'] .= $message . "<br>";
}

# We'll clear the errors first.
$_SESSION['message_error'] = '';

# This will set the variables to the posted parameters.
$name = $_POST['name'];
$question1 = $_POST['question1'];
$question2 = $_POST['question2'];
$question3 = $_POST['question3'];
$time = time();

// we'll connect to the database here.
$conn = mysqli_connect("localhost", "", "", "test");

if ($conn->connect_error) {
  log_error("can't connect to db. abandon all hope ye who enter here");
} else {

  // If there are no database errors, we'll create a prepared statement.
  $statement = $conn->prepare("INSERT INTO t_survey (c_name, c_question1, c_question2, c_question3, time) VALUES (?,?,?,?,?)");

  /*
  Note that we could also create a regular statement, but then we'd need to
  handle the escaping of our text values so that we'd avoid SQL injection attacks.
  It's also worth noting that we're not doing anything to prevent JavaScript
  attacks - a user could very easily insert JavasScript and have it display on the page.
  */

  // we bind these parameters so that we can execute the statement.
  // the "ssis" identifies string, string, integer, string.
  $statement->bind_param("ssssi", $a, $b, $c, $d, $e);

  $a = $name;
  $b = $question1;
  $c = $question2;
  $d = $question3;
  $e = $time;

  $statement->execute();
}

$conn-close();

// If there are no errors, we'll go back to the "show messages" screen.
if (!empty($_SESSION['message_error']) and strlen($_SESSION['message_error']) > 0) {
  header('location:survey.php');
} else {
  header('location:show.php');
}
