<?php
class Config {
	// 管理写メールアドレス
	public $ADMIN_MAIL = "yourmail";
	
	// 返信メールの有無
	public $RETURN_MAIL = true;
	
	// 管理者へのメールタイトル
	public $ADMIN_MAIL_SUBJECT = "お問い合わせがありました";
	// ユーザーへのメールタイトル
	public $USER_MAIL_SUBJECT  = "お問い合わせ（自動返信）";
	
	// 管理者へのメール本文タイトル
	public $ADMIN_MAIL_TEXT_SUBJECT = "以下の内容でのお問い合わせがありました";
	// ユーザーへのメール本文タイトル
	public $USER_MAIL_TEXT_SUBJECT  = "お問い合わせありがとうございます\n以下の内容でお問合せを受け付けました。";
	
	// メール送信エラー文
	public $SEND_MAIL_ERROR_TEXT = "両方エラーによりメールが送信できません。<br>管理者にお問い合わせください。";
	
	// メールランゲージ
	public $MAIL_LANGUAGE = "Japanese";
	// メールエンコード
	public $MAIL_ENCODE = "UTF-8";
	
	/* フォーム名 */
	public $form_name = "contact_form";
	
	/* フォームファイル */
	public $INPUT_FILE_NAME   = "input.php";
	public $CONFIRM_FILE_NAME = "confirm.php";
	public $FINISH_FILE_NAME  = "finish.php";
	/* フォーム画面名 */
	public $INPUT_NAME   = "input";
	public $CONFIRM_NAME = "confirm";
	public $FINISH_NAME  = "finish";
	
	/* 不正リンク用インプット名 */
	public $LINK_CHECK_REFERER = "referer";
	
	/* ワンタイムチケット用インプット名 */
	public $ONE_TIME_TICKET_NAME    = "one_time_ticket";
	
	/* モード */
	public $FORM_MODE_INPUT   = 1;
	public $FORM_MODE_CONFIRM = 2;
	public $FORM_MODE_FINISH  = 3;
	
	/* タイプ */
	public $TYPE_HIDDEN 	= "hidden";
	public $TYPE_TEXT     = "text";
	public $TYPE_TEXTAREA = "textarea";
	
	/* メール項目関連 */
	public $form_create_data = array(
										99 => array("name" => "referer", "type" => "hidden", "label" => ""),
										88 => array("name" => "one_time_ticket", "type" => "hidden", "label" => ""),
										1  => array("name" => "name", "type" => "text", "label" => "お名前"),
										2  => array("name" => "mail", "type" => "text", "label" => "メールアドレス"),
										3  => array("name" => "website", "type" => "text", "label" => "Webサイト"),
										4  => array("name" => "message", "type" => "textarea", "label" => "メッセージ"),
									 );
	
	/* 入力チェック（メール項目関連nameに対応） */
	public $inputDataCheck = array(
										"name"     => array("name" => "お名前", "required" => 1, "mail" => 0, "strnum" => 50),
										"mail"     => array("name" => "メールアドレス", "required" => 1, "mail" => 1, "strnum" => 100),
										"website"  => array("name" => "Webサイト", "required" => 0, "mail" => 0, "strnum" => 100),
										"message"  => array("name" => "メッセージ", "required" => 1, "mail" => 0, "strnum" => 500),
									);
	
	// インプットデータエラー文
	public $inputErrorText = array(
										"required" => "が入力されていません",
										"mail"     => "の形式が違います",
										"strnum"   => "は文字数オーバーしています",
									);
	
	// 戻るボタンのテキスト
	public $BACK_BUTTON_TEXT = "戻る";
	
	// 完了画面テキスト
	public $FINISH_TEXT = "送信いたしました";
}
?>