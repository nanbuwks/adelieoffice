<?php
/*
  lib.inc.php
*/
// add by nanbuwks 20110924
class ext2OldVariable{
// add by nanbuwks 20110921
  function get2OldVariable($name){
        global $$name;
        if ( !ISSET($_GET[$name]) )
                { $$name = ""; }
                else
                { $$name = $_GET[$name]; }
  }
// add by nanbuwks 20110922
  function post2OldVariable($name){
        global $$name;
        if ( !ISSET($_POST[$name]) )
                { $$name = ""; }
                else
                { $$name = $_POST[$name]; }
  }

  function getNameArray2OldVariable($hoge){
    foreach ( $hoge as $hage ) {
          $this->get2OldVariable($hage);
    }
  }
  function postNameArray2OldVariable($hoge){
    foreach ( $hoge as $hage ) {
          $this->post2OldVariable($hage);
    }
  }
}
// class ext2OldVariable end

function is_ascii($s) {
  $result = true;
  for ($i=0;$i<strlen($s);$i++) {
    $ASC = ord(substr($s,$i,1));
    if (($ASC<ord("0") or $ASC>ord("9")) && ($ASC<ord("a") or $ASC>ord("z")) && ($ASC<ord("A") or $ASC>ord("Z"))) {
      $result = false;
    }
  }
  return $result;
}

function is_tel($s) {
  $result = true;
  for ($i=0;$i<strlen($s);$i++) {
    $ASC = ord(substr($s,$i,1));
    if (( $ASC<ord("0") or $ASC>ord("9") ) and $ASC != ord("-")) {
      $result = false;
    }
  }
  return $result;
}

function is_email($s) {
  $result = false;
  if (preg_match("^[^@]+@[^.]+\..+",$s)) {
    $result = true;
  }
  return $result;
}

function is_zip($s) {
  $result = true;
  if (!preg_match("^[0-9]{3}-?[0-9]{0,4}$",$s)) {
    $result = false;
  }
  return $result;
}

function euc2sjis($str) {
  return mb_convert_encoding($str,"SJIS","EUC-JP");
}

function utf2sjis($str) {
  return mb_convert_encoding($str,"SJIS","UTF-8");
}

function jis2sjis($str) {
  return mb_convert_encoding($str,"SJIS","JIS");
}

function sjis2euc($str) {
  return mb_convert_encoding($str,"EUC-JP","SJIS");
}

function utf2euc($str) {
  return mb_convert_encoding($str,"EUC-JP","UTF-8");
}

function jis2euc($str) {
  return mb_convert_encoding($str,"EUC-JP","JIS");
}

function sjis2utf($str) {
  return mb_convert_encoding($str,"UTF-8","SJIS");
}

function euc2utf($str) {
  return mb_convert_encoding($str,"UTF-8","EUC-JP");
}

function jis2utf($str) {
  return mb_convert_encoding($str,"UTF-8","JIS");
}

function sjis2jis($str) {
  return mb_convert_encoding($str,"JIS","SJIS");
}

function euc2jis($str) {
  return mb_convert_encoding($str,"JIS","EUC-JP");
}

function utf2jis($str) {
  return mb_convert_encoding($str,"JIS","UTF-8");
}

// 入力されたテキストを安心な形に
function textsafe($str) {
  $res = trim($str);
  $res = stripslashes($res);
  $res = str_replace('"',"”",$res);
  $res = str_replace("<","＜",$res);
  $res = str_replace(">","＞",$res);
#  $res = str_replace("\\","¥",$res);
  $res = str_replace("'","&#39;",$res);
  return $res;
}

// 入力されたテキストをSQL文にしても安心な形に(EUC-JP限定)
function db_textsafe($str) {
  $res = trim($str);
  // modify by nanbuwks 20111221
//  $res = str_replace("\\",sjis2euc("¥"),$res);
//  $res = str_replace("\\","¥",$res);
  
//  $res = str_replace("'","&#39;",$res);
  return $res;
}
function wiki_textsafe($str) {
  $res = str_replace("<","&lt;",$str);
  $res = str_replace(">","&gt;",$res);
  return $res;
}

function view_textsafe($str) {
  $res = trim($str);
  $res = stripslashes($res);
  $res = str_replace("<","&lt;",$res);
  $res = str_replace(">","&gt;",$res);
  return $res;
}

function mail_textsafe($str) {
  $res = trim($str);
  $res = stripslashes($res);
  $res = str_replace("&lt;","<",$res);
  $res = str_replace("&gt;",">",$res);
  $res = str_replace("&amp;","&",$res);
  return $res;
}

function arrangetext($str,$separete) {
  $text = "";

  if ($separete=="" || $separete=="input") {
    $lines = preg_split("\n",preg_replace("/<BR>|\r\n|\r|\n/i","\n",$str));
    while(list($seq,$line)=each($lines)) {
      $s = $line;
      while (true) {
        if (strlen($s)>76) {
          $ss = mb_strcut($s,0,76);
          $s = substr($s,strlen($ss));
        } else {
          $ss = $s;
          $s = "";
        }

        $ss = str_highlight($kwd,$ss);
        $ss = str_link(preg_replace("/\s\s/i"," &nbsp;",$ss));
        $text .= $ss."\n";

        if ($s=="") break;
      }
    }
  } elseif ($separete=="board") {
    $lines = preg_split("\n",preg_replace("/<BR>|\r\n|\r|\n/i","\n",$str));
    while(list($seq,$line)=each($lines)) {
      $s = $line;
      while (true) {
        if (strlen($s)>76) {
          $ss = mb_strcut($s,0,76);
          $s = substr($s,strlen($ss));
        } else {
          $ss = $s;
          $s = "";
        }

        $ss = str_highlight($kwd,$ss);
        $ss = str_link(preg_replace("/\s\s/i"," &nbsp;",$ss));
        $text .= $ss."<BR>";

        if ($s=="") break;
      }
    }
  }
  return $text;
}

// 実験中:入力されたテキストを縦書きに(EUC-JP専用)
function str_tate($str) {
  $ret = "";
  for($i=0;$i<mb_strlen($str);$i++) {
    $s = mb_substr($str,$i,1,"EUC-JP");
    if     ($s=="("||$s=="[") { $ret .= sjis2euc("・"); }
    elseif ($s==")"||$s=="]") { $ret .= ""; }
    elseif ($s=="-"||$s==sjis2euc("ー")) { $ret .= "｜"; }
    else             { $ret .= $s;   }
    if ($s != "") { $ret .= "<BR>"; }
  }
  return $ret;
}

function make_serial($length) {
  $s = "";
  for($i=0;$i<20;$i++) {
    mt_srand((double)microtime()*1000000);
    $r = mt_rand(1,72);
    if (($r>=1) and ($r<=26)) {
      $s .= chr(ord("a")+($r-1));
    } elseif (($r>=27) and ($r<=52)) {
      $s .= chr(ord("A")+($r-27));
    } elseif (($r>=53) and ($r<=62)) {
      $s .= chr(ord("0")+($r-53));
    } elseif($r==63) { $s .= "$";
    } elseif($r==64) { $s .= ",";
    } elseif($r==65) { $s .= ";";
    } elseif($r==66) { $s .= ":";
    } elseif($r==67) { $s .= "!";
    } elseif($r==68) { $s .= "*";
    } elseif($r==69) { $s .= "~";
    } elseif($r==71) { $s .= "_";
    } elseif($r==72) { $s .= "-";
    }
  }
  return $s;
}

// 全角スペースも含めたtrim(但し全角スペースも半角に...)
function mbtrim($str) {
  $res = ereg_replace("　", " ", $str);
  return trim($res);
}

/* 文字列整形 */
function str_cut($s,$width) {
  $outtext = "";
  if (sizeof($s) > 1 or strlen($s) > $width) {
    for ($i=0;$i<mbstrlen($s);$i++) {
      $outtext .= mbsubstr($s,$i,1);
      if (strlen($outtext)>=$width-2) {
        break;
      }
    }
    return $outtext.".";
  } else {
    return $s;
  }
}
function str_rcut($s,$width) {
  $outtext = "";
  if (sizeof($s) > 1 or strlen($s) > $width) {
    $i = mbstrlen($s);
    while($i>0) {
      $outtext = mbsubstr($s,$i,1).$outtext;
      if (strlen($outtext)>$width-2) {
        break;
      }
      $i--;
    }
    return ".".$outtext;
  } else {
    return $s;
  }
}

/* 日付変換 DATETIME型 => TIMESTAMP型 */
function datetime2timestamp($dt) {
//  $w    = preg_split(" ",$dt);
//  $wYMD = preg_split("-",$w[0]);
//  if (sizeof($wYMD)==1) {
//    $wYMD = preg_split("/",$w[0]);
//  }
//  $wDST = preg_split("\+",$w[1]);
//  $wHMS = preg_split(":",$wDST[0]);
//  return mktime($wHMS[0], $wHMS[1], $wHMS[2], $wYMD[1], $wYMD[2], $wYMD[0]);
return  strtotime(date($dt));
}

/* 日付変換 DATE型 => TIMESTAMP型 */
function date2timestamp($dt) {
//  2011/06/21 modify by nanbuwks
//  $wYMD = preg_split("-",$dt);
  $wYMD = preg_preg_split("/[-\/:\s]/",$dt);
//  if (sizeof($wYMD)==1) {
//    $wYMD = preg_split("/",$dt);
//  }

  return mktime(0, 0, 0, $wYMD[1], $wYMD[2], $wYMD[0]);
}

/* 日付変換 TIMESTAMP型 => DATETIME型*/
function timestamp2datetime($ts) {
// comment out by nanbuwks 20110621
//  $w    = preg_split(" ",$dt);
  $wY = date("Y", $ts);
  $wM = date("m", $ts);
  $wD = date("d", $ts);
  $wH = date("H", $ts);
  $wN = date("i", $ts);
  $wS = date("s", $ts);

  return $wY."/".$wM."/".$wD." ".$wH.":".$wN.":".$wS;
}

// Wiki風表示関数(実験中)
function str_wiki($str) {
	global $td_back_left,$td_back;
	//事前整形
#	$str = str_replace("’","'",$str);
#	$str = str_replace("&amp;","&",$str);
#	$str = str_replace("&lt;","<",$str);
#	$str = str_replace("&gt;",">",$str);

	$str = preg_replace("/\r\n|\n|\r|<BR>/i","\n",$str);
	
	//配列展開
	$arrstr = preg_split("\n",$str);
	$rs = "";

	//変数
	$istable = false;
	$istablerow = false;
	$istablecell = false;

	$ul_level = 0;
	$ol_level = 0;
	
	while(list($seq,$s)=each($arrstr)) {
		$nobr = false;
		//<HR> is -----
		if (preg_match("/\-\-\-\-\-+/",$s,$res)) {
			$s = preg_replace("/\-\-\-\-\-+/","<HR>",$s);
			$nobr = true;
		}
		
		//**...** is <B>
	  while (true) {
			if (!preg_match("/(.*)\*\*(.+?)\*\*(.*)/",$s,$res)) break;
			$s = $res[1]."<b>".$res[2]."</b>".$res[3];
		}
		
		//__...__ is <u>
	  while (true) {
			if (!preg_match("/(.*)__(.+?)__(.*)/",$s,$res)) break;
			$s = $res[1]."<u>".$res[2]."</u>".$res[3];
		}
		//''...'' is <i>
	  while (true) {
			if (!preg_match("/(.*)''(.+?)''(.*)/",$s,$res)) break;
			$s = $res[1]."<i>".$res[2]."</i>".$res[3];
		}

		//::...:: is <center>
	  while (true) {
			if (!preg_match("/(.*)::(.+?)::(.*)/",$s,$res)) break;
			$s = $res[1]."<center>".$res[2]."</center>".$res[3];
			$nobr = true;
		}

		//<<...<< is <div align=left>
	  while (true) {
			if (!preg_match("/(.*)&lt;&lt;(.+?)&lt;&lt;(.*)/",$s,$res)) break;
			$s = $res[1]."<div align=left>".$res[2]."</div>".$res[3];
			$nobr = true;
		}

		//>>...>> is <div align=right>
	  while (true) {
			if (!preg_match("/(.*)&gt;&gt;(.+?)&gt;&gt;(.*)/",$s,$res)) break;
			$s = $res[1]."<div align=right>".$res[2]."</div>".$res[3];
			$nobr = true;
		}

		//-+...+- is <TT>
	  while (true) {
			if (!preg_match("/(.*)\-\+(.+?)\+\-(.*)/",$s,$res)) break;
			$s = $res[1]."<tt>".$res[2]."</tt>".$res[3];
		}
		
		//~~...~~ is <FONT COLOR=>
	  while (true) {
			if (!preg_match("/(.*)~~(.+?)~~(.*)/",$s,$res)) break;
			$arrtxt = preg_split(":|\||,",$res[2],2);
			if (sizeof($arrtxt)==2) {
				$s = $res[1]."<FONT COLOR=".$arrtxt[0].">".$arrtxt[1]."</FONT>".$res[3];
			} else {
				$s = $res[1]."<FONT>".$res[2]."</FONT>".$res[3];
			}
		}

		//^...^ is BOX
	  while (true) {
			if (!preg_match("/(.*)\^(.+?)\^(.*)/",$s,$res)) break;

			$arrtxt = preg_split(":",$res[2],4);
			if (sizeof($arrtxt)==4) {
				$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=".$arrtxt[0]." WIDTH=".$arrtxt[2]."><tr><td BGCOLOR=".$arrtxt[1].">".$arrtxt[3]."</td></tr></table>".$res[3];
			} elseif (sizeof($arrtxt)==3) {
				$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=".$arrtxt[0]."><tr><td BGCOLOR=".$arrtxt[1].">".$arrtxt[2]."</td></tr></table>".$res[3];
			} elseif (sizeof($arrtxt)==2) {
				$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=".$arrtxt[0]."><tr><td BGCOLOR=".$arrtxt[0].">".$arrtxt[1]."</td></tr></table>".$res[3];
			} else {
				$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=#999999><tr><td BGCOLOR=$td_back>".$res[2]."</td></tr></table>".$res[3];
			}
			$nobr = true;
		}
	  while (true) {
			if (!preg_match("/(.*)&#710;(.+?)&#710;(.*)/",$s,$res)) break;
			$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=#999999><tr><td BGCOLOR=$td_back>".$res[2]."</td></tr></table>".$res[3];
			$nobr = true;
		}
		
		//-=...=- is Title
	  while (true) {
			if (!preg_match("/(.*)\-=(.+?)=\-(.*)/",$s,$res)) break;
			$s = $res[1]."<font style=\"font-size:130%;font-weight:bold\">".$res[2]."</font>".$res[3];
#			$nobr = true;
		}
		
		//{...} is image
	  while (true) {
			if (!preg_match("/(.*)\{(.+?)\}(.*)/",$s,$res)) break;
			$arrtxt = preg_split("\|",$res[2],6);
			if (sizeof($arrtxt)==1) {
				$s = $res[1]."<img src=\"".$res[2]."\">".$res[3];
			} elseif (sizeof($arrtxt)==2) {
				$s = $res[1]."<img src=\"".$arrtxt[0]."\" ALT=\"".$arrtxt[1]."\">".$res[3];
			} elseif (sizeof($arrtxt)==4) {
				$s = $res[1]."<img src=\"".$arrtxt[0]."\" ALT=\"".$arrtxt[1]."\" WIDTH=".$arrtxt[2]." HEIGHT=".$arrtxt[3].">".$res[3];
			} elseif (sizeof($arrtxt)==6) {
				$s = $res[1]."<img src=\"".$arrtxt[0]."\" ALT=\"".$arrtxt[1]."\" WIDTH=".$arrtxt[2]." HEIGHT=".$arrtxt[3]." ALIGN=".$arrtxt[4]." VALIGN=".$arrtxt[5].">".$res[3];
			} else {
				$s = $res[1]."<img src=\"".$arrtxt[0]."\" ALT=\"".$arrtxt[1]."\">".$res[3];
			}
#			$nobr = true;
		}
		
		//-> <-(-|) is <blockquote>
	  while (true) {
			if (!preg_match("/(.*)[\-]+&gt;(.*)/",$s,$res)) break;
			$s = $res[1]."<blockquote>".$res[2];
			$nobr = true;
		}
	  while (true) {
			if (!preg_match("/(.*)&lt;[\-]+(.*)/",$s,$res)) break;
			$s = $res[1]."</blockquote>".$res[2];
			$nobr = true;
		}
	  while (true) {
			if (!preg_match("/(.*)[\-]+\|(.*)/",$s,$res)) break;
			$s = $res[1]."</blockquote>".$res[2];
			$nobr = true;
		}

		// --- is <ul>
		// +++ is <ol>

		$is_ul = preg_match("/^(\-{1,4})(.*)/",$s,$ul_res);
		if (!$is_ul) {
			if ($ul_level>0) {
				for($i=0;$i<$ul_level;$i++) {
					$s = "</ul>".$s;
				}
			}
			$ul_level = 0;
		}
		if ($is_ul) {
			$s = "";
			$level = strlen($ul_res[1]);
			if ($level>$ul_level) {
				for($i=$ul_level;$i<$level;$i++) {
					$s .= "<ul>";
				}
			}
			if ($level<$ul_level) {
				for($i=$level;$i<$ul_level;$i++) {
					$s .= "</ul>";
				}
			}
			$s .= "<li>".$ul_res[2]."</li>";
			$ul_level = $level;
			$nobr = true;
		}

		$is_ol = preg_match("/^(\+{1,4})(.*)/",$s,$ol_res);
		if (!$is_ol) {
			if ($ol_level>0) {
				for($i=0;$i<$ol_level;$i++) {
					$s = "</ol>".$s;
				}
			}
			$ol_level = 0;
		}
		if ($is_ol) {
			$s = "";
			$level = strlen($ol_res[1]);
			if ($level>$ol_level) {
				for($i=$ol_level;$i<$level;$i++) {
					$s .= "<ol>";
				}
			}
			if ($level<$ol_level) {
				for($i=$level;$i<$ol_level;$i++) {
					$s .= "</ol>";
				}
			}
			$s .= "<li>".$ol_res[2]."</li>";
			$ol_level = $level;
			$nobr = true;
		}

		//[[...]] is HTML direct
	  while (true) {
			if (!preg_match("/(.*)\[\[(.+?)\]\](.*)/",$s,$res)) break;
			$s = str_replace("&lt;","<",$res[2]);
			$s = str_replace("&gt;",">",$s);
			$s = $res[1].$s.$res[3];
		}
		
		//[(...)] is New Link
	  while (true) {
			if (!preg_match("/(.*)\[\((.+?)\)\](.*)/",$s,$res)) break;
			$arrtxt = preg_split("\|",$res[2],2);
			if (sizeof($arrtxt)==2) {
				$s = $res[1]."<a href=".$arrtxt[0]." target=\"_blank\">".$arrtxt[1]."</a>".$res[3];
			} else {
				$s = $res[1]."<a href=\"".$res[2]."\" target=\"_blank\">".$res[2]."</a>".$res[3];
			}
		}

		//[...] is Link
	  while (true) {
			if (!preg_match("/(.*)\[(.+?)\](.*)/",$s,$res)) break;
			$arrtxt = preg_split("\|",$res[2],2);
			if (sizeof($arrtxt)==2) {
				$s = $res[1]."<a href=".$arrtxt[0].">".$arrtxt[1]."</a>".$res[3];
			} else {
				$s = $res[1]."<a href=\"".$res[2]."\">".$res[2]."</a>".$res[3];
			}
		}
		
		//<tr>〜</tr>
		if ($istable && !preg_match("/(.*)\|\|\|(.*)/",$s,$res)) {
			$arrtxt = preg_split("\|",$s);
			if (sizeof($arrtxt)>0) {
				$s = "<tr>";
				while(list($colno,$coltext)=each($arrtxt)) {


					if (trim($coltext)!="") {
						$arrtext = preg_preg_split("/:/i",$coltext,2);
						if (sizeof($arrtext)==2 && (!preg_match("/^\/\//",$arrtext[1]))) {
							$s .= "<td BGCOLOR=".$arrtext[0].">";
							$s .= $arrtext[1];
						} else {
							$s .= "<td BGCOLOR=$td_back>";
							$s .= $coltext;
						}
						$s .= "</td>";
					}


#					if (trim($coltext)!="") {
#						$s .= "<td BGCOLOR=$td_back>";
#						$s .= $coltext;
#						$s .= "</td>";
#					}
				}
				$s .= "</tr>";
			}
			$nobr = true;
		}

	  while (true) {
			//||| is <table> or </table>
			if (!preg_match("/(.*)\|\|\|(.*)/",$s,$res)) break;
			if ($istable) {
				$istable = false;
				$s = $res[1]."</table>".$res[2];
			} else {
				$istable = true;
				$s = $res[1]."<table BORDER=0 CELLPADDING=3 CELLSAPCING=1 BGCOLOR=#999999>".$res[2];
			}
			$nobr = true;
		}

		if ($nobr) {
			$rs .= $s;
		} else {
			$rs .= $s."\n";
		}
	}

	$rs = str_replace("\n","<BR>\n",$rs);
	return $rs;
}

// URLやメールアドレスをリンク化する
function str_link($s) {
  global $mydomain;

  $p = 0;
  $s2 = "";
  while (true) {
    if (preg_match("s?https?://[-_.!~*'()a-zA-Z0-9;/?:@&=+$%#]+",substr($s,$p),$reg)>0) {
      // ターゲットを含んだ行があった
      $p2 = strpos(" ".substr($s,$p),$reg[0]);
      $s2 .= substr($s,$p,$p2-1);
      if ($mydomain != "" && strpos($reg[0],$mydomain)>0) {
        $s2 .= "<A HREF=\"".$reg[0]."\">".$reg[0]."</A>";
      } else {
        $s2 .= "<A HREF=\"".$reg[0]."\" target=\"_blank\">".$reg[0]."</A>";
      }
      $p = $p + $p2 + strlen($reg[0])-1;
    } else {
      // ターゲットを含んだ行はもう無い
      $s2 .= substr($s,$p);
      $s = $s2;
      break;
    }
  }

  // メールアドレスをリンク化
  $p = 0;
  $s2 = "";
  while (true) {
    if (preg_match("[-_.a-zA-Z0-9]+@[-_.a-zA-Z0-9]+\.[-_.a-zA-Z]+",substr($s,$p),$reg)>0) {
      // ターゲットを含んだ行があった
      $p2 = strpos(" ".substr($s,$p),$reg[0]);
      $s2 .= substr($s,$p,$p2-1);
      $s2 .= "<A HREF=\"mailto:".$reg[0]."\">".$reg[0]."</A>";
      $p = $p + $p2 + strlen($reg[0])-1;
    } else {
      // ターゲットを含んだ行はもう無い
      $s2 .= substr($s,$p);
      $s = $s2;
      break;
    }
  }
  return $s2;
}

// 携帯用
function str_link_tel($s) {
  $p = 0;
  $s2 = "";
  while (true) {
    if (mb_preg_match("[0-9][0-9]+\-[0-9][0-9]+\-[0-9][0-9][0-9][0-9]+",substr($s,$p),$reg)>0) {
      $p2 = strpos(" ".substr($s,$p),$reg[0]);
      $s2 .= substr($s,$p,$p2-1);
      $s2 .= "<A HREF=\"tel:".$reg[0]."\"\">".$reg[0]."</A>";
      $p = $p + $p2 + strlen($reg[0])-1;
    } else {
      $s2 .= substr($s,$p);
      $s = $s2;
      break;
    }
  }
  return $s2;
}

// 検索文字列をハイライト表示にする
function str_highlight($search,$subject) {
  $searchs = preg_split(" ",textsafe($search));

  $res = $subject;

  if (sizeof($searchs)>0) {
    while (list($key,$val)=each($searchs)) { 
      $val = str_replace("SPAN","",$val);
      $val = str_replace("CLASS","",$val);
      $val = str_replace("HIGHLIGHT","",$val);
      $val = str_replace("=","",$val);
      $val = str_replace("/","",$val);
      if ($val != "" && strlen($val)>1) {
        $res = str_replace($val,"<SPAN CLASS=HIGHLIGHT>".$val."</SPAN>",$res);
      }
    }
  }
  return $res;
}

function ext_check($filename) {
  $p = strrpos($filename,".");
  $path = "/images/";
  if ($p>0) {
    ## OS系一般
    if (preg_match("/\.txt$/i",$filename)) {
      return $path."txt.gif";
    } elseif (preg_match("/\.log$/i",$filename)) {
      return $path."log.gif";
    } elseif (preg_match("/\.csv$/i",$filename)) {
      return $path."csv.gif";
    ### 一般的な画像
    } elseif (preg_match("/(\.jpg|\.jpe|\.jpeg)$/i",$filename)) {
      return $path."jpeg.gif";
    } elseif (preg_match("/\.gif$/i",$filename)) {
      return $path."gif.gif";
    } elseif (preg_match("/(\.jp2|\.j2c|\.j2k|\.jpx)$/i",$filename)) {
      return $path."jpeg.gif";
    } elseif (preg_match("/\.png$/i",$filename)) {
      return $path."png.gif";
    } elseif (preg_match("/\.bmp$/i",$filename)) {
      return $path."bmp.gif";
    } elseif (preg_match("/\.psd$/i",$filename)) {
      return $path."psd.gif";
    } elseif (preg_match("/\.tif*$/i",$filename)) {
      return $path."tiff.gif";
    } elseif (preg_match("/\.pdf$/i",$filename)) { // PDF
      return $path."pdf.gif";
    ## 一般的なMicrosoft-Officeファイル
    } elseif (preg_match("/\.doc$/i",$filename)) { // MS-Word
      return $path."doc.gif";
    } elseif (preg_match("/\.xls$/i",$filename)) { // MS-Excel
      return $path."xls.gif";
    } elseif (preg_match("/\.ppt$/i",$filename)) { // MS-PowerPoint
      return $path."ppt.gif";
    } elseif (preg_match("/\.mdb$/i",$filename)) { // MS-Access
      return $path."mdb.gif";
    ## HTML関連
    } elseif (preg_match("/(\.html|\.htm)$/i",$filename)) {  // HTML
      return $path."html.gif";
    } elseif (preg_match("/\.shtml$/i",$filename)) { // SHTML
      return $path."shtml.gif";
    } elseif (preg_match("/\.php$/i",$filename)) {   // PHP
      return $path."php.gif";
    } elseif (preg_match("/\.php3$/i",$filename)) {  // PHP3
      return $path."php3.gif";
    } elseif (preg_match("/\.php4$/i",$filename)) {  // PHP4
      return $path."php4.gif";
    } elseif (preg_match("/\.phtml$/i",$filename)) { // PHTML
      return $path."phtml.gif";
    } elseif (preg_match("/\.pl$/i",$filename)) {    // PL
      return $path."pl.gif";
    } elseif (preg_match("/\.cgi$/i",$filename)) {   // CGI
      return $path."cgi.gif";
    ## 一般的な動画ファイル
    } elseif (preg_match("/\.avi$/i",$filename)) {
      return $path."avi.gif";
    } elseif (preg_match("/(\.mpg$|\.mpe$|\.mpeg)$/i",$filename)) {
      return $path."mpeg.gif";
    } elseif (preg_match("/\.mp2$/i",$filename)) {
      return $path."mp2.gif";
    } elseif (preg_match("/\.mov$/i",$filename)) {
      return $path."mov.gif";
    } elseif (preg_match("/\.qt$/i",$filename)) {
      return $path."qt.gif";
    ## 一般的な動画ファイル(Flash関連)
    } elseif (preg_match("/\.fla$/i",$filename)) {
      return $path."fla.gif";
    } elseif (preg_match("/\.swf$/i",$filename)) {
      return $path."swf.gif";
    ## 一般的な音声ファイル
    } elseif (preg_match("/\.mp3$/i",$filename)) {
      return $path."mp3.gif";
    } elseif (preg_match("/(\.aif$|\.aifc$|\.aiff)$/i",$filename)) {
      return $path."wav.gif";
    } elseif (preg_match("/(\.wav$|\.wave)$/i",$filename)) {
      return $path."wav.gif";
    ## その他の音声ファイル
    } elseif (preg_match("/\.au$/i",$filename)) {
      return $path."au.gif";
    } elseif (preg_match("/\.mid$/i",$filename)) {
      return $path."mid.gif";
    } elseif (preg_match("/\.midi$/i",$filename)) {
      return $path."midi.gif";
    } elseif (preg_match("/\.snd$/i",$filename)) {
      return $path."snd.gif";
    ## その他の動画ファイル
    } elseif (preg_match("/\.mpa$/i",$filename)) {
      return $path."mpa.gif";
    } elseif (preg_match("/\.m1v$/i",$filename)) {
      return $path."m1v.gif";
    } elseif (preg_match("/\.dvi$/i",$filename)) {
      return $path."dvi.gif";
    ## Flash関連
    } elseif (preg_match("/\.spa$/i",$filename)) {
      return $path."spa.gif";
    } elseif (preg_match("/\.ssk$/i",$filename)) {
      return $path."ssk.gif";
    } elseif (preg_match("/\.swt$/i",$filename)) {
      return $path."swt.gif";
    } elseif (preg_match("/\.spl$/i",$filename)) {
      return $path."spl.gif";
    } elseif (preg_match("/\.fh5$/i",$filename)) {
      return $path."fh5.gif";
    } elseif (preg_match("/\.fh7$/i",$filename)) {
      return $path."fh7.gif";
    } elseif (preg_match("/\.iff$/i",$filename)) {
      return $path."iff.gif";
    } elseif (preg_match("/\.ps$/i",$filename)) {
      return $path."ps.gif";
    } elseif (preg_match("/\.ai$/i",$filename)) {
      return $path."ai.gif";
    } elseif (preg_match("/\.cgm$/i",$filename)) { // Illustrator(cgm)
      return $path."cgm.gif";
    } elseif (preg_match("/\.cdr$/i",$filename)) { // Illustrator(cdr)
      return $path."cdr.gif";
    } elseif (preg_match("/\.dxf$/i",$filename)) { // Illustrator(dxf)
      return $path."dxf.gif";
    } elseif (preg_match("/\.dwg$/i",$filename)) { // Illustrator(dwg)
      return $path."dwg.gif";
    } elseif (preg_match("/\.emf$/i",$filename)) { // Illustrator(emf)
      return $path."emf.gif";
    } elseif (preg_match("/(\.eps$|\.epsf)$/i",$filename)) { // Illustrator(eps)
      return $path."eps.gif";
    } elseif (preg_match("/(\.fh4$|\.fh8)$/i",$filename)) { // Illustrator(fh4,fh8)
      return $path."fh4.gif";
    } elseif (preg_match("/\.flm$/i",$filename)) { // Illustrator(flm)
      return $path."flm.gif";
    } elseif (preg_match("/\.pcd$/i",$filename)) { // Illustrator(pcd)
      return $path."pcd.gif";
    ## 圧縮関連
    } elseif (preg_match("/\.rpm$/i",$filename)) {
      return $path."rpm.gif";
    } elseif (preg_match("/\.lzh$/i",$filename)) {
      return $path."lzh.gif";
    } elseif (preg_match("/\.sea$/i",$filename)) {
      return $path."sea.gif";
    } elseif (preg_match("/\.sit$/i",$filename)) {
      return $path."sit.gif";
    } elseif (preg_match("/(\.tar$|\.arc$|\.arj$|\.bdf$|\.bz2$|\.cab$|\.cpt)$/i",$filename)) {
      return $path."tar.gif";
    } elseif (preg_match("/(\.taz$|\.tgz$|\.z$|\.rar$|\.lzs$|\.gz$|\.ish)$/i",$filename)) {
      return $path."tar.gif";
    } elseif (preg_match("/\.zip$/i",$filename)) {
      return $path."zip.gif";
    } elseif (preg_match("/\.patch$/i",$filename)) {
      return $path."patch.gif";
    ## Windows関連
    } elseif (preg_match("/(\.ico$|\.icon)$/i",$filename)) {
      return $path."icon.gif";
    ## その他画像
    } elseif (preg_match("/\.tga$/i",$filename)) {
      return $path."tga.gif";
    } elseif (preg_match("/\.rle$/i",$filename)) {
      return $path."rle.gif";
    } elseif (preg_match("/\.pcx$/i",$filename)) {
      return $path."pcx.gif";
    } elseif (preg_match("/\.xpm$/i",$filename)) {
      return $path."xpm.gif";
    } elseif (preg_match("/(\.pic$|\.pct$|\.pict$|\.pic[1-2]$|\.pics)$/i",$filename)) {
      return $path."pict.gif";
    } elseif (preg_match("/\.lrg$/i",$filename)) {
      return $path."lrg.gif";
    } elseif (preg_match("/\.rtf$/i",$filename)) {
      return $path."rtf.gif";
    ## その他のMicrosoft-Officeファイル
    } elseif (preg_match("/\.dot$/i",$filename)) { // MS-Word
      return $path."dot.gif";
    } elseif (preg_match("/\.mcw$/i",$filename)) { // MS-Word
      return $path."mcw.gif";
    } elseif (preg_match("/\.xjs$/i",$filename)) { // MS-Excel
      return $path."xjs.gif";
    } elseif (preg_match("/\.xlm$/i",$filename)) { // MS-Excel
      return $path."xlm.gif";
    } elseif (preg_match("/\.xla$/i",$filename)) { // MS-Excel
      return $path."xla.gif";
    } elseif (preg_match("/\.xlc$/i",$filename)) { // MS-Excel
      return $path."xlc.gif";
    } elseif (preg_match("/\.xlw$/i",$filename)) { // MS-Excel
      return $path."xlw.gif";
    } elseif (preg_match("/\.xlt$/i",$filename)) { // MS-Excel
      return $path."xlt.gif";
    } elseif (preg_match("/\.pps$/i",$filename)) { // MS-PowerPoint
      return $path."pps.gif";
    } elseif (preg_match("/\.pot$/i",$filename)) { // MS-PowerPoint
      return $path."pot.gif";
    } elseif (preg_match("/\.ppa$/i",$filename)) { // MS-PowerPoint
      return $path."ppa.gif";
    } elseif (preg_match("/\.mdw$/i",$filename)) { // MS-Access
      return $path."mdw.gif";
    } elseif (preg_match("/\.mda$/i",$filename)) { // MS-Access
      return $path."mda.gif";
    } elseif (preg_match("/\.mde$/i",$filename)) { // MS-Access
      return $path."mde.gif";
    ### その他文書関連
    ## 一太郎
    } elseif (preg_match("/\.atr$/i",$filename)) {
      return $path."atr.gif";
    } elseif (preg_match("/\.ctl$/i",$filename)) {
      return $path."ctl.gif";
    } elseif (preg_match("/\.jxw$/i",$filename)) {
      return $path."jxw.gif";
    } elseif (preg_match("/\.jsw$/i",$filename)) {
      return $path."jsw.gif";
    } elseif (preg_match("/\.jaw$/i",$filename)) {
      return $path."jaw.gif";
    } elseif (preg_match("/\.jtw$/i",$filename)) {
      return $path."jtw.gif";
    } elseif (preg_match("/\.jbw$/i",$filename)) {
      return $path."jbw.gif";
    } elseif (preg_match("/\.juw$/i",$filename)) {
      return $path."juw.gif";
    } elseif (preg_match("/\.jfw$/i",$filename)) {
      return $path."jfw.gif";
    } elseif (preg_match("/\.jvw$/i",$filename)) {
      return $path."jvw.gif";
    } elseif (preg_match("/\.jtd$/i",$filename)) {
      return $path."jtd.gif";
    } elseif (preg_match("/\.jtt$/i",$filename)) {
      return $path."jtt.gif";
    ## 花子
    } elseif (preg_match("/\.dra$/i",$filename)) {
      return $path."dra.gif";
    } elseif (preg_match("/\.drh$/i",$filename)) {
      return $path."drh.gif";
    } elseif (preg_match("/\.jsh$/i",$filename)) {
      return $path."jsh.gif";
    } elseif (preg_match("/\.jah$/i",$filename)) {
      return $path."jah.gif";
    } elseif (preg_match("/\.jbh$/i",$filename)) {
      return $path."jbh.gif";
    } elseif (preg_match("/\.jth$/i",$filename)) {
      return $path."jth.gif";
    } elseif (preg_match("/\.juh$/i",$filename)) {
      return $path."juh.gif";
    } elseif (preg_match("/\.pst$/i",$filename)) {
      return $path."pst.gif";
    } elseif (preg_match("/\.prt$/i",$filename)) {
      return $path."prt.gif";
    } elseif (preg_match("/\.pts$/i",$filename)) {
      return $path."pts.gif";
    } elseif (preg_match("/\.pt3$/i",$filename)) {
      return $path."pt3.gif";
    } elseif (preg_match("/\.pa3$/i",$filename)) {
      return $path."pa3.gif";
    } elseif (preg_match("/\.jhd$/i",$filename)) {
      return $path."jhd.gif";
    } elseif (preg_match("/\.jht$/i",$filename)) {
      return $path."jht.gif";
    } elseif (preg_match("/\.jmg$/i",$filename)) {
      return $path."jmg.gif";
    ## OASYS
    } elseif (preg_match("/\.oas$/i",$filename)) {
      return $path."oas.gif";
    } elseif (preg_match("/\.oa2$/i",$filename)) {
      return $path."oa2.gif";
    } elseif (preg_match("/\.oa3$/i",$filename)) {
      return $path."oa3.gif";
    } elseif (preg_match("/\.grp$/i",$filename)) {
      return $path."grp.gif";
    } elseif (preg_match("/\.owk$/i",$filename)) {
      return $path."owk.gif";
    } else {
      return $path."unknown.gif";
    }

## 登録予定拡張子
#ポストスクリプト形式
#eps
#ps
#アイコン
#ico
#windows形式
#tif
#font
#scr
#sys
#dll
##inf
#cpl
#vxd
#com
#bat
#exe
#ocx
#Lotus123
#fmt
#windowsのヘルプで使われるファイル形式。
#fts
  } else {
    return $path."unknown.gif";
  }
}

function get_last($table,$field,$where,$default) {
  global $conn;
  if ($where != "") {
    $where .= " AND ";
  }
  $where .= "not $field is null";

  $sql = "SELECT $field FROM $table WHERE $where ORDER BY $field DESC LIMIT 1";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt > 0) {
    $val = pg_fetch_result($res,0,$field);
  } else {
    $val = $default;
  }
  return $val;
}

function get_first($table,$field,$where,$default) {
  global $conn;
  if ($where != "") {
    $where .= " AND ";
  }
  $where .= "not $field is null";

  $sql = "SELECT $field FROM $table WHERE $where ORDER BY $field LIMIT 1";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt > 0) {
    $val = pg_fetch_result($res,0,$field);
  } else {
    $val = $default;
  }
  return $val;
}

function get_row($table,$fields,$where) {
  global $conn;
  if ($where != "") {
    $sql = "SELECT $fields FROM $table WHERE $where";
  } else {
    $sql = "SELECT $fields FROM $table";
  }
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt > 0) {
    $row = pg_fetch_array($res,0);
    return $row;
  } else {
    return array();
  }
}

function get_count($table,$where) {
  global $conn;
  if ($where != "") {
    $sql = "SELECT count(*) FROM $table WHERE $where";
  } else {
    $sql = "SELECT count(*) FROM $table";
  }
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt > 0) {
    $row = pg_fetch_array($res,0);
    $val = $row[0];
  } else {
    $val = 0;
  }
  return $val;
}

function makemenu_hotellist($sort,$order,$linkstr) {
  global $conn;
  global $hotelid;

  if ($sort=="question") {
    // アンケート順
    if ($order=="asc") {
    } else {
    }
  } elseif ($sort=="question1") {
    // 感謝の声順
    if ($order=="asc") {
    } else {
    }
  } elseif ($sort=="question2") {
    // 改善提案順
    if ($order=="asc") {
    } else {
    }
  } elseif ($sort=="kana") {
    // カナ順
    if ($order=="asc") {
      $sql = "SELECT * from hotel ORDER BY kana";
    } else {
      $sql = "SELECT * from hotel ORDER BY kana DESC";
    }
  } else {
    // 登録順
    if ($order=="asc") {
      $sql = "SELECT * from hotel ORDER BY seqno";
    } else {
      $sql = "SELECT * from hotel ORDER BY seqno DESC";
    }
  }

  $sql = "SELECT * from hotel ORDER BY seqno";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt>0) {
    for ($i=0;$i<$cnt;$i++) {
      $row = pg_fetch_array($res,$i);

      $ls = $linkstr;
      $ls = str_replace("%SEQNO%",euc2sjis($row["seqno"]),$ls);
      $ls = str_replace("%ID%",euc2sjis($row["id"]),$ls);
      if ($row["id"]==$hotelid) {
        $ls = str_replace("%SELECTED%","SELECTED",$ls);
      } else {
        $ls = str_replace("%SELECTED%","",$ls);
      }
      $ls = str_replace("%NAME%",euc2sjis($row["name"]),$ls);
      $ls = str_replace("%KANA%",euc2sjis($row["kana"]),$ls);

      $s .= $ls;
#      $s .= "()";
#      $s .= "<BR>\n";
    }
  }
  return $s;
}

function makeselect_hotellist($key,$usenull) {
  global $conn;

  $s = "<SELECT NAME=\"hotelid\">\n";
  if ($usenull) {
    $s .= "<OPTION VALUE=\"\">選択してください</OPTION>\n";
    $s .= "<OPTION VALUE=\"\">----------</OPTION>\n";
  }

  $sql = "SELECT * from hotel ORDER BY seqno";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt>0) {
    for ($i=0;$i<$cnt;$i++) {
      $row = pg_fetch_array($res,$i);

      $s .= "<OPTION VALUE=\"".euc2sjis($row["id"])."\"";
      if ($row["id"]==$key) $s .= " SELECTED";
      $s .= ">".euc2sjis($row["name"])."</OPTION>\n";
    }
  }
  $s .= "</SELECT>\n";
  return $s;
}

function makeselect_reflist($key,$usenull) {
  global $conn;

  $s = "<SELECT NAME=\"refid\">\n";
  if ($usenull) {
    $s .= "<OPTION VALUE=\"\">選択してください</OPTION>\n";
    $s .= "<OPTION VALUE=\"\">----------</OPTION>\n";
  }

  $sql = "SELECT * from q_ref ORDER BY seqno";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt>0) {
    for ($i=0;$i<$cnt;$i++) {
      $row = pg_fetch_array($res,$i);

      $s .= "<OPTION VALUE=\"".euc2sjis($row["id"])."\"";
      if ($row["id"]==$key) $s .= " SELECTED";
      $s .= ">".euc2sjis($row["name"])."</OPTION>\n";
    }
  }
  $s .= "</SELECT>\n";
  return $s;
}

function makeselect_kindlist($key,$key2,$usenull) {
  global $conn;

  $sql = "SELECT * from q_kind ORDER BY seqno";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);

  $s = "";
  if ($cnt>0) {
    $s .= "<SCRIPT LANGUAGE=\"JavaScript\">\n";
    $s .= "<!--\n";
    $s .= "classs = new Array();\n";

    for ($i=0;$i<$cnt;$i++) {
      $row = pg_fetch_array($res,$i);
      $sql_class = "SELECT * from q_class WHERE kindid='".euc2sjis($row["id"])."' ORDER BY seqno";
      $res_class = pg_query($conn,$sql_class);
      $cnt_class = pg_num_rows($res_class);

      $classies = "";
      if ($cnt_class>0) {
        for ($j=0;$j<$cnt_class;$j++) {
          $row_class = pg_fetch_array($res_class,$j);
          if ($classies != "") $classies .= ";;";
          $classid   = euc2sjis($row_class["id"]);
          $classname = $row_class["name"];
#          $classname = mb_strcut($classname,0,30);
          $classname = euc2sjis($classname);
          $classies .= $classid."::".$classname;
        }
      }
      $s .= "classs[".euc2sjis($row["id"])."] = \"".$classies."\";\n";
    }

    $s .= "\n";

    $s .= "function SelectKind(frm) {\n";
    $s .= "  frm.classid.length = 1;\n";
    $s .= "  frm.classid.options[0].value = \"\";\n";
    $s .= "  frm.classid.options[0].text  = \"未選択\";\n";

#    $s .= "  kindid = frm.kindid[frm.kindid.selectedIndex].value;\n";

    $s .= "  for (i=0;i<frm.kindid.length;i++) {\n";
    $s .= "    if (frm.kindid[i].checked == true) {\n";
    $s .= "      kindid = frm.kindid[i].value;\n";
    $s .= "      break;\n";
    $s .= "    }\n;";
    $s .= "  }\n;";

    $s .= "  if (kindid != '') {\n";

    $s .= "    if (kindid == 1) {\n";
    $s .= "      frm.classid.style.backgroundColor='#FFEEEE';\n";
    $s .= "      frm.comment.style.backgroundColor='#FFEEEE';\n";
    $s .= "    } else { if (kindid == 2) {\n";
    $s .= "      frm.classid.style.backgroundColor='#EEEEFF';\n";
    $s .= "      frm.comment.style.backgroundColor='#EEEEFF';\n";
    $s .= "    } else { if (kindid == 9) {\n";
    $s .= "      frm.classid.style.backgroundColor='#EEFFEE';\n";
    $s .= "      frm.comment.style.backgroundColor='#EEFFEE';\n";
    $s .= "    }}}\n";

    $s .= "    if (classs[kindid] != '') {\n";
    $s .= "      frm.classid.length = 2;\n";
    $s .= "      frm.classid.options[1].value = \"\";\n";
    $s .= "      frm.classid.options[1].text  = \"----------\";\n";
    $s .= "      classtext = classs[kindid].preg_split(';;');\n";
    $s .= "      frm.classid.length = 2+classtext.length;\n";
    $s .= "      for (i=0;i<classtext.length;i++) {\n";
    $s .= "        classvalue = classtext[i].preg_split('::');\n";
    $s .= "        frm.classid.options[i+2].value = classvalue[0];\n";
    $s .= "        frm.classid.options[i+2].text  = classvalue[1];\n";
    $s .= "      };\n";
    $s .= "    } else {\n";
    $s .= "//      alert(classs[kindid]);\n";
    $s .= "    }\n";
    $s .= "  }\n";
    $s .= "}\n";

    $s .= "// -->\n";
    $s .= "</SCRIPT>\n";

#    $s .= "<SELECT NAME=\"kindid\" ONCHANGE=\"SelectKind(this.form)\">\n";
#    if ($usenull) {
#      $s .= "<OPTION VALUE=\"\">選択してください</OPTION>\n";
#      $s .= "<OPTION VALUE=\"\">----------</OPTION>\n";
#    }

#    for ($i=0;$i<$cnt;$i++) {
#      $row = pg_fetch_array($res,$i);
#      $s .= "<OPTION VALUE=\"".euc2sjis($row["id"])."\"";
#      if ($row["id"]==$key) $s .= " SELECTED";
#      if ($row["id"]=="1") $s .= " STYLE=\"background-color:#FFCCCC\"";
#      if ($row["id"]=="2") $s .= " STYLE=\"background-color:#CCCCFF\"";
#      if ($row["id"]=="9") $s .= " STYLE=\"background-color:#CCFFCC\"";
#      $s .= ">".euc2sjis($row["name"])."</OPTION>\n";
#    }
#    $s .= "</SELECT>\n";

    for ($i=0;$i<$cnt;$i++) {
      $row = pg_fetch_array($res,$i);
      if ($row["id"]=="1") $s .= "<FONT STYLE=\"background-color:#FFDDDD\">";
      if ($row["id"]=="2") $s .= "<FONT STYLE=\"background-color:#DDDDFF\">";
      if ($row["id"]=="9") $s .= "<FONT STYLE=\"background-color:#DDFFDD\">";
      $s .= "<INPUT ONCLICK=\"SelectKind(this.form)\" TYPE=RADIO NAME=\"kindid\" VALUE=\"".euc2sjis($row["id"])."\"";
      if ($row["id"]==$key) $s .= " CHECKED";
      $s .= ">";
      $s .= euc2sjis($row["name"])."&nbsp;</FONT>\n";
    }
    $s .= "<BR>\n";


    $s .= "詳細: <SELECT NAME=\"classid\"";
    if ($key == 1) { $s .= " STYLE=\"background-color:#FFEEEE\""; }
    if ($key == 2) { $s .= " STYLE=\"background-color:#EEEEFF\""; }
    if ($key == 9) { $s .= " STYLE=\"background-color:#EEFFEE\""; }

    $s .= ">\n";
    if ($usenull) {
      $s .= "<OPTION VALUE=\"\">選択してください</OPTION>\n";
      $s .= "<OPTION VALUE=\"\">----------</OPTION>\n";
    }

    if (!empty($key)) {
      $sql_class = "SELECT * from q_class WHERE kindid='".$key."' ORDER BY seqno";
      $res_class = pg_query($conn,$sql_class);
      $cnt_class = pg_num_rows($res_class);

      for ($i=0;$i<$cnt_class;$i++) {
        $row_class = pg_fetch_array($res_class,$i);

        $classid   = euc2sjis($row_class["id"]);
        $classname = $row_class["name"];
#        $classname = mb_strcut($classname,0,30);
        $classname = euc2sjis($classname);

        $s .= "<OPTION VALUE=\"".$classid."\"";
        if ($row_class["id"]==$key2) $s .= " SELECTED";
        $s .= ">".$classname."</OPTION>\n";
      }
    }
    $s .= "</SELECT>\n";
  }

  return $s;
}

function makeselect_statelist($key,$usenull) {
  global $arrState;

  $s = "<SELECT NAME=\"state\" STYLE=\"width:78px\">\n";
  if ($usenull) {
    $s .= "<OPTION VALUE=\"\">以下選択</OPTION>\n";
    $s .= "<OPTION VALUE=\"\">--------</OPTION>\n";
  }

  if (sizeof($arrState)>0) {
    reset($arrState);
    while (list($no,$val)=each($arrState)) {
      $s .= "<OPTION VALUE=\"".$val."\"";
      if ($val==$key) $s .= " SELECTED";
      $s .= ">".$val."\n";
    }
  }
  $s .= "</SELECT>\n";
  return $s;
}


function makeradio_answer($fieldname,$key) {
  $s = "";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"4\"";
  if ($key==4) $s .= " CHECKED";
  $s .= ">大変満足\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"3\"";
  if ($key==3) $s .= " CHECKED";
  $s .= ">やや満足\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"2\"";
  if ($key==2) $s .= " CHECKED";
  $s .= ">やや不満\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"1\"";
  if ($key==1) $s .= " CHECKED";
  $s .= ">大変不満\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"0\"";
  if ($key==0) $s .= " CHECKED";
  $s .= "><FONT COLOR=#555555>未設定\n";
  return $s;
}

function makeradio_answer2($fieldname,$key) {
  $s = "";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"1\"";
  if ($key==1) $s .= " CHECKED";
  $s .= ">はい\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"2\"";
  if ($key==2) $s .= " CHECKED";
  $s .= ">いいえ\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"0\"";
  if ($key==0) $s .= " CHECKED";
  $s .= "><FONT COLOR=#555555>未設定</FONT>\n";
  return $s;
}

function draw_hotel($id,$str) {
  global $conn;

  $sql = "SELECT * from hotel WHERE id='".$id."'";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt==0) {
    $seqno = "";
    $id    = "";
    $name  = "不明";
    $kana  = "フメイ";
  } else {
    $row = pg_fetch_array($res,0);
    $seqno = $row["seqno"];
    $id    = $row["id"];
    $name  = euc2sjis($row["name"]);
    $kana  = euc2sjis($row["kana"]);
  }
  $str = str_replace("%SEQNO%",$seqno,$str);
  $str = str_replace("%ID%",   $id,   $str);
  $str = str_replace("%NAME%", $name, $str);
  $str = str_replace("%KANA%", $kana, $str);
  return $str;
}

function draw_ref($id,$str) {
  global $conn;

  $sql = "SELECT * from q_ref WHERE id='".$id."'";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt==0) {
    $seqno = "";
    $id    = "";
    $name  = "不明";
    $kana  = "フメイ";
  } else {
    $row = pg_fetch_array($res,0);
    $seqno = $row["seqno"];
    $id    = $row["id"];
    $name  = euc2sjis($row["name"]);
    $kana  = euc2sjis($row["kana"]);
  }
  $str = str_replace("%SEQNO%",$seqno,$str);
  $str = str_replace("%ID%",   $id,   $str);
  $str = str_replace("%NAME%", $name, $str);
  $str = str_replace("%KANA%", $kana, $str);
  return $str;
}

function draw_kind($id,$str) {
  global $conn;

  $sql = "SELECT * from q_kind WHERE id='".$id."'";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt==0) {
    $seqno = "";
    $id    = "";
    $name  = "不明";
    $kana  = "フメイ";
  } else {
    $row = pg_fetch_array($res,0);
    $seqno = $row["seqno"];
    $id    = $row["id"];
    $name  = euc2sjis($row["name"]);
    $kana  = euc2sjis($row["kana"]);
  }
  $str = str_replace("%SEQNO%",$seqno,$str);
  $str = str_replace("%ID%",   $id,   $str);
  $str = str_replace("%NAME%", $name, $str);
  $str = str_replace("%KANA%", $kana, $str);
  return $str;
}

function draw_class($kindid,$id,$str) {
  global $conn;

  $sql = "SELECT * from q_class WHERE kindid='".$kindid."' AND id='".$id."'";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt==0) {
    $seqno = "";
    $id    = "";
    $name  = "不明";
    $kana  = "フメイ";
  } else {
    $row = pg_fetch_array($res,0);
    $seqno = $row["seqno"];
    $id    = $row["id"];
    $name  = euc2sjis($row["name"]);
    $kana  = euc2sjis($row["kana"]);
  }
  $str = str_replace("%SEQNO%",$seqno,$str);
  $str = str_replace("%ID%",   $id,   $str);
  $str = str_replace("%NAME%", $name, $str);
  $str = str_replace("%KANA%", $kana, $str);
  return $str;
}

function draw_answer($val,$str) {
  $str = str_replace("%VALUE%",$val,$str);
  if ($val==4) $str = str_replace("%NAME%","大変満足",$str);
  if ($val==3) $str = str_replace("%NAME%","やや満足",$str);
  if ($val==2) $str = str_replace("%NAME%","やや不満",$str);
  if ($val==1) $str = str_replace("%NAME%","大変不満",$str);
  if ($val==0) $str = str_replace("%NAME%","<FONT COLOR=#555555>未設定</FONT>",$str);
  return $str;
}

function setarray_class($kindid) {
  global $conn;

  $sql = "SELECT * from q_class WHERE kindid='".$kindid."' ORDER BY seqno";
  $res = pg_query($conn,$sql);
  $cnt = pg_num_rows($res);
  if ($cnt>0) {
    while ($row = pg_fetch_array($res)) { 
      $arr[$row["id"]] = euc2sjis($row["name"]);
    }
    return $arr;
  } else {
    return array();
  }
}

function makeradio_answer3($fieldname,$key) {
  $s = "";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"t\"";
  if ($key==t) $s .= " CHECKED";
  $s .= ">対応済\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"f\"";
  if ($key!=t) $s .= " CHECKED";
  $s .= ">未対応\n";
  return $s;
}

function draw_answer3($val,$str) {
  $str = str_replace("%VALUE%",$val,$str);
  if ($val==f) $str = str_replace("%NAME%","未対応",$str);
  if ($val==t) $str = str_replace("%NAME%","対応済",$str);
  return $str;
}

function makeradio_answer4($fieldname,$key) {
  $s = "";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"3\"";
  if ($key==3) $s .= " CHECKED";
  $s .= ">大\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"2\"";
  if ($key==2) $s .= " CHECKED";
  $s .= ">中\n";
  $s .= "<INPUT TYPE=RADIO NAME=\"$fieldname\" VALUE=\"1\"";
  if ($key==1) $s .= " CHECKED";
  $s .= ">小\n";
  return $s;
}

function draw_answer4($val,$str) {
  $str = str_replace("%VALUE%",$val,$str);
  if ($val==3) $str = str_replace("%NAME%","大",$str);
  if ($val==2) $str = str_replace("%NAME%","中",$str);
  if ($val==1) $str = str_replace("%NAME%","小",$str);
  if ($val==0) $str = str_replace("%NAME%","<FONT COLOR=#555555>未設定</FONT>",$str);
  return $str;
}

?>
