<?php
  $rip = getenv("REMOTE_ADDR");
  if (preg_match("210.153.84.",$rip)==1 or preg_match("210.136.161.",$rip)==1) {
    // imode処理
    if ($id != "") {
      $login = true;
      $login_id = $id;
    }
  } else {
    // 通常処理
    if (!empty($HTTP_COOKIE_VARS["adelie:login"])) {
      $login = true;
      // えんちょー
      setcookie("adelie:login",$HTTP_COOKIE_VARS["adelie:login"],time()+1800);

      $cols = preg_split("\t",$HTTP_COOKIE_VARS["adelie:login"]);
      $login_id = $cols[0];

    } else {
      $login = false;
      $login_id = $cols[0];
    }
  }
?>
