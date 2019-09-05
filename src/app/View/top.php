<?php
$sortKind = [
    '標準'               => 'standard',
    '売れている'         => 'sales',
    '発売日(古い)'       => '+releaseDate',
    '発売日(新しい)'     => '-releaseDate',
    '価格が安い'         => '+itemPrice',
    '価格が高い'         => '-itemPrice',
    'レビュー件数が多い' => 'reviewCount',
    'レビュー評価が高い' => 'reviewAverage'
];
/*
$url = 'http://weather.livedoor.com/forecast/webservice/json/v1?city=400040';
// cURLセッションを初期化
$curlHandle = curl_init();

// cURL転送オプション設定
curl_setopt($curlHandle, CURLOPT_URL, $url);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

// cURLセッション実行
$result = curl_exec($curlHandle);
print_r(json_decode($result));
// cURLセッション閉じる
curl_close($curlHandle);
*/
?>
<!DOCTYPE html>
<html>
<head lang="ja">
<meta charset="UTF-8">
<title>todoist</title>
<link rel="icon" href="http://os3-385-25562.vs.sakura.ne.jp/image/favicon.ico">
<link rel="stylesheet" type="text/css" href="/css/top.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<!-- Rakuten Web Services Attribution Snippet FROM HERE -->
<a href="https://webservice.rakuten.co.jp/" target="_blank"><img src="https://webservice.rakuten.co.jp/img/credit/200709/credit_31130.gif" border="0" alt="楽天ウェブサービスセンター" title="楽天ウェブサービスセンター" width="311" height="30"/></a>
<!-- Rakuten Web Services Attribution Snippet TO HERE -->
<div class="header">
  <div class="td-brand">
    <img alt="reiwa" src="http://os3-385-25562.vs.sakura.ne.jp/image/reiwa_happy.png" width="100" height="100">
  </div>
  <div class="td-header__action-holder">
    <ul class="header__actions">
    <?php if (empty($_SESSION['user_name'])) { ?>
      <li class="header__action">
        <a href="/signin">ログイン</a>
      </li>
      <li class="header__action">
        <a href="/signup">サインアップ</a>
      </li>
    <?php } else { ?>
    <li class="header__action">
        <form name="logout" action="/user/logout" method="post">
          <input type="hidden" name="token" value="<?php echo session_id(); ?>">
          <a href="javascript:logout.submit()">ログアウト</a>
        </form>
      </li>
    <?php } ?>
    </ul>
  </div>
</div>

<?php if (!empty($_SESSION['user_name'])) { ?>
<b><?=$_SESSION['user_name']?></b>さんようこそ
<br>
<a href="/mypage/<?=$_SESSION['user_id']?>">マイページ</a>
<a href="/calendar">カレンダー</a>
2019/3/20　始動
<p id="RealTimeClockArea"></p>
<?php } ?>
<p id="LimitTimeArea"></p>
<b>著者で検索</b>
<form action="/search_books" method="post">
<input type="text" name="author">
<?php foreach($sortKind as $key => $sort) : ?>
<?php if ($sort == 'standard') { ?>
<input type="radio" name="sort" value="standard" checked><font size="1">標準</font>
<?php } else { ?>
<input type="radio" name="sort" value="<?= h($sort) ?>"><font size="1"><?= $key ?></font>
<?php } ?>
<?php endforeach; ?>
<button type="submit">検索</button>
</form>
<br>
<b>商品検索（YAHOOと楽天）</b>
<form action="search_item" method="POST">
<input type="text" name="item">
<button type="subimit">検索</button>
</form>
<br><br>
<a href="/study">学習一覧</a>
<br>
<a href="/admin_signin">管理者ログイン</a>
<script type="text/javascript" src="/js/top.js"></script>
<script type="text/javascript" src="/js/jquery.js"></script>
</body>
</html>
