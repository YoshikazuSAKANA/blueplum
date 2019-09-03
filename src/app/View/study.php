<!DOCTYPE html>
<html>
<head lang="ja">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
文字列マッチングの誤動作
<br>
%lt;strpos(ラリルレロ, 宴):
<?php
$p = strpos("ラリルレロ", "宴");
echo "answer = " . $p;
?>
<br>
mb_strpos(ラリルレロ, ロ):  
<?php
$p = mb_strpos("ラリルレロ", "ロ");
echo "answer = " . $p . "<BR>";
$test = $_POST["test"];
$test = mb_convert_encoding($test, 'EUC-JP');

$ary[] = "ASCII";
$ary[] = "JIS";
$ary[] = "EUC-JP";
echo "detect is : " . mb_detect_encoding($test, $ary, true);
$ans = mb_check_encoding($test, "UTF-8");
echo "<BR>ans  = " . $ans
?>
<form action="/study" method="post">
<input type="text" name="test" value="<?php echo htmlspecialchars($test, ENT_QUOTES, "UTF-8"); ?>">
<button type="submit">送信</button>

<iframe width="320" height="100" src="http://os3-385-25562.vs.sakura.ne.jp/xss">
</form>
</body>
</html>
