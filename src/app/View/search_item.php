<!DOCTYPE html>
<html>
<head lang="ja">
<meta charset="UTF-8">
</head>
<body>
<h3><b><?= $title ?></b></h3>
<?php $i = 1; 
foreach($objItems as $item) { ?>

  <?= $i ?>位 
  <font size="1"><?= $item->name  ?></font><br>
  <font size="1"><?= $item->price ?>円</font><br>
  <img src=" <?= $item->image ?>" width=75" height="100"><br>

<?php $i++; } ?>

</body>
</html>
