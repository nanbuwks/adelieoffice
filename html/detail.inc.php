<?php
  // add isset by nanbuwks 20110622 and modify 20170328
  // if ($login && !isset($MES)) {
  if ($login) {
?>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% HEIGHT=420>
<TR WIDTH=100%>
<?php
		if (!empty($menutext)) {
?>
<TD WIDTH=170 VALIGN=TOP ALIGN=CENTER>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 WIDTH=170><TR>
<TD STYLE="line-height:130%"><?php echo $menutext; ?></TD></TR></TABLE></TD>
<TD WIDTH=1 HEIGHT=460 BGCOLOR=#999999 STYLE="width:1px;height:460px"></TD>

<TD VALIGN=TOP><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 WIDTH=100%><TR><TD STYLE="line-height:13px">
<?php
		} else {
?>
<TD WIDTH=100% VALIGN=TOP><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=4 WIDTH=100%><TR><TD STYLE="line-height:13px">
<?php
		}
    // 
    if (trim($pagetext)=="") $pagetext = "<FONT COLOR=#FFFFFF>This [content]page is blank</FONT>\n";
    print $pagetext;

    if (sizeof((array)preg_split("~\/~",$request_uri))>3) {
      print "<BR><DIV ALIGN=RIGHT><A HREF=\"javascript:history.back()\">&lt; 戻る &gt;</A>&nbsp;</DIV><BR>";
    }
?>
</TD></TR>
</TABLE>
</TD></TR>
</TABLE>
</TD></TR>
</TABLE>
<?php
  } else {
    // add isset by nanbuwks 20110622
    // if (sizeof($MES)>0) {
    if (!isset($MES)) {
      // エラー時
      print "<BR><BR><CENTER><FONT COLOR=#FF0000>";
      // エラーメッセージ出力
      while (list($key,$val)=each($MES)) {
        print $val."<BR>";
      }
      print "</CENTER>\n";
    }
  }
?>
