<?php
/*************************************************
 * 初期設定ファイル
 *
 */

//----------------------------------------------------
// デバッグ表示 true / デバッグ表示オフfalse
//----------------------------------------------------

// define("_DEBUG_MODE", true); 

define("_DEBUG_MODE", false); 

define("_ROOT_DIR", "/home/y/share/pear/ymols/src");


//----------------------------------------------------
// データベース関連
//----------------------------------------------------

// データベース接続ユーザー名
define("_DB_USER", "admin");

// データベース接続パスワード
define("_DB_PASS", "Oniram_0622");

// データベースホスト名
define("_DB_HOST", "os3-385-25562.vs.sakura.ne.jp");

// データベース名
define("_DB_NAME", "car");

// データベースの種類
define("_DB_TYPE", "mysql");

// データソースネーム
define("_DSN", _DB_TYPE . ":host=" . _DB_HOST . ";dbname=" . _DB_NAME. ";charset=utf8");


//----------------------------------------------------
// セッション名
//----------------------------------------------------

// 会員用セッション名
define("_MEMBER_SESSNAME", "PHPSESSION_MEMBER");

// 管理者用セッション名
define("_SYSTEM_SESSNAME", "PHPSESSION_SYSTEM");

// 会員用認証情報 保管変数名
define("_MEMBER_AUTHINFO", "userinfo");

// 管理者用認証情報 保管変数名
define("_SYSTEM_AUTHINFO", "systeminfo");


//----------------------------------------------------
// 会員・管理者　処理分岐用
//----------------------------------------------------

// 会員用フラッグ
define("_MEMBER_FLG", false);

// 管理者フラッグ
define("_SYSTEM_FLG", true);


//----------------------------------------------------
// ファイル設置ディレクトリ
//----------------------------------------------------

// 外部でファンクションなど
define( "_PHP_LIBS_DIR", _ROOT_DIR . "/library");

// MVCモデル
define("_MODEL_DIR", _ROOT_DIR . "/app/Model");
define("_VIEW_DIR", _ROOT_DIR . "/app/View");
define("_CONTROLLER_DIR", _ROOT_DIR . "/app/Controller");

// 環境変数 
define( "_SCRIPT_NAME", $_SERVER['SCRIPT_NAME']);


//----------------------------------------------------
// クラスファイルの読み込み
//----------------------------------------------------
// 読み込みの順番を変えると動作しません。
require_once( _MODEL_DIR      . "/BaseModel.php");
require_once( _MODEL_DIR      . "/DBModel.php");

//----------------------------------------------------
// ルーティング設定
//--

$conf = [
          ['GET', '/', 'IndexController', 'IndexAction'],
          ['GET', '/signin', 'UserController', 'PageLoadAction', _VIEW_DIR . '/signin.html'],
          ['GET', '/signup', 'UserController', 'PageLoadAction', _VIEW_DIR . '/signup.html'],
          ['POST', '/user/signin', 'UserController', 'SigninAction'],
          ['POST', '/user/signup', 'UserController', 'SignupAction'],
          ['GET', '/product/:id', 'ProductController', 'dispCarDetailAction'],
        ];


