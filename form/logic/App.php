<?php
/**
 * Appクラス
 */
class App {
	// 管理写メールアドレス
	protected $admin_mail;
	
	// 返信メールの有無
	protected $return_mail;
	
	// 管理者へのメールタイトル
	protected $admin_mail_subject;
	// ユーザーへのメールタイトル
	protected $user_mail_subject;
	
	// 管理者へのメール本文タイトル
	protected $admin_mail_text_subject;
	// ユーザーへのメール本文タイトル
	protected $user_mail_text_subject;
	
	// メール送信エラー文
	protected $send_mail_error_text;
	
	// メールランゲージ
	protected $mail_language;
	// メールエンコード
	protected $mail_encode;
	
	// ユーティリティーインスタンス格納用
	protected $utilInstance;
	
	/* ワンタイムチケット */
	protected $one_time_ticket;
	
	/* ワンタイムチケット用インプット名 */
	protected $one_time_ticket_name;
	
	/* フォーム名 */
	protected $form_name;
	
	// フォーム作成データ
	protected $form_create_data;
	
	// 入力チェック用
	protected $inputDataCheck;
	
	// インプットデータエラー文
	protected $inputErrorText;
	
	/* 不正リンク用インプット名 */
	protected $link_check_referer;
	
	// フォームモード
	protected $form_mode_input;
	protected $form_mode_confirm;
	protected $form_mode_finish;
	
	/* フォームファイル */
	protected $input_file_name;
	protected $confirm_file_name;
	protected $finish_file_name;
	
	/* フォーム画面名 */
	protected $input_name;
	protected $confirm_name;
	protected $finish_name;
	
	/* タイプ */
	protected $type_hidden;
	protected $type_text;
	protected $type_textarea;
	
	// 戻るボタンのテキスト
	protected $back_button_text;
	
	// 完了画面テキスト
	protected $finish_text;
	
	/**
	 * コンストラクタ
	 * @param 	$config		// configオブジェクト
	 */
	function __construct($config) {
		// セッションスタート
		session_start();
		
		// ユーティリティーインスタンス生成
		$this->utilInstance = new Utility();
		
		// 管理写メールアドレス
		$this->admin_mail = $config->ADMIN_MAIL;
		
		// 管理者へのメールタイトル
		$this->admin_mail_subject = $config->ADMIN_MAIL_SUBJECT;
		// ユーザーへのメールタイトル
		$this->user_mail_subject  = $config->USER_MAIL_SUBJECT;
		
		// 管理者へのメール本文タイトル
		$this->admin_mail_text_subject = $config->ADMIN_MAIL_TEXT_SUBJECT;
		// ユーザーへのメール本文タイトル
		$this->user_mail_text_subject  = $config->USER_MAIL_TEXT_SUBJECT;
		
		// メール送信エラー文
		$this->send_mail_error_text = $config->SEND_MAIL_ERROR_TEXT;
		
		// メールランゲージ
		$this->mail_language = $config->MAIL_LANGUAGE;
		// メールエンコード
		$this->mail_encode = $config->MAIL_ENCODE;
		
		// ワンタイムチケットの生成
		$this->one_time_ticket = md5(uniqid(mt_rand(), TRUE));
		$_SESSION['one_time_ticket'][] = $this->one_time_ticket;
		/* ワンタイムチケット用インプット名 */
		$this->one_time_ticket_name = $config->ONE_TIME_TICKET_NAME;
		
		/* フォーム名 */
		$this->form_name = $config->form_name;
		
		// フォームデータ格納
		$this->form_create_data = $config->form_create_data;
		
		// 入力チェック用
		$this->inputDataCheck = $config->inputDataCheck;
		
		// インプットデータエラー文
		$this->inputErrorText = $config->inputErrorText;
		
		/* 不正リンク用インプット名 */
		$this->link_check_referer = $config->LINK_CHECK_REFERER;
		
		// フォームモード
		$this->form_mode_input   = $config->FORM_MODE_INPUT;
		$this->form_mode_confirm = $config->FORM_MODE_CONFIRM;
		$this->form_mode_finish  = $config->FORM_MODE_FINISH;
		
		/* フォームファイル */
		$this->input_file_name   = $config->INPUT_FILE_NAME;
		$this->confirm_file_name = $config->CONFIRM_FILE_NAME;
		$this->finish_file_name  = $config->FINISH_FILE_NAME;
		
		/* フォーム画面名 */
		$this->input_name   = $config->INPUT_NAME;
		$this->confirm_name = $config->CONFIRM_NAME;
		$this->finish_name  = $config->FINISH_NAME;
		
		/* タイプ */
		$this->type_hidden   = $config->TYPE_HIDDEN;
		$this->type_text     = $config->TYPE_TEXT;
		$this->type_textarea = $config->TYPE_TEXTAREA;
		
		// 戻るボタンのテキスト
		$this->back_button_text = $config->BACK_BUTTON_TEXT;
		
		// 完了画面テキスト
		$this->finish_text = $config->FINISH_TEXT;
	}
}