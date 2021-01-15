<?php
  // システム名
  $system_shortname = "AdelieOffice"; // ← メール用
  $system_name  = "AdelieOffice Ver2.0.1";

  // サブドメイン名を獲得(ASP提供時用)
  $envHostName = getenv("HTTP_HOST");
  if (preg_match("~gw\.adelie\.ne\.jp($|:)~",$envHostName)) {
    $envHostNames = preg_split("\.",$envHostName);
    $subdomain = $envHostNames[0];
  }

  // 設置パス
#  $basedir = "/home/gw/$subdomain";
  $basedir = "/var/www";
  $basepath = $basedir."/html";

  // ドメイン名(ASP提供時用)
//  $domain = $subdomain.".gw.adelie.ne.jp";
//  $domain = "192.168.1.6/cgi-bin/cbag/ag.cgi";
  // DB関連
  $server    = "localhost";
#  $server    = "gw1.adelie.ne.jp";
  $port      = "5432"; 
  $user      = "apache";
  $pg_passwd = "password";
#  $db        = "gw_".$subdomain;
  $db        = "adelieoffice";

  $network   = "internet";

  $pgsql_connect = "";
  if ($server<>"")    { $pgsql_connect .= "host=".$server." "; }
  if ($port<>"")      { $pgsql_connect .= "port=".$port." "; }
  if ($user<>"")      { $pgsql_connect .= "user=".$user." "; }
  if ($pg_passwd<>"") { $pgsql_connect .= "password=".$pg_passwd." "; }
  if ($db<>"")        { $pgsql_connect .= "dbname=".$db." "; }

  if (empty($conn)) {
    $conn = pg_connect($pgsql_connect);
    pg_query($conn,"SET DATESTYLE TO 'ISO'");
  } else {
    pg_query($conn,"SET DATESTYLE TO 'ISO'");
  }

  $sql_admin = "SELECT * FROM admin";
  $res_admin = pg_query($conn,$sql_admin);
  if (pg_num_rows($res_admin)>0){
    $row_admin    = pg_fetch_array($res_admin,0);
    $service_name = $row_admin["service_name"];
    if ($service_name == "") $service_name = "未設定";
    $homeurl      = $row_admin["url"];
    $logoutsecond = $row_admin["logoutsecond"];
    $interval     = $row_admin["interval"];
    $restday[0]   = $row_admin["sunday"];
    $restday[1]   = $row_admin["monday"];
    $restday[2]   = $row_admin["tuesday"];
    $restday[3]   = $row_admin["wednesday"];
    $restday[4]   = $row_admin["thursday"];
    $restday[5]   = $row_admin["friday"];
    $restday[6]   = $row_admin["saturday"];
  } else {
    $homeurl      = 0;
    $service_name = "未設定";
    $logoutsecond = 3600;
    $interval     = 5;
    $restday[0]   = "t";
    $restday[1]   = "f";
    $restday[2]   = "f";
    $restday[3]   = "f";
    $restday[4]   = "f";
    $restday[5]   = "f";
    $restday[6]   = "t";
  }
	if (!is_numeric($interval) || $interval==0) $interval = 5;

  // ログの有効期限
  $logday = 90;

  // ページ幅
  $tablewidth = 740;

  // 共用フォルダの格納ディレクトリ(要777)
  $folderpath = $basedir."/folder";
  $folderpath_web = "./";
  $path_level = 5; // 作成可能な階層レベル

  $thumbnailpath = $basedir."/thumbnail"; // 2004'05/01 added by ori
  $thumbnailwidth = 160;
  $thumbnailheight = 120;

  $circularspath = $basedir."/circular";
  $workflowpath  = $basedir."/workflow";

  $foldermembers = "/メンバーディレクトリ";

  // WWWサーバー動作ドメインまたはIPアドレス(Cookie用)
  if (getenv("SERVER_PORT")==80) {
    $access = "http://";
  } else {
    $access = "https://";
  }

  //クッキー名
  $cookiename = "AdelieOffice2:login";

  //トップページへのパス
  $toppath = "";

  // 管理者メールアドレス
  $webmaster = "support@adelie.ne.jp"; 

	// 外部コマンドフルパス指定(Win環境への移行時には代替が必要となります)
	$cmd_du = "/usr/bin/du";
	$cmd_rm = "/bin/rm"; // 2004'10/06 added by ori
	
  $netpbmpath = "/usr/bin"; // 2004'05/01 added by ori
	
  // 共通 HTML設定＆画面配色パターン
  $menuobj_style="font-size:8.5pt";

  // 会議室の表示行数
  $board_rows_posted = 20; # 1ページの表示発言数
  $board_rows_thread = 20; # 1ページの表示スレッド数

  // 住所録の表示行数
  $address_rows_corp   = 15; # 1ページの表示発言数
  $address_rows_people = 15; # 1ページの表示発言数

  // 回覧板の閲覧行数
  $circular_rows = 20;

  // ワークフローの閲覧行数
  $workflow_rows = 20;

  // アップロードファイルの最大サイズ
  $upload_max = 204800000;

  // 携帯版の各種設定
  $mobile_rows = 9; // 基本値
  $mobile_bbsrows = 9;
  $mobile_bbspostrows = 9;

  // メニュー画像とテキスト
  $schedules_text = "予定表";
  $bulletins_text = "ＢＢＳ";
  $mails_text     = "伝言メモ";
  $rooms_text     = "施設予約";
  $circulas_text  = "回覧板";
  $todos_text     = "備忘録";
  $folders_text   = "共有フォルダ";
  $links_text     = "リンク集";
  $address_text   = "住所録";
  $chats_text     = "チャット";
  $workflow_text  = "ワークフロー";

  $schedules_subtext = "schedules";
  $bulletins_subtext = "bulletins";
  $mails_subtext     = "mails";
  $rooms_subtext     = "rooms";
  $circulas_subtext  = "circulas";
  $todos_subtext     = "todos";
  $folders_subtext   = "folders";
  $links_subtext     = "links";
  $address_subtext   = "address";
  $chats_subtext     = "chat";
  $workflow_subtext  = "workflow";

  $schedules_comment = "個人や所属グループのスケジュールが確認できます";
  $bulletins_comment = "討論するためのＢＢＳです";
  $mails_comment     = "個人やグループ宛にメッセージを送信することができます";
  $rooms_comment     = "会議室や施設の予約を行うことができます";
  $todos_comment     = "優先順位をつけてメモを登録しておくことができます";
  $circulas_comment  = "回覧板のように複数の相手先に情報を配信することができます";
  $folders_comment   = "パソコン上のファイルをアップロードして全員で共有することができます";
  $links_comment     = "よく使うリンクを登録しておくことができます";
  $address_comment   = "取引先や友人・知人の住所を登録しておくことができます";
  $chats_comment     = "インターネットのどこからでもチャットをすることができます";
  $workflow_comment  = "社内稟議を円滑にすすめることができます";

  $birthday_comment = "さんの誕生日です";
  $member_comment    = "メンバーの連絡先などがすぐ確認できます"; // add 20011/09/20 by nanbuwks

  $border64 = 0;
  $border32 = 0;

  $title_backcolor  = "#090727";
  $title_forecolor1 = "#330066"; // 曜日色を使うため無効
  $title_forecolor2 = "#FFFFFF";
  $title_forecolor3 = "#FFFFFF";

  $logout_backcolor = "#003366";
  $logout_forecolor = "#FFFFFF";

  $bottom_backcolor = "#006699";
  $bottom_forecolor = "#FFFFFF";

  $menu_backcolor   = "#BBCCEE";
  $menu_forecolor   = "#000000";

  $indexmenu_backcolor   = "#99CCFF";
  $indexmenu_forecolor   = "#FFFFFF";

  $bodyForeColor   = "#333366";
  $bodyLinkColor   = "#0033FF";
  $bodyVLinkColor  = "#0033FF";
  $bodyALinkColor  = "#0033FF";
  $bodyHLinkColor  = "#FF0000"; // Hover
  $bodyBackColor   = "#F4F4FF";
//  $bodyImage       = "";

  $td_back      = "#FFFFFF";
  $td_back_left = "#DDDDFF";

  $bg_dark      = "#DDDDFF";
  $bg_light     = "#EEEEFF";

  $sch_back     = "#6699FF";

  // ヘッダ用テキストのクリア
  // modigy nanbuwks 20170324
  //$headertext  = "Content-Type: text/html; charset=EUC-JP\n";
  $headertext  = "charset=UTF-8\n";
  $headertext .= "Expires: Mon, 26 Jul 1997 05:00:00 GMT\n";
  $headertext .= "Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT\n";
  $headertext .= "Cache-Control: no-cache, must-revalidate, max_age=0\n";
  $headertext .= "Pragma: no-cache\n";

	## 利用者用 HTML設定＆画面配色パターン
	$fontsize = "9";
	if (!empty($_GET["fontsize"]) && is_numeric($_GET["fontsize"])) {
		if ($_GET["fontsize"]=="8" || $_GET["fontsize"]=="9" || $_GET["fontsize"]=="10" || $_GET["fontsize"]=="11") {
			$fontsize = $_GET["fontsize"];
			$headertext.= "Set-Cookie: fontsize=$fontsize; path=/; expires=".date("l, d-M-Y H:i:s T",time()+15552000).";\n";
		}
	} else {
		if (!empty($_COOKIE["fontsize"]) && is_numeric($_COOKIE["fontsize"])) {
			$fontsize = $_COOKIE["fontsize"];
		} else {
			$fontsize = 9;
		}
	}
  $css  = "BODY,TABLE { \n";
  $css .= "FONT-SIZE:".$fontsize."pt; FONT-FAMILY: Verdana, sans-serif, 'ＭＳ Ｐゴシック', 'Osaka';\n";
  $css .= "SCROLLBAR-FACE-COLOR: #DDDDDD;\n";
  $css .= "SCROLLBAR-HIGHLIGHT-COLOR:#FFFFFF;\n";
  $css .= "OVERFLOW: AUTO;\n";
  $css .= "SCROLLBAR-SHADOW-COLOR: #777777;\n";
  $css .= "SCROLLBAR-3DLIGHT-COLOR: #777777;\n";
  $css .= "SCROLLBAR-ARROW-COLOR: #777777;\n";
  $css .= "SCROLLBAR-TRACK-COLOR: #CCCCCC;\n";
  $css .= "SCROLLBAR-DARKSHADOW-COLOR: #CCCCCC\n";
  $css .= " }\n";
#  $css .= "A:link     { text-decoration:none; font-weight:bold; color:".$bodyLinkColor."; }\n";
#  $css .= "A:active   { text-decoration:none; font-weight:bold; color:".$bodyALinkColor."; }\n";
#  $css .= "A:visited  { text-decoration:none; font-weight:bold; color:".$bodyVLinkColor."; }\n";
#  $css .= "A:hover    { text-decoration:underline; font-weight:bold; color:".$bodyHLinkColor."; }\n";
  $css .= "A:link     { text-decoration:none; font-weight:normal; color:".$bodyLinkColor."; }\n";
  $css .= "A:active   { text-decoration:none; font-weight:normal; color:".$bodyALinkColor."; }\n";
  $css .= "A:visited  { text-decoration:none; font-weight:normal; color:".$bodyVLinkColor."; }\n";
  $css .= "A:hover    { text-decoration:underline; font-weight:normal; color:".$bodyHLinkColor."; }\n";
  $css .= ".BAR { font-size: ".$fontsize."pt; color:#FFFFFF }\n";
  $css .= "A.BAR:link     { text-decoration:none; font-weight:normal; color:#FFFFFF; }\n";
  $css .= "A.BAR:active   { text-decoration:none; font-weight:normal; color:#FFFFFF; }\n";
  $css .= "A.BAR:visited  { text-decoration:none; font-weight:normal; color:#FFFFFF; }\n";
  $css .= "A.BAR:hover    { text-decoration:none; font-weight:normal; color:#FFFF00; }\n";
  $css .= ".TEXT { font-size: ".$fontsize."pt; line-height:16px; padding:5px }\n";
  $css .= ".CENTER { text-align:center }\n";
  $css .= ".HIGHLIGHT { background-color: #FFFF00; }\n";

	$css.= "TD#list_h01 {\n";
	$css.= "	font-size:9pt;\n";
	$css.= "	font-weight:bold;\n";
	$css.= "	text-align:right;\n";
	$css.= "	line-height:16pt;\n";
	$css.= "	background-color:".$td_back_left.";\n";
	$css.= "	padding-left:5px;\n";
	$css.= "	padding-right:5px;\n";
	$css.= "	white-space:nowrap;\n";
	$css.= "}\n";

  // 伝言メモ用 優先順位配列
  $priority_name  = array("高",     "やや高", "普通",   "やや低", "低");
  $priority_color = array("#FF3333","#FF6633","#FFFF66","#33FF66","#6666FF");

  // その他設定
  $pagerows = 10; //検索ページの行数

  // 汎用配列
  $COUNTRY_ARR = array("日本"); // 国名

  // 都道府県
  $STATE_ARR = array("北海道","青森県","岩手県","宮城県","秋田県","山形県","福島県",
                     "新潟県","富山県","石川県","福井県",
                     "山梨県","長野県","岐阜県",
                     "東京都","神奈川県","千葉県","埼玉県","群馬県","栃木県","茨城県",
                     "静岡県","愛知県","三重県",
                     "大阪府","京都府","兵庫県","奈良県","滋賀県","和歌山県",
                     "鳥取県","島根県","岡山県","広島県","山口県",
                     "徳島県","香川県","愛媛県","高知県",
                     "福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県",
                     "沖縄県");

  $STATE_ARR_WEATHER = array("北海道(道北)","北海道(道東)","北海道(道央)","北海道(道南)",
                     "青森県","岩手県","宮城県","秋田県","山形県","福島県",
                     "新潟県","富山県","石川県","福井県",
                     "山梨県","長野県","岐阜県",
                     "東京都","神奈川県","千葉県","埼玉県","群馬県","栃木県","茨城県",
                     "静岡県","愛知県","三重県",
                     "大阪府","京都府","兵庫県","奈良県","滋賀県","和歌山県",
                     "鳥取県","島根県","岡山県","広島県","山口県",
                     "徳島県","香川県","愛媛県","高知県",
                     "福岡県","佐賀県","長崎県","熊本県","大分県","宮崎県","鹿児島県",
                     "沖縄県");

  // 都道府県エリア
  $STATE_ARR_A = array("北海道","東北","北陸","中部","関東","東海","関西","中国","四国","九州","沖縄");

  // 都道府県エリア対応表
  $STATE_ARR_B = array("北海道",
                       "青森県\t岩手県\t宮城県\t秋田県\t山形県\t福島県",
                       "新潟県\t富山県\t石川県\t福井県",
                       "山梨県\t長野県\t岐阜県",
                       "東京都\t神奈川県\t千葉県\t埼玉県\t群馬県\t栃木県\t茨城県",
                       "静岡県\t愛知県\t三重県",
                       "大阪府\t京都府\t兵庫県\t奈良県\t滋賀県\t和歌山県",
                       "鳥取県\t島根県\t岡山県\t広島県\t山口県",
                       "徳島県\t香川県\t愛媛県\t高知県",
                       "福岡県\t佐賀県\t長崎県\t熊本県\t大分県\t宮崎県\t鹿児島県",
                       "沖縄県");

  // 曜日配列(1:月,2:火,3:水,4:木,5:金,6:土,0:日,7:祭日)
  $wname                = array("日",     "月",     "火",     "水",     "木",     "金",     "土"               );
  $wcolor_titleback     = array("#FFCCCC","#DADADA","#DADADA","#DADADA","#DADADA","#DADADA","#CCCCFF","#FFDDCC");
  $wcolor_title         = array("#FF0000","#000000","#000000","#000000","#000000","#000000","#0000FF","#FF6633");
  $wcolor_back          = array("#FFDADA","#FCFCFC","#FCFCFC","#FCFCFC","#FCFCFC","#FCFCFC","#DADAFF","#FFDADA");
  $wcolor_back_startmon = array("#FCFCFC","#FCFCFC","#FCFCFC","#FCFCFC","#FCFCFC","#DADAFF","#FFDADA","#FFDADA");
  $wcolor               = array("#FF3333","#000066","#000066","#000066","#000066","#000066","#3333FF","#FF0000");
  $wcolor_startmon      = array("#000066","#000066","#000066","#000066","#000066","#3333FF","#FF0000","#FF3333");

  $alarm_interval       = array(5,10,15,20,30,40,50,60,90,120,180);
  $alarm_interval_name  = array("5分前","10分前","15分前","20分前","30分前","40分前","50分前","1時間前","1時間半前","2時間前","3時間前");

  // 日数表(各月の月末の日数と年間の日数)
  $day_365 = array(31,28,31,30,31,30,31,31,30,31,30,31,365);
  $day_366 = array(31,29,31,30,31,30,31,31,30,31,30,31,366);
?>
