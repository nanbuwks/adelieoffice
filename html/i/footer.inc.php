<?php
	$footer["txt"] = "";
	if ($login) {
		$footer["txt"].= "<hr size=\"0\">";
		if (!preg_match("^\/i\/(index|$|\?)", $_SERVER["REQUEST_URI"])) {
			$footer["txt"].= "<div align=\"right\">";
			$footer["txt"].= "<a href=\"/i/".$serial."\" ".$accesskey."=\"#\">";
			if ($agenttype=="jsky") {
				$footer["txt"].= "#.";
			}
			$footer["txt"].= "TOP</a>";
			$footer["txt"].= "</div>";
		}
	}
	$footer["txt"].= "</body>";
	$footer["txt"].= "</html>";

	## 半角処理
	$header["txt"] = mb_convert_kana($header["txt"],"aks","EUC-JP");
	$txt  = mb_convert_kana($txt,"aks","EUC-JP");
	$footer["txt"] = mb_convert_kana($footer["txt"],"aks","EUC-JP");

	## 文字コード変換
	$header["txt"] = mb_convert_encoding($header["txt"],"SJIS","EUC-JP");
	$txt  = mb_convert_encoding($txt,"SJIS","EUC-JP");
	$footer["txt"] = mb_convert_encoding($footer["txt"],"SJIS","EUC-JP");

	## 絵文字処理
	$header["txt"] = replace_emoji($header["txt"]);
	$txt  = replace_emoji($txt);
	$footer["txt"] = replace_emoji($footer["txt"]);

	## ページ出力
	mb_http_output("pass");
	if (!$noheader) {
		header("Content-Type: text/html; charset=Shift_JIS");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-cache");
		header("Pragma: no-cache");
	}

	print $header["txt"];
	if ($login) { print $txt; }
	print $footer["txt"];
?>
