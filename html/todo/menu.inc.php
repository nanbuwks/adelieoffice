<?php

if (empty($todo_s_sort)) { $todo_s_sort = "priority"; }
$today = getdate();

$menutext .= "<TABLE CELLPADDING=1 CELLSPACING=0 BORDER=0 WIDTH=160 BGCOLOR=#666666><FORM name=input_form ACTION=\"$toppath/todo/\" METHOD=POST>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD><TABLE CELLPADDING=4 CELLSPACING=0 BORDER=0 WIDTH=158 BGCOLOR=#666666>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD BGCOLOR=#999999><A HREF=\"$toppath/todo/\" STYLE=\"color:#FFFFFF\"><IMG SRC=\"$toppath/image/todo.gif\" ALIGN=ABSMIDDLE ALT=\"備忘録\" BORDER=0> <A HREF=\"$toppath/todo/\"><FONT COLOR=#FFFFFF>備忘録</A></TD>\n";
$menutext .= "</TR>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD STYLE=\"line-height:15px\" BGCOLOR=#FFFFFF VALIGN=TOP><TABLE CELLPADDING=1 CELLSPACING=0 BORDER=0 WIDTH=150>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD WIDTH=50 valign=top><nobr>優先度&nbsp;&nbsp;<select style=\"vertical-align: top;\" name=\"s_priority\">\n";
$selected[$todo_s_priority] = " selected";
$menutext .= "<option value=0".$selected[0].">全て</option>\n";
$menutext .= "<option value=3".$selected[3].">普通</option>\n";
$menutext .= "<option value=2".$selected[2].">やや高</option>\n";
$menutext .= "<option value=1".$selected[1].">高</option></nobr></TD>\n";
unset($selected);
$menutext .= "</TR>\n";
$menutext .= "<tr height=1>\n";
$menutext .= "<td colspan=2><hr size=1></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD valign=top>開始日</td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr>\n";
$menutext .= "<td valign=top align=left><nobr><input type=text name=from_begin_year value=\"".$todo_s_from_begin_year."\" style=\"width: 38px\">/<select name=from_begin_month>\n";
$selected[$todo_s_from_begin_month] = " selected";
for ($i = 1; $i <= 12; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>/<select name=\"from_begin_day\">\n";
$selected[$todo_s_from_begin_day] = " selected";
for ($i = 1; $i <= 31; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr>\n";
$menutext .= "<td valign=\"top\" align=\"right\"><nobr>〜<input type=text name=\"to_begin_year\" value=\"".$todo_s_to_begin_year."\" style=\"width: 38px\">/<select name=to_begin_month>\n";
$selected[$todo_s_to_begin_month] = " selected";
for ($i = 1; $i <= 12; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>/<select name=\"to_begin_day\">\n";
$selected[$todo_s_to_begin_day] = " selected";
for ($i = 1; $i <= 31; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select></td><td>\n";
$menutext .= "</select></td>\n";
$menutext .= "</TR>\n";
$menutext .= "<tr height=1>\n";
$menutext .= "<td colspan=2><hr size=1></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<script language=\"javascript\">\n";
$menutext .= "function chkbox_click(f){\n";
$menutext .= "flg = f.view_end_flg.checked;\n";
$menutext .= "if (flg==true) {\n";
$menutext .= "	f.use_end_flg.value = 't';\n";
$menutext .= "} else {\n";
$menutext .= "	f.use_end_flg.value = 'f';\n";
$menutext .= "}\n";
$menutext .= "f.from_end_year.disabled = flg;\n";
$menutext .= "f.from_end_month.disabled = flg;\n";
$menutext .= "f.from_end_day.disabled = flg;\n";
$menutext .= "f.to_end_year.disabled = flg;\n";
$menutext .= "f.to_end_month.disabled = flg;\n";
$menutext .= "f.to_end_day.disabled = flg;\n";
$menutext .= "}\n";
$menutext .= "</script>\n";
$menutext .= "<TR>\n";
if ($todo_s_use_end_flg == "on"){
	$checked = " checked";
	$disabled = " disabled";
}
$menutext .= "<td valign=top><nobr>締切日&nbsp;&nbsp;<input type=\"checkbox\" name=view_end_flg".$checked." onclick=\"chkbox_click(this.form);\">未設定</nobr><input type=hidden name=use_end_flg VALUE=\"\"></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr>\n";
$menutext .= "<td valign=top align=left><nobr><input type=text value=\"".$todo_s_from_end_year."\" name=from_end_year".$disabled." style=\"width: 38px\">/<select name=from_end_month".$disabled.">\n";
$selected[$todo_s_from_end_month] = " selected";
for ($i = 1; $i <= 12; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>/<select name=from_end_day".$disabled.">\n";
$selected[$todo_s_from_end_day] = " selected";
for ($i = 1; $i <= 31; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select></td>\n";
$menutext .= "</TR>\n";
$menutext .= "<tr>\n";
$menutext .= "<td valign=top align=right><nobr>〜<input type=text name=to_end_year value=\"".$todo_s_to_end_year."\"".$disabled." style=\"width: 38px\">/<select name=to_end_month".$disabled.">\n";
$selected[$todo_s_to_end_month] = " selected";
//print_r($selected);
for ($i = 1; $i <= 12; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>/<select name=to_end_day".$disabled.">\n";
$selected[$todo_s_to_end_day] = " selected";
for ($i = 1; $i <= 31; $i++){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr height=1>\n";
$menutext .= "<td colspan=2><hr size=1></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD valign=top>達成率</td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr>\n";
$menutext .= "<td valign=top><nobr>最低<select name=progress_min>\n";
$selected[$todo_s_progress_min] = " selected";
for ($i = 0; $i <= 100; $i = $i + 10){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>％</td>\n";
$menutext .= "</tr>\n";
$menutext .= "<tr>\n";
$menutext .= "<td align=right>〜最高<select name=progress_max>\n";
$selected[$todo_s_progress_max] = " selected";
for ($i = 0; $i <= 100; $i = $i + 10){
	$menutext .= "<option value=".$i.$selected[$i].">".$i."</option>";
}
unset($selected);
$menutext .= "</select>％<nobr></td>\n";
$menutext .= "</TR>\n";
$menutext .= "<tr height=1>\n";
$menutext .= "<td colspan=2><hr size=1></td>\n";
$menutext .= "</tr>\n";
$menutext .= "<TR>\n";
$menutext .= "<TD valign=top><nobr>タイトル&nbsp;&nbsp;<input type=\"text\" name=title value=\"".$todo_s_title."\" style=\"width: 70px;\">を含む</nobr><td>\n";
$menutext .= "</TR>\n";
$menutext .= "</TABLE></TD>\n";
$menutext .= "</TR>\n";
$menutext .= "</TABLE></TD>\n";
$menutext .= "</TR>\n";
$menutext .= "</TABLE></TD>\n";
$menutext .= "</TR>\n";
$menutext .= "<tr>\n";
$menutext .= "<td align=right><input type=submit value=\"検索\"><input type=button name=reset onclick=\"javascript:input_form_reset();\"value=\"クリア\">\n";
$menutext .= "<script language=\"javascript\">\n";
$menutext .= "function input_form_reset(){\n";
$menutext .= "document.input_form.s_priority.value=0;\n";
$menutext .= "document.input_form.from_begin_year.value=".$today["year"].";\n";
$menutext .= "document.input_form.from_begin_month.value=1;\n";
$menutext .= "document.input_form.from_begin_day.value=1;\n";
$menutext .= "document.input_form.to_begin_year.value=".$today["year"].";\n";
$menutext .= "document.input_form.to_begin_month.value=12;\n";
$menutext .= "document.input_form.to_begin_day.value=31;\n";
$menutext .= "document.input_form.s_use_end_flg.checked=\"on\";\n";
$menutext .= "document.input_form.from_end_year.value=".$today["year"].";\n";
$menutext .= "document.input_form.from_end_month.value=1;\n";
$menutext .= "document.input_form.from_end_day.value=1;\n";
$menutext .= "document.input_form.to_end_year.value=".$today["year"].";\n";
$menutext .= "document.input_form.to_end_month.value=12;\n";
$menutext .= "document.input_form.to_end_day.value=31;\n";
$menutext .= "document.input_form.progress_min.value=0;\n";
$menutext .= "document.input_form.progress_max.value=90;\n";
$menutext .= "document.input_form.title.value=\"\";\n";
$menutext .= "}\n";
$menutext .= "</script>\n";

?>