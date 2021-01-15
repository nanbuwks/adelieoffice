<?php
 // add isset nanbuwks 20110622
  if (!isset($menutext))
       {
            $menutext="";
       }
  $menutext .= "<TABLE CELLPADDING=0>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/setting\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/setting/\">管理者設定</A></TD><TR>\n";

	$menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/groups\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/groups/\">グループ設定</a></TD><TR>\n";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/users\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/users/\">ユーザー設定</a></TD><TR>\n";

	$menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/locations\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/locations/\">行き先名称設定</a></TD><TR>\n";
	if (file_exists($basepath.$toppath."/schedule")) {
	  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/schedule_kind\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/schedule_kind/\">予定表区分設定</a></TD><TR>\n";
	}
	if (file_exists($basepath.$toppath."/room")) {
	  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/rooms_type\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/rooms_type/\">施設区分設定</a></TD><TR>\n";
	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/rooms\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/rooms/\">施設設定</a></TD><TR>\n";
	}
	if (file_exists($basepath.$toppath."/bulletin")) {
	  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/boards\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/boards/\">BBSルーム設定</a></TD><TR>\n";
	}
	if (file_exists($basepath.$toppath."/address")) {
	  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/address_kind\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/address_kind/\">住所録区分設定</a></TD><TR>\n";
	}
	if (file_exists($basepath.$toppath."/workflow")) {
	  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

	  $menutext .= "<TR><TD WIDTH=12>";
		if (preg_match("/\/admin\/flows\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
	  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/flows/\">フロー設定</a></TD><TR>\n";
	}
 
  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/logs\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/logs/\">利用ログ</a></TD><TR>\n";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/logs4\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/logs4/\">勤務ログ</a></TD><TR>\n";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/logs2\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/logs2/\">予定ログ</a></TD><TR>\n";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/logs3\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/logs3/\">詳細ログ</a></TD><TR>\n";

  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/holiday\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
  $menutext .= "</TD><TD><A HREF=\"$toppath/admin/holiday/\">祭日設定</A></TD><TR>\n";

  $menutext .= "<TR><TD COLSPAN=2 HEIGHT=5></TD></TR>";

  $menutext .= "<TR><TD WIDTH=12>";
	if (preg_match("/\/admin\/datas\//i",$request_uri)) { $menutext .= "<IMG SRC=\"../../image/tri.gif\">"; }
 $menutext .= "</TD><TD><A HREF=\"$toppath/admin/datas/\">データ管理</a></TD><TR>\n";
 

  $menutext .= "</TABLE>";
?>
