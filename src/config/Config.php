<?php

date_default_timezone_set('Asia/Tokyo');
require_once("/home/y/share/pear/secret.php");
require_once("/home/y/share/pear/blueplum/src/common.php");
/*************************************************
 * 初期設定ファイル
 *
 */

//----------------------------------------------------
// デバッグ表示 true / デバッグ表示オフfalse
//----------------------------------------------------

// define("_DEBUG_MODE", true); 

define("_DEBUG_MODE", false); 

define("_ROOT_DIR", "/home/y/share/pear/blueplum/src");


//----------------------------------------------------
// データベース関連
//----------------------------------------------------

// データベース接続ユーザー名
define("_DB_USER", "admin");

// データベース接続パスワード
define("_DB_PASS", _DB_PASSWORD);

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
define("_TMP_DIR", _ROOT_DIR . "/htdocs/tmp/");

// 環境変数 
define( "_SCRIPT_NAME", $_SERVER['SCRIPT_NAME']);
define('_HEADERS', 'From: huitawarosu@yahoo.co.jp');

//----------------------------------------------------
// クラスファイルの読み込み
//----------------------------------------------------
// 読み込みの順番を変えると動作しません。
require_once( _MODEL_DIR      . "/BaseModel.php");
require_once( _MODEL_DIR      . "/DBModel.php");
require_once( _MODEL_DIR      . "/Auth.php");
require_once( _MODEL_DIR      . "/Validation.php");
require_once( _MODEL_DIR      . "/AdminModel.php");
require_once( _MODEL_DIR      . "/Logger.php");
require_once( _MODEL_DIR      . "/UserModel.php");
require_once( _MODEL_DIR      . "/UserDao.php");

//----------------------------------------------------
// ルーティング設定
//---------------------------------------------------

$conf = [
          ['GET', '/', 'IndexController', 'IndexAction'],
          ['GET', '/signin', null, 'PageLoadAction', _VIEW_DIR . '/signin.html'],
          ['GET', '/signup', null, 'PageLoadAction', _VIEW_DIR . '/signup.html'],
          ['GET', '/calendar', null, 'PageLoadAction', _VIEW_DIR . '/calendar.html'],
          ['GET', '/error', null, 'PageLoadAction', _VIEW_DIR . '/error.html'],
          ['POST', '/user/signin', 'UserController', 'SigninAction'],
          ['POST', '/user/signup', 'UserController', 'SignupAction'],
          ['GET', '/product/:id', 'ProductController', 'dispCarDetailAction'],
          ['POST', '/user/logout', 'UserController', 'LogoutAction'],
          ['GET', '/mypage/:id', 'UserController', 'MyPageAction'],
          ['GET', '/admin', null, 'PageLoadAction', _VIEW_DIR . '/admin_top.html'],
          ['POST', '/admin', 'AdminController', 'SigninAction'],
          ['POST', '/admin_regist', null, 'PageLoadAction', _VIEW_DIR . '/admin_regist.html'],
          ['GET', '/admin_signin', null, 'PageLoadAction', _VIEW_DIR . '/admin_signin.html'],
          ['GET', '/admin_user_list', 'AdminController', 'dispUserListAction'],
          ['GET', '/admin_admin_list', 'AdminController', 'dispAdminListAction'],
          ['GET', '/admin_user_data/:id', 'AdminController', 'dispUserDetailAction'],
          ['POST', '/admin_confirm_user_data', 'AdminController', 'confirmUserDetailAction'],
          ['POST', '/admin_update_user_data', 'AdminController', 'updateUserAction'],
          ['GET', '/admin_delete_confirm_user/:id', 'AdminController', 'dispUserDetailAction'],
          ['POST', '/admin_delete_user', 'AdminController', 'deleteUserAction'],
          ['POST', '/entry_task', 'UserController', 'entryTaskAction'],
          ['POST', '/user/done_task', 'UserController', 'doneUserTaskAction'],
          ['GET', '/admin_test_cron', null, 'PageLoadAction', _VIEW_DIR . '/admin_test_cron.html'],
          ['POST', '/search_book',  null, 'PageLoadAction', _VIEW_DIR . '/search_book.php'],
          ['GET', '/api/search/zipcode/:zipcode', 'WebAPI', 'searchZipCodeAction'],
          ['GET', '/test_curl', 'WebAPI', 'curlAction'],
          ['GET', '/study', null, 'PageLoadAction', _VIEW_DIR . '/study.php'],
          ['POST', '/study', null, 'PageLoadAction', _VIEW_DIR . '/study.php'],
          ['GET', '/xss', null, 'PageLoadAction', _VIEW_DIR . '/xss.php'],
          ['POST', '/xss', null, 'PageLoadAction', _VIEW_DIR . '/xss.php']
        ];

