<?php
function escape($html) {
  return htmlspecialchars($html ?? '', ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

require_once("../../interface/globals.php");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/patient.inc");

$result = getPatientData($pid, "fname,lname,mname,pid,pubpid,phone_home,pharmacy_id,DOB,DATE_FORMAT(DOB,'%Y%m%d'),referred_by,referred_date as DOB_YMD");
//echo " " . xlt('Patient') . " " . text($result['fname']) . " " . text($result['lname']) . " " . text($result['pid']);
$p_id=text($result['pid']);
$p_name = text($result['lname']) . ", " . text($result['fname']) . " " . text($result['mname']);

$user = $_SESSION['authUserID'];

$month = date('m');
$day = date('d');
$year = date('Y');
$now = time();
$today = date('Y-m-d', $now);
$timestamp = date('Y-m-d H:i:s', $now);

function csrf() {

  session_start();

  if (empty($_SESSION['csrf'])) {
    if (function_exists('random_bytes')) {
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    } else if (function_exists('mcrypt_create_iv')) {
      $_SESSION['csrf'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
      $_SESSION['csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }
}