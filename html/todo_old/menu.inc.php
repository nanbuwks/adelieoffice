<?php
/*
$menutext .= "
<TABLE><FORM ACTION=\"$toppath/todo/search/\" METHOD=POST>
<TR>
<TD><IMG SRC=\"$toppath/image/search.gif\" WIDTH=16 HEIGHT=16 BORDER=0 ALT=\"検索\" ALIGN=ABSMIDDLE><INPUT TYPE=TEXT NAME=\"kwd\" VALUE=\"$kwd\" SIZE=15 STYLE=\"width:98px\"><INPUT TYPE=SUBMIT VALUE=\"検索\" STYLE=\"width:36px\">
</TD></TR></FORM></TABLE>";
*/

if (empty($todo_sort)) { $todo_sort = "priority"; }

$menutext .= "<TABLE><FORM ACTION=\"$toppath/todo/\" METHOD=POST><TR><TD STYLE=\"font-size:85%\">\n";
$menutext .= "<INPUT TYPE=RADIO NAME=\"sort\" VALUE=\"stamp\" style=\"vertical-align:-2\"";
if ($todo_sort == "stamp") { $menutext .= " checked"; }
$menutext .= " ONCLICK=\"location.href=('$toppath/todo/?new_sort=stamp')\">作成順\n";
$menutext .= "<INPUT TYPE=RADIO NAME=\"sort\" VALUE=\"priority\" style=\"vertical-align:-2\"";
if ($todo_sort == "priority") { $menutext .= " checked"; }
$menutext .= " ONCLICK=\"location.href=('$toppath/todo/?new_sort=priority')\">優先度順\n";
$menutext .= "</TD></TR></FORM></TABLE>\n";

$menutext .= "<CENTER>
<TABLE CELLPADDING=1 CELLSPACING=0 BORDER=0 WIDTH=160 BGCOLOR=#666666><TR><TD>
<TABLE CELLPADDING=4 CELLSPACING=0 BORDER=0 WIDTH=158 BGCOLOR=#666666>
<TR><TD BGCOLOR=#999999><A HREF=\"$toppath/todo/\" STYLE=\"color:#FFFFFF\"><IMG SRC=\"$toppath/image/todo.gif\" ALIGN=ABSMIDDLE ALT=\"備忘録\" BORDER=0> <A HREF=\"$toppath/todo/\"><FONT COLOR=#FFFFFF>備忘録</A></TD></TR>
<TR>
<TD STYLE=\"line-height:15px\" BGCOLOR=#FFFFFF VALIGN=TOP>
";

$sql = "select * from todos where user_id='".$login_id."'";
if ($todo_sort == "stamp") {
	$sql .= "ORDER BY createstamp DESC,updatestamp DESC,seqno DESC";
} else {
	$sql .= "ORDER BY priority, updatestamp DESC, seqno DESC";
}
$res = pg_query($conn,$sql);
$cnt_s = pg_num_rows($res); // 件数取得

if ($cnt_s <= 0) {
	$menutext .= "メモはありません\n";
} else {
	$menutext .= "<TABLE CELLPADDING=1 CELLSPACING=0 BORDER=0 WIDTH=150>\n";

	for($r=0;$cnt_s>$r;$r++){
		$row = pg_fetch_array($res,$r);

		if (empty($n) && $r==0) { $n = $row["seqno"]; }
		//bgcolor変更
		if ($r % 2 == 0){
			$tdcolor = $bg_dark;
		}else{
			$tdcolor = $bg_light;
		}
		
		// 備忘録
	  $menutext .= "<TR><TD WIDTH=16>";
	  if ($n==$row["seqno"]) {
	    $menutext .= "<IMG SRC=\"$toppath/image/tri.gif\" WIDTH=12 HEIGHT=13>";
	  } else {
	    $menutext .= "&nbsp;";
	  }
	  $menutext .= "</TD>";
		// 内容
		$menutext .= "<TD VALIGN=TOP ALIGN=LEFT NOWRAP>";
		if ($row["priority"]==1) {
			$menutext .= "<FONT COLOR=#FF0000>●</FONT>";
		} elseif($row["priority"]==2) {
			$menutext .= "<FONT COLOR=#FF6600>●</FONT>";
		} else {
			$menutext .= "<FONT COLOR=#FFEE00>●</FONT>";
		}

		$menutext .= "<A HREF=\"$toppath/todo/?n=".$row["seqno"]."\">";
		$l_subject = $row["subject"];
		if (trim($l_subject)=="") {
			$bodys = preg_split("\r\n",$row["body"]);
			$l_subject = $bodys[0];
		}
		$l_subject = str_cut($l_subject,23);
		
		$menutext .= $l_subject."</A>";

#		if ($row["updatestamp"]!="") {
#			$menutext .= "(";
#			$menutext .= date("n/j",datetime2timestamp($row["updatestamp"]))." ".date("H:i",datetime2timestamp($row["updatestamp"]));;
#			$menutext .= ")";
#		}

		$menutext .= "</TD></TR>\n";
	}
	$menutext .= "</TABLE>\n";
}
$menutext .= "</TD></TR></TABLE>
</TD></TR></TABLE>

<BR>
";
?>