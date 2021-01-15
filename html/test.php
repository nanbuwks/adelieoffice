<?php
    // DB関連
  $server    = "localhost";
#  $server    = "gw1.adelie.ne.jp";
  $port      = "5432";
  $user      = "apache";
  $pg_passwd = "Cab6402!";
#  $db        = "gw_".$subdomain;
  $db        = "AdelieOffice";

  $network   = "internet";

$pgsql_connect = "";
  if ($server<>"")    { $pgsql_connect .= "host=".$server." "; }
  if ($port<>"")      { $pgsql_connect .= "port=".$port." "; }
  if ($user<>"")      { $pgsql_connect .= "user=".$user." "; }
  if ($pg_passwd<>"") { $pgsql_connect .= "password=".$pg_passwd." "; }
  if ($db<>"")        { $pgsql_connect .= "dbname=".$db." "; }

    $conn = pg_connect($pgsql_connect);
    pg_query($conn,"SET DATESTYLE TO 'ISO'");



phpinfo();

?>
