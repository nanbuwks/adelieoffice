<?php
	$agent       = getenv("HTTP_USER_AGENT");
	$remote_addr = getenv("REMOTE_ADDR");
	$remote_host = getenv("REMOTE_HOST");
	$script_name = getenv("SCRIPT_NAME");
	$request_uri = getenv("REQUEST_URI");
	$referer     = getenv("HTTP_REFERER");
	$host_name   = getenv("HTTP_HOST");

	$loginerr = array();
	if (empty($pagetitle)) { $pagetitle.= "ペンギンオフィス"; }

	if (!$conn) {
		$conn = pg_connect($pgsql_connect);
		$res = pg_query($conn,"SET DATESTYLE TO 'ISO'");
	} else {
		/* 日付を Y-m-d h:n:s に固定 */
		$res = pg_query($conn,"SET DATESTYLE TO 'ISO'");
	}

	$title_backcolor     = "#FFFFEE";
	$title_forecolor     = "#663300";
	$title_forecolor1    = "#663300";
	$title_forecolor2    = "#663300";
	$title_forecolor3    = "#663300";
	$logout_backcolor    = "#99FFFF";
	$logout_forecolor    = "#663300";
	$bottom_backcolor    = "#FFCC99";
	$bottom_forecolor    = "#663300";
	$menu_backcolor      = "#99FFFF";
	$menu_forecolor      = "#663300";
	$indexmenu_backcolor = "#99FFFF";
	$indexmenu_forecolor = "#000033";

	$bodyBackColor       = "#FFFFFF";
	$bodyForeColor       = "#000000";
	$bodyLinkColor       = "#0000FF";
	$bodyVLinkColor      = "#0000FF";

	if (!$noheader) {
		$header["txt"].= "<html>";
		$header["txt"].= "<head>";
		$header["txt"].= "<title>";
		$header["txt"].= $pagetitle;
		$header["txt"].= "</title>";
		$header["txt"].= "</head>";
		$header["txt"].= "<body text=".$bodyForeColor." link=".$bodyLinkColor." vlink=".$bodyVLinkColor." bgcolor=".$bodyBackColor.">";
		$header["txt"].= "<font size=\"-1\" style=\"font-size:small\">";
	}

	// ログイン処理
	if ($authtype=="utn") {
		if (preg_match("/DoCoMo\/1\.0/i",$agent)) {
			// imode PDC
			if (preg_match("/ser(.*)$/i",$agent,$matches)>0) {
				$utn = $matches[1];
			}
		} elseif (preg_match("/DoCoMo\/2\.0/i",$agent)) {
			// imode FOMA
			if (preg_match("/ser(.*);/i",$agent,$matches)>0) {
				$utn = $matches[1];
			}
		}
	}

	// userid/pwd はPOSTからしか受け付けないようにする。
	if (!empty($_REQUEST["userid"])) { $userid = $_REQUEST["userid"]; } else { $userid = ""; }
	if (!empty($_REQUEST["pwd"]))    { $pwd    = $_REQUEST["pwd"];    } else { $pwd    = ""; }
	if (!empty($_REQUEST["ms"]))     { $ms     = $_REQUEST["ms"];     } else { $ms     = ""; }
	if (!empty($_REQUEST["sn"]))     { $sn     = $_REQUEST["sn"];     } else { $sn     = ""; }

	if ($authtype=="" || $authtype=="utn") {
		if ($sn=="" && $ms=="") {
			if ($userid=="") {
				$header["txt"].= "<center>";
				$header["txt"].= $system_shortname."<br>";
				$header["txt"].= "Login<br>";
				$header["txt"].= "<hr size=\"0\">";
				$header["txt"].= "<form name=\"login\" action=\"./\" method=\"post\">";
				$header["txt"].= "<center>";
				$header["txt"].= "&lt;ログインID&gt;<BR>";
				$header["txt"].= "<INPUT TYPE=TEXT SIZE=13 NAME=\"userid\" VALUE=\"".$retryid."\" ";
				$header["txt"].= inputmode("alphabet");
				$header["txt"].= "><BR>";
				$header["txt"].= "&lt;パスワード&gt;<BR>";
				$header["txt"].= "<INPUT TYPE=PASSWORD SIZE=13 NAME=\"pwd\" ";
				$header["txt"].= inputmode("alphabet");
				$header["txt"].= "><BR>";
				$header["txt"].= "<INPUT TYPE=SUBMIT VALUE=\"ログイン\">";
				$header["txt"].= "</CENTER>";
				$header["txt"].= "</FORM>";
				$header["txt"].= "</CENTER>";
			} else {
				// ユーザーID入力後
				$sql_login = "SELECT * FROM users WHERE id='".$userid."'";
				$res_login = pg_query($conn,$sql_login);
				if (pg_num_rows($res_login)>0) {
					$row_login = pg_fetch_array($res_login,0);
					if ($pwd<>$row_login["passwd"] && crypt($pwd,substr($pwd,1,2))<>$row_login["passwd"]) {
						$loginerr[] = "パスワードまたはユーザーIDが正しくありません。";
					} else {
						$login_ok = false;
						// 認証成功
						$login       = true;
						$login_id    = $row_login["id"];
						$no          = $row_login["seqno"];
						$login_name  = $row_login["name"];
						$login_email = $row_login["email"];
						$login_ryaku = $row_login["name_ryaku"];
						$admin_sign  = $row_login["admin_sign"];

						// ms(mobile_session)生成
						$ms = make_serial(10);

						$sql_login  = "UPDATE users SET ";
						$sql_login .= "onetime_passwd='',";
						$sql_login .= "mobile_session='".$ms."'";
						$sql_login .= " WHERE id='".$login_id."'";
						$res_login = pg_query($conn,$sql_login);

						header("Location: ".$access.$domain.$toppath."/i/?ms=$ms&mode=login");
						exit;
					}
				} else {
					$loginerr[] = "ユーザーIDまたはパスワードが正しくありません。";
				}
			}
		} elseif (!empty($sn) && empty($ms)) {
			## DirectLogin
			if (empty($userid)) {
				$sql_login = "SELECT * FROM users WHERE onetime_passwd='".$sn."'";
			} else {
				$sql_login = "SELECT * FROM users WHERE onetime_passwd='".$sn."' AND id='".$userid."'";
			}
			$res_login = pg_query($conn,$sql_login);
			$cnt_login = pg_num_rows($res_login);
			if ($cnt_login>0) {
				$row_login = pg_fetch_array($res_login,0);
				## ユーザー情報の取得
				$login       = true;
				$login_id    = $row_login["id"];
				$no          = $row_login["seqno"];
				$login_name  = $row_login["name"];
				$login_email = $row_login["email"];
				$login_ryaku = $row_login["name_ryaku"];
				$admin_sign  = $row_login["admin_sign"];
				$ms          = $row_login["mobile_session"];
				if (empty($ms)) { $ms = make_serial(10); }

				## onetime_passwdの消去
				$sql_login = "UPDATE users SET ";
				$sql_login.= "onetime_passwd='',";
				$sql_login.= "mobile_session='".$ms."'";
				$sql_login.= " WHERE id='".$login_id."'";
				$res_login = pg_query($conn,$sql_login);
			} else {
				$loginerr[] = "指定された方法でのログインは既にご利用できません。";
			}

			// 個体認証処理
			if (($authtype=="utn" || $row_login["utn"]!="") && $mode=="login") {
				if ($authtype!="utn") {
					$loginerr[] = "このユーザIDは既に他の携帯電話で利用されています。機種を変更した場合は管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
				}
				if (sizeof($loginerr)=="0") {
					if ($utn == "") {
						$header["txt"].= "不正利用防止のため携帯電話情報を確認します。";
						$header["txt"].= "下のリンクを選択してください。<br>";
						$header["txt"].= "<center>";
						$rn = make_serial(6);
						$header["txt"].= "<a href=\"".$access.$domain.$toppath."/i/?ms=$ms&mode=login&rn=$rn\" utn>メニューへ</A>";
						$header["txt"].= "&emojimobilephone;";
						$header["txt"].= "<BR><BR>";
						$header["txt"].= "&emojiadelie";
						$header["txt"].= "</CENTER>";
#						$login = false;
					} else {
						if ($row_login["utn"]=="") {
							$login_ok = true;
						} else {
							if ($row_login["utn"]==$utn) {
							$login_ok = true;
						} else {
							$loginerr[] = "指定のIDは既に他の携帯電話で利用されています。<BR>機種を変更した場合は,管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
							}
						}

						// 個体認証の書き込み
						if ($login_ok && $utn!="") {
							$sql_login  = "UPDATE users SET ";
							$sql_login .= "utn='".$utn."'";
							$sql_login .= " WHERE id='".$login_id."'";
							$res_login = pg_query($conn,$sql_login);
						}
					}
				}
			} else {
				if ($authtype != "utn") {
					if ($row_login["utn"]=="") {
						$login_ok = true;
					} else {
						$loginerr[] = "指定のUserIDは既に他の携帯電話で利用されています!<BR>機種を変更した場合、管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
					}
				}
			}
		} elseif (!empty($ms)) {
			## MobileSessionあり
			if (empty($userid)) {
				$sql_login = "SELECT * FROM users WHERE mobile_session='".$ms."'";
			} else {
				$sql_login = "SELECT * FROM users WHERE mobile_session='".$ms."' AND id='".$userid."'";
			}
			$res_login = pg_query($conn,$sql_login);
			$cnt_login = pg_num_rows($res_login);
			if ($cnt_login>0) {
				$row_login = pg_fetch_array($res_login,0);

				// 変数展開
				$login       = true;
				$login_id    = $row_login["id"];
				$no          = $row_login["seqno"];
				$login_name  = $row_login["name"];
				$login_email = $row_login["email"];
				$login_ryaku = $row_login["name_ryaku"];
				$admin_sign  = $row_login["admin_sign"];
			} else {
				$loginerr[] = "パスワードまたはユーザーIDが正しくありません";
			}

			## 個体認証処理
			if (($authtype=="utn" || $row_login["utn"]!="") && $mode=="login") {
				if ($authtype!="utn") {
					$loginerr[] = "このユーザIDは既に他の携帯電話で利用されています。機種を変更した場合は管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
				}
				if (sizeof($loginerr)=="0") {
					if (empty($utn)) {
						$header["txt"].= "不正利用防止のため携帯電話情報を確認します。下のリンクを選択してください。<BR><BR>";
						$header["txt"].= "<CENTER>";
						$rn = make_serial(6);
						$header["txt"].= "<A HREF=\"".$access.$domain.$toppath."/i/?ms=$ms&mode=login&rn=$rn\" utn>メニューへ</A>";
						$header["txt"].= "&emojimobilephone;";
						$header["txt"].= "<BR><BR>";
						$header["txt"].= "&emojiadelie;";
						$header["txt"].= "</CENTER>";
						$login = false;
					} else {
						if ($row_login["utn"]=="") {
							$login_ok = true;
						} else {
							if ($row_login["utn"]==$utn) {
								$login_ok = true;
							} else {
								$loginerr[] = "指定のIDは既に他の携帯電話で利用されています。<BR>機種を変更した場合は、管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
							}
						}

						## 個体認証の書き込み
						if ($login_ok && $utn!="") {
							$sql_login  = "UPDATE users SET ";
							$sql_login .= "utn='".$utn."'";
							$sql_login .= " WHERE id='".$login_id."'";
							$res_login = pg_query($conn,$sql_login);
						}
					}
				}
			} else {
				if ($authtype != "utn") {
					if ($row_login["utn"]=="") {
						$login_ok = true;
					} else {
						$loginerr[] = "指定のUserIDは既に他の携帯電話で利用されています!<BR>機種を変更した場合、管理者に連絡してメンテナンスページから携帯情報の削除を行ってもらってください。その後、新しい携帯電話で再度ログインすることでご利用が可能になります。";
					}
				}
			}
		}

		if ($ms <> "") {
			$serial = "?ms=".$ms;
		} else {
			$serial = "?ms=";
		}

	} elseif ($authtype=="basic") {
		// BASIC認証
		if(!isset($PHP_AUTH_USER)) {
			Header("WWW-Authenticate: Basic realm=\"Authentication\"");
			Header("HTTP/1.1 401 Unauthorized");
			$loginerr[] = "認証処理がキャンセルされました";
		} else {
			if ($PHP_AUTH_USER=="") {
				/* ブランクチェック */
				Header("WWW-Authenticate: Basic realm=\"Authentication\"");
				Header("HTTP/1.0 401 Unauthorized");
				$loginerr[] = "ユーザーIDが入力されていません";
			}
		}

		/* ユーザー確認 */
		$sql_login = "SELECT * FROM users where id='".$PHP_AUTH_USER."'";
		$res_login = pg_query($conn,$sql_login);

		if (pg_num_rows($res_login)>0) {
			$row_login = pg_fetch_array($res_login,0);

			if ($PHP_AUTH_PW<>$row_login["passwd"] and crypt($PHP_AUTH_PW,substr($PHP_AUTH_PW,1,2))<>$row_login["passwd"]) {
				Header("WWW-Authenticate: Basic realm=\"Authentication\"");
				Header("HTTP/1.0 401 Unauthorized");
				$loginerr[] = "パスワードまたはユーザーIDが正しくありません";
			}
		} else {
			Header("WWW-Authenticate: Basic realm=\"Authentication\"");
			Header("HTTP/1.0 401 Unauthorized");
			$loginerr[] = "ユーザーIDまたはパスワードが正しくありません";
		}

		// 変数展開
		$login       = true;
		$login_id    = $PHP_AUTH_USER;
		$no          = $row_login["seqno"];
		$login_name  = $row_login["name"];
		$login_email = $row_login["email"];
		$login_ryaku = $row_login["name_ryaku"];
		$admin_sign  = $row_login["admin_sign"];
		$serial = "?ms=";
	}

	if (empty($ms) || $mode=="login" || $mode=="dlogin") {
		$first_sign = "t";
	} else {
		$first_sign = "f";
	}

	## ログ生成
	pg_query($conn, "BEGIN;");
	$header["sql"] = "INSERT INTO tracking (";
	$header["sql"] = "serial,";
	$header["sql"] = "id,";
	$header["sql"] = "ip,";
	$header["sql"] = "url,";
	$header["sql"] = "uri,";
	$header["sql"] = "ref,";
	$header["sql"] = "agent,";
	$header["sql"] = "firstsign,";
	$header["sql"] = "calltime";
	$header["sql"] = ") values (";
	$header["sql"].= "'".$ms."',";
	$header["sql"].= "'".$login_id."',";
	$header["sql"].= "'".$remote_addr."',";
	$header["sql"].= "'".db_textsafe(textsafe($script_name,"EUC-JP"))."',";
	$header["sql"].= "'".db_textsafe(textsafe($request_uri,"EUC-JP"))."',";
	$header["sql"].= "'".db_textsafe(textsafe($referer,"EUC-JP"))."',";
	$header["sql"].= "'".db_textsafe(textsafe($agent,"EUC-JP"))."',";
	$header["sql"].= "'".$first_sign."',";
	$header["sql"].= "'now()')";
	$header["res"] = pg_query($conn,$header["sql"]);
	if (!$header["res"] || pg_affected_rows($header["res"])==0) {
		pg_query($conn,"ROLLBACK;");
	} else {
		pg_query($conn,"COMMIT;");
	}
	pg_free_result($header["res"]);

	## ヘッダーエラー表示
	if (sizeof($loginerr)>0) {
		$header["txt"].= "<font color=\"#ff0000\">";
		while (list($key,$val)=each($loginerr)) {
			$header["txt"].= "".$val."<br>";
		}
		$header["txt"].= "</font>";
		$header["txt"].= "<br>";
		$header["txt"].= "<center>&lt;<A HREF=\"".$access.$domain.$toppath."/i/\">再ログイン</a>&gt;</center>";
		$login = false;
	}
?>