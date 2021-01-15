<?php
	$agent = getenv("HTTP_USER_AGENT");
	$ip    = getenv("REMOTE_ADDR");

	if (preg_match("/^DoCoMo/",$agent)) {
		//-----------
		// iモード時
		//-----------
		$agenttype = "imode";

		// 未対応機用デフォルト指定 2003/11/15以降の最低標準
		$charW      = 10;
		$charH      = 10;
		$imgW       = 120;
		$imgH       = 130;
		$color      = 256;
		$cachesize  = 10;
		$javasign   = "f";
		$javatype   = "";
		$codetype   = "HTML3.0";

		$gifsign    = "t";
		$anigifsign = "t";
		$jpegsign   = "f";
		$pngsign    = "f";
		$flashsign  = "f";
		$ifmsign    = "f";
		$menusign   = "f";

		$agents = preg_split("\/| |\(",mb_convert_encoding($agent,"EUC-JP"));
		$agentname = $agents[2];
		if ($agentname=="SH505i2") {
			$agentname = "SH505i";
		}
		if ($agentname=="P209is") {
			$agentname = "P209iS";
		}

		$row = get_row("terminal","*","carrier='i' AND \"type\"='".$agentname."'");
		if (sizeof($row)>0) {
#			$agent = $row["agent"];
			if (empty($row["authtype"])) {
				$authtype   = "";
			} else {
				$authtype   = $row["authtype"];
			}
			$charW      = $row["charwidth"];
			$charH      = $row["charheight"];
			$imgW       = $row["imagewidth"];
			$imgH       = $row["imageheight"];
			$color      = $row["color"];
			$cachesize  = $row["cachesize"];

			$javasign   = $row["javasign"];
			$javatype   = $row["javatype"];

			$codetype   = $row["codetype"];

			$gifsign    = $row["gifsign"];
			$anigifsign = $row["anigifsign"];
			$jpegsign   = $row["jpegsign"];
			$pngsign    = $row["pngsign"];
			$flashsign  = $row["flashsign"];
			$ifmsign    = $row["ifmsign"];
			$menusign   = $row["menusign"];
		} elseif ($agentname=="ISIM60") {
			$ifmsign    = "t";
			$menusign   = "t";
			$anigifsign = "t";
			$flashsign  = "t";
			$javasign   = "t";
			$javatype   = "DoJa2.0";
		}
	} elseif (preg_match("/^J\-PHONE\//",$agent)){
		//---------
		// J-SKY時
		//---------
		$agenttype = "jsky";

		// 未対応機用デフォルト指定 J-SKY対応機以降の最低標準
		$charW      = 10;
		$charH      = 10;
		$imgW       = 120;
		$imgH       = 130;
		$color      = 256;
		$cachesize  = 10;
		$javasign   = "f";
		$javatype   = "";
		$codetype   = "HTMLVer.1";

		$gifsign    = "f";
		$anigifsign = "f";
		$jpegsign   = "t";
		$pngsign    = "f";
		$flashsign  = "f";
		$ifmsign    = "f";
		$menusign   = "f";

		$agents = preg_split("\/| ",mb_convert_encoding($agent,"EUC-JP"));
		$agentname = $agents[2];

		$freeagent = true; //DB内に登録が無い印

		$row = get_row("terminal","*","carrier='v' AND \"type\"='".$agentname."'");
		if (sizeof($row)>0) {
#			$agent = $row["agent"];
			$freeagent  = false; //DBで対応済みの印

			$authtype   = "";
			$charW      = $row["charwidth"];
			$charH      = $row["charheight"];
			$imgW       = $row["imagewidth"];
			$imgH       = $row["imageheight"];
			$color      = $row["color"];
			$cachesize  = $row["cachesize"];

			$javasign   = $row["javasign"];
			$javatype   = $row["javatype"];

			$codetype   = $row["codetype"];

			$gifsign    = $row["gifsign"];
			$anigifsign = $row["anigifsign"];
			$jpegsign   = $row["jpegsign"];
			$pngsign    = $row["pngsign"];
			$flashsign  = $row["flashsign"];
			$ifmsign    = $row["ifmsign"];
			$menusign   = $row["menusign"];
		}
	} elseif (preg_match("/UP\.Browser/",$agent)){
		//---------
		// EZweb時
		//---------

		if (preg_match("/^UP\.Browser/",$agent)) {
#			// HDML機は対応外...(^L^)
#			print "お使いの携帯端末(".$agent.")には現在対応していません";
#			exit;
#			//未対応機(HDML)
			$charW      = 10;
			$charH      = 10;
			$imgW       = 96;
			$imgH       = 96;
			$color      = 16;
			$cachesize  = 5;
			$javasign   = "f";
			$javatype   = "";
			$codetype   = "HDMLx.x";

			$gifsign    = "f";
			$anigifsign = "f";
			$jpegsign   = "f";
			$pngsign    = "t";
			$flashsign  = "f";
			$ifmsign    = "f";
			$menusign   = "f";

			$agentname = "";
		} else {
			$agenttype="ezweb";
			// 未対応機用デフォルト指定 EZweb対応機以降の最低標準
			$charW      = 10;
			$charH      = 10;
			$imgW       = 120;
			$imgH       = 130;
			$color      = 256;
			$cachesize  = 10;
			$javasign   = "f";
			$javatype   = "";
			$codetype   = "XHTML1.0x";

			$gifsign    = "t";
			$anigifsign = "t";
			$jpegsign   = "t";
			$pngsign    = "t";
			$flashsign  = "f";
			$ifmsign    = "f";
			$menusign   = "f";

			$agents = preg_split("\-| ",mb_convert_encoding($agent,"EUC-JP"));
			$agentname = $agents[1];
		}
		$freeagent = true; //DB内に登録が無い印

		$row = get_row("terminal","*","carrier='z' AND \"type\"='".$agentname."'");
		if (sizeof($row)>0) {
#			$agent = $row["agent"];
			$freeagent  = false; //DBで対応済みの印
			$authtype   = "";
			$charW      = $row["charwidth"];
			$charH      = $row["charheight"];
			$imgW       = $row["imagewidth"];
			$imgH       = $row["imageheight"];
			$color      = $row["color"];
			$cachesize  = $row["cachesize"];
			$javasign   = $row["javasign"];
			$javatype   = $row["javatype"];

			$codetype   = $row["codetype"];

			$gifsign    = $row["gifsign"];
			$anigifsign = $row["anigifsign"];
			$jpegsign   = $row["jpegsign"];
			$pngsign    = $row["pngsign"];
			$flashsign  = $row["flashsign"];
			$ifmsign    = $row["ifmsign"];
			$menusign   = $row["menusign"];
		}
	} elseif (preg_match("/^210\.171\.130\.162/",$ip)){
		//---------
		// Other時
		//---------
		# ADELIE TEST用 ↓
		$agenttype  = "jsky";
		$agentname = "V601SH";

		$charW      = 10;
		$charH      = 10;
		$imgW       = 160;
		$imgH       = 180;
		$color      = 256;
		$cachesize  = 10;
		$javasign   = "t";
		$javatype   = "";
		$codetype   = "CHTML2.0";

		$gifsign    = "f";
		$anigifsign = "f";
		$jpegsign   = "t";
		$pngsign    = "f";
		$flashsign  = "f";
		$ifmsign    = "t";
		$menusign   = "f";
	} elseif (preg_match("/^Mozilla/",$agent)){
		print "このディレクトリは通常のブラウザから閲覧することはできません。iモード端末から利用してください";
		exit;
		# TEST用 ↓
		$authtype = "";
		$char_w=20;
		$char_h=10;
		$imgW = $char_w / 2 * 12; #?
		$imgH = $char_h *  8; #?
		$color=4096;
		$agenttype="jsky";
	} else {
		print "お使いの携帯端末(".$agent.")には現在対応していません";
		exit;
	}

	// 画像幅の調整
	if ($agenttype=="imode") {
		if ($color<256) { // モノクロ機は全て96ピクセル画像を使わせる
			$imgW = 96;
		} elseif ($cachesize<10) { // キャッシュが10K以下なら96ピクセルを使わせる
			$imgW = 96;
		} else {
			if ($jpegsign=="f" && $imgW>120) { // JPEGが使えない機種には120幅画像を使わせる
				$imgW = 120;
			}
			if ($jpegsign=="t" && $imgW<132) { // JPEGが使えても132幅以下の機種にはGIFを使わせる
				$jpegsign = "f";
			}
			if ($cachesize<20 && $imgW>160) { // キャッシュが20K以下の機種には160幅画像を使わせる
				$imgW = 160;
			}
		}
	}

	if ($agenttype=="jsky") {
		if ($cachesize<12 && $imgW>120) { // キャッシュが12K以下の機種(6kとか)には120幅画像を使わせる
			$imgW = 120;
		}
		if ($cachesize>=12 && $cachesize<200 && $imgW>160) { // キャッシュが200K以下の機種には160幅画像を使わせる
			$imgW = 160;
		}
	}

	// 画像幅のセット
	if ($imgW<120) {
		$imgsize = "96";
	} elseif ($imgW<132) {
		$imgsize = "120";
	} elseif ($imgW<160) {
		$imgsize = "132";
	} elseif ($imgW<176) {
		$imgsize = "160";
	} elseif ($imgW<240) {
		$imgsize = "176";
	} else {
		$imgsize = "240";
	}
	if ($color<256) {
		$imgsize .= "g";
		if ($imgW>120) {
		  $imgsize = "120g";
		}
	}

	## グループウェア用カスタマイズ
	if ($agenttype=="imode") {
		$imgext = "gif";
		$image_extension = "gif";
	} elseif ($agenttype=="ezweb") {
		$imgext = "gif";
		$image_extension = "gif";
	} elseif ($agenttype=="jsky") {
		$imgext = "png";
		$image_extension = "png";
	} else {
		$imgext = "gif";
		$image_extension = "gif";
	}
?>