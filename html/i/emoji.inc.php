<?php
	if ($agenttype=="imode") {
		$carrier = "i";
		$imageextension = ".gif";
		$imagepath = "/home/bbr/html_i/img";
		$accesskey = "ACCESSKEY";
	} elseif ($agenttype=="jsky") {
		$carrier = "v";
		$imageextension = ".jpg";
		$imagepath = "/home/bbr/html_v/img";
#		$accesskey = "DIRECTKEY";
		$accesskey = "ACCESSKEY";
	} elseif ($agenttype=="ezweb") {
		$carrier = "z";
#		$image_extension = ".png"
		$imageextension = ".gif";
		$imagepath = "/home/bbr/html_ez/img";
		$accesskey = "accesskey";
	} else {
		$carrier = "";
		$imageextension = ".gif";
		$imagepath = "/home/bbr/html_i/img";
#		$accesskey = "ACCESSKEY";
	}

	function echo_inputmode($type) {
		return inputmode($type);
	}

  function echo_mode($type) {
    global $agenttype;
    if ($agenttype=="imode") {
      if ($type=="hiragana") {
#        echo "istyle=1";
      } elseif ($type=="katakana") {
        echo "istyle=2";
      } elseif ($type=="alphabet") {
        echo "istyle=3";
      } else {
        echo "istyle=4";
      }
    } elseif ($agenttype=="jsky") {
      if ($type=="hiragana") {
#        echo "mode=hiragana";
      } elseif ($type=="katakana") {
        echo "mode=katagana";
      } elseif ($type=="alphabet") {
        echo "mode=alphabet";
      } else {
        echo "mode=numeric";
      }
    } elseif ($agenttype=="ezweb") {
      if ($type=="hiragana") {
#        echo "istyle=1";
      } elseif ($type=="katakana") {
        echo "istyle=2";
      } elseif ($type=="alphabet") {
        echo "istyle=3";
      } else {
        echo "istyle=4";
      }
    } else {
      if ($type=="hiragana") {
        echo "style=\"ime-mode:active\"";
      } elseif ($type=="katakana") {
        echo " style=\"ime-mode:active\"";
      } elseif ($type=="alphabet") {
        echo " style=\"ime-mode:disabled\"";
      } else {
        echo " style=\"ime-mode:disabled\"";
      }
    }
  }

	## 入力フォーム属性
	function inputmode($type) {
		global $agenttype;
		if ($agenttype=="imode") {
			if ($type=="hiragana") {
				$ret = "ISTYLE=1";
			} elseif ($type=="katakana") {
				$ret = "ISTYLE=2";
			} elseif ($type=="alphabet") {
				$ret = "ISTYLE=3";
			} else {
				$ret = "ISTYLE=4";
			}
		} elseif ($agenttype=="jsky") {
			if ($type=="hiragana") {
				$ret = "MODE=hiragana";
			} elseif ($type=="katakana") {
				$ret = "MODE=katagana";
			} elseif ($type=="alphabet") {
				$ret = "MODE=alphabet";
			} else {
				$ret = "MODE=numeric";
			}
		} elseif ($agenttype=="ezweb") {
			if ($type=="hiragana") {
				$ret = "ISTYLE=1";
			} elseif ($type=="katakana") {
				$ret = "ISTYLE=2";
			} elseif ($type=="alphabet") {
				$ret = "ISTYLE=3";
			} else {
				$ret = "ISTYLE=4";
			}
		} else {
			if ($type=="hiragana") {
				$ret = "STYLE=\"ime-mode:active\"";
			} elseif ($type=="katakana") {
				$ret = "STYLE=\"ime-mode:active\"";
			} elseif ($type=="alphabet") {
				$ret = "STYLE=\"ime-mode:disabled\"";
			} else {
				$ret = "STYLE=\"ime-mode:disabled\"";
			}
		}
		return $ret;
	}

	## 絵文字出力:通常
	function emoji($name) {
		global $conn,$agenttype,$codetype;
		$ret = "";
		$sql = "SELECT * FROM emoji WHERE name='$name'";
		$res = pg_query($conn,$sql);
		if (pg_num_rows($res)>0) {
			$row = pg_fetch_array($res,0);
			$color   = $row["color_i"];
			$code_i  = $row["code_i"];
			$code_j  = $row["code_j"];
			$code_ez = $row["code_ez"];
			$text    = euc2sjis($row["text"]);
		}
		if ($agenttype=="imode") {
			if ($code_i!="") {
				$ret = "<FONT COLOR=$color>";
				$ret .= pack("C2",hexdec(substr($code_i,0,2)),hexdec(substr($code_i,2,2)));
				$ret .= "</FONT>";
			} elseif (!empty($text)) {
				$ret = "<FONT COLOR=$color>";
				$ret .= $text;
				$ret .= "</FONT>";
			} else {
				$ret = "";
			}
		} elseif ($agenttype=="jsky") {
			## C型端末用処理
			if ($code_j!="" && ($codetype!="HTMLVer.4" && $codetype!="HTMLVer.5")) {
				$checkcode = substr($code_j,1,1);
				if ($checkcode=="O" || $checkcode=="P" || $checkcode=="Q") {
					$code_j = "";
					$name = "";
				}
			}

			if ($code_j!="") {
				$ret = emoji_in();
				$ret.= $code_j;
				$ret.= emoji_out();
			} elseif (substr($name,0,1)=="V") {
				$ret = emoji_in();
				$ret.= "$".chr(hexdec(substr($name,1,2))).chr(hexdec(substr($name,3,2)));
				$ret.= emoji_out();
			} elseif (!empty($text)) {
				$ret = "<FONT COLOR=$color>";
				$ret.= $text;
				$ret.= "</FONT>";
			} else {
				$ret	= "";
			}
		} elseif ($agenttype=="ezweb") {
			if ($code_ez!="") {
				$ret = "<IMG localsrc=\"".$code_ez."\">";
			} elseif (substr($name,0,1)=="Z") {
				$ret = "<IMG localsrc=\"".substr($name,1)."\">";
			} elseif (!empty($text)) {
				$ret = "<FONT COLOR=$color>";
				$ret.= $text;
				$ret.= "</FONT>";
			} else {
				$ret = "";
			}
		} else {
			if (!empty($text)) {
				$ret = "<FONT COLOR=$color>";
				$ret.= $text;
				$ret.= "</FONT>";
			} else {
				$ret = "";
			}
		}
		return $ret;
	}

	// 絵文字出力2:色なし
	function emoji2($name) {
		global $conn,$agenttype,$codetype;
		$ret = "";
		$sql = "SELECT * FROM emoji WHERE name='$name'";
		$res = pg_query($conn,$sql);
		if (pg_num_rows($res)>0) {
			$row = pg_fetch_array($res,0);
			$code_i	= $row["code_i"];
			$code_j	= $row["code_j"];
			$code_ez = $row["code_ez"];
			$text		= euc2sjis($row["text"]);
		}
		if ($agenttype=="imode") {
			if ($code_i!="") {
				$ret .= pack("C2",hexdec(substr($code_i,0,2)),hexdec(substr($code_i,2,2)));
			} elseif (!empty($text)) {
				$ret .= $text;
			} else {
				$ret	= "";
			}
		} elseif ($agenttype=="jsky") {
			// C型端末用処理
			if ($code_j!="" && ($codetype!="HTMLVer.4" && $codetype!="HTMLVer.5")) {
				$checkcode = substr($code_j,1,1);
				if ($checkcode=="O" || $checkcode=="P" || $checkcode=="Q") {
					$code_j = "";
					$name	 = "";
				}
			}

			if ($code_j!="") {
				$ret	= emoji_in();
				$ret .= $code_j;
				$ret .= emoji_out();
			} elseif (substr($name,0,1)=="V") {
				$ret	= emoji_in();
				$ret .= "$".chr(hexdec(substr($name,1,2))).chr(hexdec(substr($name,3,2)));
				$ret .= emoji_out();
			} elseif (!empty($text)) {
				$ret .= $text;
			} else {
				$ret	= "";
			}
		} elseif ($agenttype=="ezweb") {
			if ($code_ez!="") {
				$ret = "<IMG localsrc=\"".$code_ez."\">";
			} elseif (substr($name,0,1)=="Z") {
				$ret = "<IMG localsrc=\"".substr($name,1)."\">";
			} elseif (!empty($text)) {
				$ret .= $text;
			} else {
				$ret	= "";
			}
		} else {
			$ret .= $text;
		}
		return $ret;
	}

	function echo_emoji($name) {
		global $agenttype;
		if ($agenttype=="imode") {
			ob_end_flush();
		}
		mb_http_output("pass");
		print emoji($name);
		mb_http_output("SJIS");
		if ($agenttype=="imode") {
			ob_start('mb_output_handler');
		}
	}

	function emoji_in() { // 絵文字Shiftイン(Only J-PHONE)
		return pack("C1",hexdec("1B"));
	}
	function emoji_out() { // 絵文字Shiftアウト(Only J-PHONE)
		return pack("C1",hexdec("0F"));
	}
	function echo_emoji_in() { // 絵文字Shiftイン(Only J-PHONE)
#		ob_end_flush(); mb_http_output("pass");
		mb_http_output("pass");
		echo emoji_in();
#		mb_http_output("SJIS"); ob_start('mb_output_handler');
	}
	function echo_emoji_out() { // 絵文字Shiftアウト(Only J-PHONE)
#		ob_end_flush(); mb_http_output("pass");
		echo emoji_out();
#		mb_http_output("SJIS"); ob_start('mb_output_handler');
		mb_http_output("SJIS");
	}

// 絵文字を除去 注:SJISのまま受け取り、SJISで返す
function remove_emoji($str) {
	global $agenttype;
	$ret = "";
	if ($agenttype=="imode") {
		$kanji = false;
		$emoji = false;
		for ($i=0;$i<strlen($str);$i++) {
			$s = substr($str,$i,1);
			if (!$kanji) {
				if ((ord($s) >= 0x81 && ord($s) <= 0x9F) || (ord($s) >= 0xF9 && ord($s) <= 0xFC)) {
					// 漢字[0xF8-0xF9]の第一バイト
					$kanji = true;
					$emoji = false;
					$ss = $s;
				} else {
					$ret .= $s;
				}
			} else {
				$kanji = false;
				if (ord($ss)==0xF8 && ord($s)>=0x9F && ord($s)<=0xFC) {
					$emoji = true;
				} elseif (ord($ss)==0xF9 && (
									(ord($s)>=0x40 && ord($s)<=0x49) ||
									(ord($s)>=0x50 && ord($s)<=0x57) ||
									(ord($s)>=0x5B && ord($s)<=0x5E) ||
									(ord($s)>=0x72 && ord($s)<=0x7E) ||
									(ord($s)>=0x80 && ord($s)<=0xFC)
									)) {
					$emoji = true;
				} else {
					$emoji = false;
				}
				if ($emoji) {
				} else {
					$ret .= $ss.$s;
				}
				$ss = "";
				$s	= "";
			}
		}
	} elseif ($agenttype=="jsky") {
		// Vodafone(旧J-PHONE)用
		$emoji = false;
		for ($i=0;$i<strlen($str);$i++) {
			$s = substr($str,$i,1);
			if (strlen($str)>$i+2) {
				$s2 = substr($str,$i+1,1);
			} else {
				$s2 = "";
			}

			if (!$emoji) {
				// 非絵文字モード中
				if (ord($s) == 0x1B && ord($s2) == 0x24) {
					// Shift-In発見!
					$ret .= $s;
					$emoji = true;
					$i += 1;
					continue;
				}
				$ret .= $s;
				continue;
			} else {
				// 絵文字モード中
				if (ord($s) == 0x0F) {
					// Shift-Out発見!
					$emoji = false;
				}
#				$i += 1;
			}
			$s2 = "";
			$s	= "";
		}
	} elseif ($agenttype=="ezweb") {
		$ret = $str; # 不要
	} else {
		$ret = $str; # そのまま
	}
	return($ret);
}

// 絵文字を&emojixxxxx;の形式に変換する 注:SJISのまま受け取り、SJISで返す
function convert_emoji($str) {
	global $agenttype;
	$ret = "";
	if ($agenttype=="imode") {
		// iモード用
		$kanji = false;
		$emoji = false;
		for ($i=0;$i<strlen($str);$i++) {
			$s = substr($str,$i,1);
			if (!$kanji) {
				if ((ord($s) >= 0x81 && ord($s) <= 0x9F) || (ord($s) >= 0xF9 && ord($s) <= 0xFC)) {
#					// 漢字[0xF8-0xF9]の第一バイト
					$kanji = true;
					$emoji = false;
					$ss = $s;
				} else {
					$ret .= $s;
				}
			} else {
				$kanji = false;
				if (ord($ss)==0xF8 && ord($s)>=0x9F && ord($s)<=0xFC) {
					$emoji = true;
				} elseif (ord($ss)==0xF9 && (
									(ord($s)>=0x40 && ord($s)<=0x49) ||
									(ord($s)>=0x50 && ord($s)<=0x57) ||
									(ord($s)>=0x5B && ord($s)<=0x5E) ||
									(ord($s)>=0x72 && ord($s)<=0x7E) ||
									(ord($s)>=0x80 && ord($s)<=0xFC)
									)) {
					$emoji = true;
				} else {
					$emoji = false;
				}
				if ($emoji) {
					$hex = strtoupper(base_convert(ord($ss),10,16).base_convert(ord($s),10,16));
					$name = get_first("emoji","name","code_i='$hex'","");
					if ($name!="") {
						$ret .= "&nemoji".$name.";";
					}
				} else {
					$ret .= $ss.$s;
				}
				$ss = "";
				$s	= "";
			}
		}
	} elseif ($agenttype=="jsky") {
		// Vodafone(旧J-PHONE)用
		$emoji = false;
		for ($i=0;$i<strlen($str);$i++) {
			$s = substr($str,$i,1);
			if (strlen($str)>$i+2) {
				$s2 = substr($str,$i+1,1);
			} else {
				$s2 = "";
			}

			if (ord($s) == 0x1B && ord($s2) == 0x24) {
				// Shift-In発見!
				$emoji_start = $i+2;
				$emoji_end = strpos($str,0x0F,$emoji_start);
				$emoji_length = $emoji_end - $emoji_start+1;

				$emoji_chars = substr($str,$emoji_start,$emoji_length-1);

				$first_char = substr($emoji_chars,0,1);
				$second_chars = substr($emoji_chars,1);
				for ($j=0;$j<strlen($second_chars);$j++) {
					$second_char = substr($second_chars,$j,1);
					$emoji_char = "$".$first_char.$second_char;
					$name = get_first("emoji","name","code_j='".$emoji_char."'","");
					if ($name!="") {
						$ret .= "&nemoji".$name.";";
					} else {
						$ret .= "&nemojiV".sprintf("%02X",ord($first_char)).sprintf("%02X",ord($second_char)).";";
					}
				}

				$i += $emoji_length+1;
				continue;
			} else {
				// 通常
				$ret .= $s;
				continue;
			}
			$s2 = "";
			$s	= "";
		}
	} elseif ($agenttype=="ezweb") {
		$ret = $str; # 不要
	} else {
		$ret = $str; # そのまま
	}
	return($ret);
}

function replace_emoji($str) {
	// 絵文字変換
	$c = 0;
	while (true) {
		$c++;
		if (preg_match("/&emoji([a-zA-Z0-9!?$\(\)_\-]+);/i",$str,$reg)>0) {
			// ターゲットを含んだ行があった
			if ($reg[1]!="") {
				$emoji = emoji($reg[1]);
#				if (!empty($emoji)) {
					$str = str_replace($reg[0],$emoji,$str);
#				}
			}
			if ($c>200) {
				break;
			}
		} else {
			break;
		}
	}
	// 絵文字変換2
	$c = 0;
	while (true) {
		$c++;
		if (preg_match("/&nemoji([a-zA-Z0-9!?$\(\)_\-]+);/i",$str,$reg)>0) {
			// ターゲットを含んだ行があった
			if ($reg[1]!="") {
				$emoji2 = emoji2($reg[1]);
#				if (!empty($emoji2)) {
					$str = str_replace($reg[0],$emoji2,$str);
#				}
			}
			if ($c>200) {
				break;
			}
		} else {
			break;
		}
	}
	return $str;
}

	## サイトタイトル表示
	function echo_emoji_title($color) {
		global $system_shortname,$agenttype;
		if ($agenttype=="imode") {
			print $system_shortname."V2";
			echo_emoji("imode2");
		} elseif ($agenttype=="jsky") {
			print $system_shortname."V2";
			echo_emoji("vodafone");
		} elseif ($agenttype=="ezweb") {
			print $system_shortname."V2";
			echo_emoji("ezweb");
		} else {
			print "Mobile".$system_shortname." Ver2";
		}
	}
?>