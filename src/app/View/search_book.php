<?php

function getAuthorBooks($author) {

    $baseUrl = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404?';
    $paramUrl = null;
    $param = [];
    $rakutenItem = [];

    $param['applicationId'] = '1025653256501622754';
    $param['affiliateId'] = '1d009b0.0dfb50a9.18d009b1.13448471';
    $param['author'] =  $author;
    $param['hits'] = 30;
    $param['page'] = 9;
    $param['carrier'] = 0;
    $param['formatVersion'] = 2;
    $param['format'] = 'json';
    $param['sort'] = 'sales';

    foreach($param as $key => $value) {
        $paramUrl .= '&' . $key . '=' . $value;
    }
    $paramUrl = substr($paramUrl, 1);
    $requestUrl = $baseUrl . $paramUrl;

    $rakutenContent = json_decode(file_get_contents($requestUrl), true);
    $i = 0;
    foreach($rakutenContent['Items'] as $itemNumber => $items) {
        foreach($items as $item) {
            $rakutenItem[$i]['title'] = $item['title'];
            $rakutenItem[$i]['image'] = $item['mediumImageUrl'];
        }
        $i++;
    }
    return $rakutenItem;
}

$author = mb_convert_kana(htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8'), 's');
$rakutenItem = getAuthorBooks($author);
?>

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
