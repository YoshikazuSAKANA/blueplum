<?php
/**
 * バリデーションに関するクラス
 *
 * @author YoshikazuSakamoto
 * @category Validation
 * @package Model
*/
class Validation extends DBModel {

    /**
     * 登録フォーム画面のレスポンチ値をバリデーションのために整形
     *
     * @access public
     * @param array $postData
     * @return array $postData
     */
    public function formatPostData($postData) {

        // 姓の空白を削除
        if (!empty($postData['last_name'])) {
            $postData['last_name'] = str_replace(array(' ','　'), '', $postData['last_name']);
        }

        // 名前の空白を削除
        if (!empty($postData['first_name'])) {
            $postData['first_name'] = str_replace(array(' ','　'), '', $postData['first_name']);
        }

        // 生年月日を半角に変換
        if (!empty($postData['birthday'])) {
            $postData['birthday'] = mb_convert_kana($postData['birthday'], 'n');
        }

        // メールアドレスを半角に変換
        if (!empty($postData['mail_address'])) {
            $postData['mail_address'] = mb_convert_kana($postData['mail_address'], 'a');
        }

        // パスワードを半角に変換
        if (!empty($postData['password'])) {
            $postData['password'] = mb_convert_kana($postData['password'], 'a');
        }
        return $postData;
    }

    /**
     * 登録フォームのレスポンス値(整形後)をバリデーション.
     * 処理ごとにエラー文を格納
     *
     * @access public
     * @param array $data
     * @return array $data
     */
    public function validate($data, $updateFlg = 0) {

        // エラー文
        $error = array();

        // 姓チェック
        if (empty($data['last_name'])) {
            $error[] = '姓を入力してください';
        } else if (mb_strlen($data['last_name'] > 15)) {
            $error[] = '姓を15文字以内にしてください';
        }

        // 名前チェック
        if (empty($data['first_name'])) {
            $error[] = '名前を入力してください';
        } else if (mb_strlen($data['first_name'] > 15)) {
            $error[] = '名前を15文字以内にしてください';
        }

        // 生年月日チェック
        if (empty($data['birthday'])) {
            $error[] = '生年月日を入力してください';
        } else {
            list($y, $m, $d) = explode('/', $data['birthday']);
            if (!checkdate($m, $d, $y)) {
                $error[] = '生年月日が存在しません';
            } elseif ($y < 1900) {
                $error[] = '生年月日が正しくありません';
            }
        }

        // メールアドレスチェック
        if (empty($data['mail_address'])) {
            $error[] = 'メールアドレスを入力してください';
        } elseif (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $data['mail_address'])){
            $error[] =  'メールアドレスが正しくありません';
        } elseif (parent::checkExistMailAddress($data['mail_address']) === false && $updateFlg != 1) {
            $error[] = 'すでに同一のメールアドレスが存在します';
        }

        // パスワードチェック
        if ($updateFlg != 1) {
            $error[] = $this->validatePassword($data);
        }

        return $error;
    }

    public function validatePassword($data) {

        // パスワードチェック
        if (empty($data['password'])) {
            $error = 'パスワードを入力してください';
        } elseif (mb_strlen($data['password']) > 100) {
            $error = 'パスワードは100文字以内にしてください';
        } elseif (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $data['password'])) {
            $error = '英数字を含む8文字以上のパスワードにしてください';
        }
        return $error;
    }

}
