<!DOCTYPE html>
<html>
<head lang="ja">
<meta charset="UTF-8">
</head>
<body>
<h3><b>売り上げランキング：<?= $author ?></b></h3>
<?php foreach ($rakutenItem as $number => $item) : ?>
<?= $number+1 ?>位
<?= $item['title']?><br>
<img src="<?= $item['image']?>" width=75" height="100"><BR>
<?php endforeach; ?>
</body>
</html>
