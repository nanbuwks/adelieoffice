<?php
  // ディレクトリツリー表示のコントロール用(SESSIONを利用)
  $pathon  = stripslashes(urldecode($_GET["pathon"]));
  $pathoff = stripslashes(urldecode($_GET["pathoff"]));

  if (!empty($pathon)) {
    $_SESSION[$pathon] = "on";
  } elseif (!empty($pathoff)) {
    $_SESSION[$pathoff] = "off";
  }
  if (!empty($relpath) && empty($pathon) && empty($pathoff)) {
    if ($_SESSION[$relpath] != "on") {
      $_SESSION[$relpath] = "on";
    }
  }
  if (empty($_SESSION["/"])) {
    $_SESSION["/"] = "on";
  }

  $menutext .= "
<TABLE><FORM ACTION=\"./search/\" METHOD=POST>
<TR>
<TD><IMG SRC=\"$toppath/image/search.gif\" WIDTH=16 HEIGHT=16 BORDER=0 ALT=\"検索\" ALIGN=ABSMIDDLE><INPUT TYPE=HIDDEN NAME=\"search\" VALUE=\"t\"><INPUT TYPE=TEXT NAME=\"kwd\" VALUE=\"".view_textsafe($folder_kwd)."\" SIZE=15 STYLE=\"width:98px\"><INPUT TYPE=SUBMIT VALUE=\"検索\" STYLE=\"width:36px\">
</TD></TR></FORM></TABLE>

<CENTER>
<TABLE CELLPADDING=1 CELLSPACING=0 BORDER=0 WIDTH=160 BGCOLOR=#666666><TR><TD>
<TABLE CELLPADDING=4 CELLSPACING=0 BORDER=0 WIDTH=158 BGCOLOR=#666666>
<TR><TD VALIGN=TOP BGCOLOR=#999999><FONT COLOR=#FFFFFF><A HREF=\"$toppath/folder/\" STYLE=\"color:#FFFFFF\"><IMG SRC=\"$toppath/image/foldersadd.gif\" ALIGN=ABSMIDDLE BORDER=0 ALT=\"フォルダ\"> フォルダリスト</A>
</TD></TR>
<TR><TD STYLE=\"line-height:13px;font-size:8pt\" BGCOLOR=#FFFFFF ALIGN=LEFT>
";

// ディレクトリリストチェック
function check_tree($dirname) {
#echo $level.":".$dirname."<BR>";
  global $conn,$groups,$admin_sign,$login_id,$folderpath,$relpath,$view_order;
  $viewpath = $folderpath.$dirname;

  if (!opendir($viewpath)) {
    print "<CENTER>Webフォルダ保存用ディレクトリの準備が完了していません。<BR>フォルダが作成されパーミッションが正しいか確認してください。</CENTER>";
  exit;
  }
  if (!file_exists($viewpath)) {
    return "0/0";
  }
  $handle = opendir($viewpath);
  // 獲得
  $dirs = 0;
  $files = 0;
  while ($file = readdir($handle)) { 
    if ($file != "." && $file != "..") { 
      if (is_dir($viewpath."/".$file)) {
        if ($admin_sign=="t" || $admin_sign!="t" && $file != "trash") {
          if ($admin_sign != "t") {
            if ($dirname=="/") {
              $searchpath = $dirname.$file;
            } else {
              $searchpath = $dirname."/".$file;
            }
            $sql = "SELECT view_group_ids,view_user_ids FROM folders WHERE path='".$searchpath."'";
            $res = pg_query($conn,$sql);
            $cnt = pg_num_rows($res);
            if ($cnt>0) {
              $view = false;
              $row = pg_fetch_array($res,0);
              if ($row["view_group_ids"]=="" && $row["view_user_ids"]=="") {
                $view = true;
              } else {
                if ($row["view_group_ids"]!="") {
                  $view_group_id = preg_split(",",$row["view_group_ids"]);
                  if (sizeof($view_group_id)>0) while (list($seq,$id)=each($view_group_id)) {
                    if (sizeof($groups)>0) {
                      reset($groups);
                      while (list($gseq,$gid)=each($groups)) {
                        if ($gid==$id) { $view = true; break; }
                      }
                    }
                  }
                }
                if ($row["view_user_ids"]!="") {
                  $view_user_id = preg_split(",",$row["view_user_ids"]);
                  if (sizeof($view_user_id)>0) while (list($seq,$id)=each($view_user_id)) {
                    if ($login_id==$id) { $view = true; break; }
                  }
                }
              }
              if (!$view) {
                continue;
              }
            }
          }
          $dirs++;
        }
      }
      if (is_file($viewpath."/".$file)) {
        $files++;
      }
    }
  }
  return $dirs."/".$files;
}

// ディレクトリリスト表示
function echo_tree($dirname,$level,$tree,$last) {
#echo $level.":".$dirname."<BR>";
  global $conn,$groups,$login_id,$admin_sign;
  global $folderpath,$relpath,$vieworder;
  $s = "";

  $viewpath = $folderpath.$dirname;
  if (!file_exists($viewpath)) {
    return "";
  }
  $handle = opendir($viewpath);
  // 獲得
  while ($file = readdir($handle)) { 
    if ($file != "." && $file != "..") { 
      if ($admin_sign=="t" || $admin_sign!="t" && $file != "trash") {
        $filename = $file;
        $value = basename($viewpath."/".$file);
        if (is_dir($viewpath."/".$file)) {
          //
          if ($admin_sign != "t") {
            if ($dirname=="/") {
              $searchpath = $dirname.$file;
            } else {
              $searchpath = $dirname."/".$file;
            }
            $sql = "SELECT view_group_ids,view_user_ids FROM folders WHERE path='".$searchpath."'";
            $res = pg_query($conn,$sql);
            $cnt = pg_num_rows($res);
            if ($cnt>0) {
              $view = false;
              $row = pg_fetch_array($res,0);
              if ($row["view_group_ids"]=="" && $row["view_user_ids"]=="") {
                $view = true;
              } else {
                if ($row["view_group_ids"]!="") {
                  $view_group_id = preg_split(",",$row["view_group_ids"]);
                  if (sizeof($view_group_id)>0) while (list($seq,$id)=each($view_group_id)) {
                    if (sizeof($groups)>0) {
                      reset($groups);
                      while (list($gseq,$gid)=each($groups)) {
                        if ($gid==$id) { $view = true; break; }
                      }
                    }
                  }
                }
                if ($row["view_user_ids"]!="") {
                  $view_user_id = preg_split(",",$row["view_user_ids"]);
                  if (sizeof($view_user_id)>0) while (list($seq,$id)=each($view_user_id)) {
                    if ($login_id==$id) { $view = true; break; }
                  }
                }
              }
              if (!$view) {
                continue;
              }
            }
          }

          //
          $dirs[$filename] = $value;
        }
      }
    }
  }
  // ソート
  if (sizeof($dirs)>0) {
#    if ($vieworder=="asc") {
      asort($dirs);
#    } else {
#      arsort($dirs);
#    }

    // 出力
    $c = 0;
    while (list($key,$val)=each($dirs)) { 
      $c++;
      if (substr($dirname,-1,1)!="/") {
        $subdirname = $dirname."/".$key;
      } else {
        $subdirname = $dirname.$key;
      }

      // 表示チェック
      if ($admin_sign != "t") {
        $sql = "SELECT view_group_ids,view_user_ids FROM folders WHERE path='".$subdirname."'";
        $res = pg_query($conn,$sql);
        $cnt = pg_num_rows($res);
        if ($cnt>0) {
          $view = false;
          $row = pg_fetch_array($res,0);
          if ($row["view_group_ids"]=="" && $row["view_user_ids"]=="") {
            $view = true;
          } else {
            if ($row["view_group_ids"]!="") {
              $view_group_id = preg_split(",",$row["view_group_ids"]);
              if (sizeof($view_group_id)>0) while (list($seq,$id)=each($view_group_id)) {
                if (sizeof($groups)>0) {
                  reset($groups);
                  while (list($gseq,$gid)=each($groups)) {
                    if ($gid==$id) { $view = true; break; }
                  }
                }
              }
            }
            if ($row["view_user_ids"]!="") {
              $view_user_id = preg_split(",",$row["view_user_ids"]);
              if (sizeof($view_user_id)>0) while (list($seq,$id)=each($view_user_id)) {
                if ($login_id==$id) { $view = true; break; }
              }
            }
          }
          if (!$view) continue;
        }
      }


      $ret = check_tree($subdirname);
      $rets = preg_split("/",$ret);
      $dircount = $rets[0];
      $filecount = $rets[1];

      for ($i=0;$i<strlen($tree)-1;$i++) {
        if (substr($tree,$i,1)=="t") {
          $s .= "<IMG SRC=\"$toppath/image/icon/fol2.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
        } else {
          $s .= "<IMG SRC=\"$toppath/image/icon/folnull.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
        }
      }
      if (sizeof($dirs)==$c) {
#      if ($last==true) {
        $s .= "<IMG SRC=\"$toppath/image/icon/fol4.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
      } else {
        $s .= "<IMG SRC=\"$toppath/image/icon/fol3.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
      }

      if ($dircount>0) {
        if ($_SESSION[$subdirname]=="on") {
          $s .= "<A HREF=\"$toppath/folder/?pathoff=".rawurlencode($subdirname)."\">";
          $s .= "<IMG SRC=\"$toppath/image/icon/folminus.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
        } else {
          $s .= "<A HREF=\"$toppath/folder/?pathon=".rawurlencode($subdirname)."\">";
          $s .= "<IMG SRC=\"$toppath/image/icon/folplus.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
        }
        $s .= "</A>";
      } else {
#        $s .= "<IMG SRC=\"$toppath/image/icon/fol1.gif\" WIDTH=5 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
      }

      if ($filecount>0) {
        $dirshortname = mb_strcut(textsafe($key),0,23-floor(($level+1)*2.5));
      } else {
        $dirshortname = mb_strcut(textsafe($key),0,26-floor(($level+1)*2.5));
      }

      if ($relpath != $subdirname) {
        $s .= "<A HREF=\"$toppath/folder/?relpath=".urlencode($subdirname)."\">".$dirshortname."</A>";
      } else {
        $s .= "<B>".$dirshortname."</B>";
      }
      if ($filecount>0) {
        $s .= "(".$filecount.")";
      }

      $s .= "<BR>";

      if ($_SESSION[$subdirname]=="on") {
        if (sizeof($dirs)==$c) {
          $tree = substr($tree,0,strlen($tree)-1)."f";
        }
        if ($dircount>0) {
          if (sizeof($dirs)==$c) {
            $s .= echo_tree($subdirname,$level+1,$tree."t",true);
          } else {
            $s .= echo_tree($subdirname,$level+1,$tree."t",false);
          }
        } else {
          if (sizeof($dirs)==$c) {
            $s .= echo_tree($subdirname,$level+1,$tree."f",true);
          } else {
            $s .= echo_tree($subdirname,$level+1,$tree."f",false);
          }
        }
      }
    }
  }
  return $s;
}

  // メニュー内容の生成
  $ret = check_tree("/");
  $rets = preg_split("/",$ret);
  $dircount = $rets[0];
  $filecount = $rets[1];

  if ($dircount>0) {
    if ($_SESSION["/"]=="on") {
      $menutext .= "<A HREF=\"$toppath/folder/?pathoff=".rawurlencode("/")."\">";
      $menutext .= "<IMG SRC=\"$toppath/image/icon/folminus.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
    } else {
      $menutext .= "<A HREF=\"$toppath/folder/?pathon=".rawurlencode("/")."\">";
      $menutext .= "<IMG SRC=\"$toppath/image/icon/folplus.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
    }
    $menutext .= "</A>";
  } else {
    $menutext .= "<IMG SRC=\"$toppath/image/icon/fol1.gif\" WIDTH=10 HEIGHT=13 ALIGN=ABSMIDDLE BORDER=0>";
  }
  if ($relpath != "/") {
    $menutext .= "<A HREF=\"$toppath/folder/?relpath=/\">/ [ルートフォルダ]</A>";
  } else {
    $menutext .= "<B>/</B> [ルートフォルダ]";
  }

  if ($filecount>0) {
    $menutext .= "(".$filecount.")";
  }

  $menutext .= "<BR>";
  if ($_SESSION["/"]=="on") {
    if ($dircount>0) {
      $menutext .= echo_tree("/",0,"t",true);
    } else {
      $menutext .= echo_tree("/",0,"f",true);
    }
  }

  $menutext .= "
</TD></TR></TABLE>
</TD></TR></TABLE>";

  ## アップロード・フォルダ作成ボタン表示
#  $menutext .= "<BR>";
#  $checkpath = $relpath;
#  if ($checkpath=="") $checkpath = "/";
#  if ($download_sign=="t") {
#    $menutext .= "<A HREF=\"$toppath/folder/upload/?path=".urlencode($checkpath)."\">";
#    $menutext .= "<IMG SRC=\"$toppath/image/filesadd.gif\" ALIGN=ABSMIDDLE BORDER=0 ALT=\"アップロード\"> アップロード";
#    $menutext .= "</A><BR><BR>";
#    $menutext .= "<A HREF=\"$toppath/folder/makefolder/?path=".urlencode($checkpath)."\">";
#    $menutext .= "<IMG SRC=\"$toppath/image/foldersadd.gif\" ALIGN=ABSMIDDLE BORDER=0 ALT=\"フォルダ作成\"> フォルダ作成";
#    $menutext .= "</A><BR>";
#  }
#  $menutext .= "</CENTER><BR>";
?>