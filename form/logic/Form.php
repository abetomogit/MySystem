<?php
include_once 'App.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/my_system/form/utility/Utility.php';

/**
 * Formクラス
 */
class Form extends App {
	
	/**
	 * フォーム開始
	 * @param $mode				// 1:input, 2:confirm, 3:finish
	 * @param $method			// フォームのメソッド（post/get）
	 * @param $action			// フォームのアクション
	 * @param $submit_text		//　submitボタンのテキスト
	 * @return 	$form_tag		// フォームタグ
	 */
	public function startForm($mode, $action = null, $submit_text = null, $method = "post") {
		
		
		// inputなら直リンク確認はなし
		if ($mode == $this->form_mode_input) {
			$form_tag = self::createForm($mode, $this->form_name, $method, $action, $submit_text, $_POST);
		} else {
			if ($_POST) {
				$form_tag = self::createForm($mode, $this->form_name, $method, $action, $submit_text, $_POST);
			} else {
				header("Location:".$this->input_file_name);
				exit;
			}
		}
		return $form_tag;
	}
	
	/**
	 * フォームタグ作成
	 * @param 	$mode					// 1:input, 2:confirm, 3:finish
	 * @param 	$name					// フォーム名
	 * @param 	$method					// フォームのメソッド（post/get）
	 * @param 	$action					// フォームのアクション
	 * @param 	$submit_button_text		//　submitボタンのテキスト
	 * @param 	$post_data				// ポストデータ
	 * @return 	$form_tag				// フォームタグ
	 */
	private function createForm($mode, $name, $method = null, $action = null, $submit_text = null, $post_data = null) {
		
		switch ($mode) {
			case $this->form_mode_input:
				$form_tag = "";
				
				// エラー用div開始
				if (isset($_SESSION['input_error'])) {
					if (count($_SESSION['input_error']) != 0) {
						$form_tag .= "<div class='error'>\n";
						// 入力チェックがエラーだったらエラー文を表示
							foreach ($_SESSION['input_error'] as $key => $value) {
								$form_tag .= "<p>".$value."</p>\n";
							}
						$form_tag .= "</div>\n";
					}
				}
				
				/** インプットフォーム **/
				// div開始
				$form_tag .= "<div class='".$name."'>\n";
				// フォームタグの作成
				if ($method != null && $action != null) {
					$form_tag .= $this->utilInstance->formStartTag($name, $method, $action);
				}
				// フォームデータの埋め込み
				$form_tag .= self::formInputTag($this->form_create_data);
				// submitボタン作成
				if ($submit_text != null) {
					$form_tag .= $this->utilInstance->formSubmitTag($submit_text);
				}
				// フォーム終了
				$form_tag .= $this->utilInstance->formEndTag();
				// div終了
				$form_tag .= "</div>\n";
				/** ここまでインプットフォーム **/
				break;
			case $this->form_mode_confirm:
				// マジッククォート判定
				$post_data = $this->utilInstance->removeMagicQuotesGpc($post_data);
				
				/** データをセッションに格納 **/
				$this->utilInstance->addSession($post_data);
				
				// 直リンクの場合はinput画面へ遷移
				self::linkCheckReferer($this->input_name);
				
				// ワンタイムチケット確認
				self::checkOneTimeTicket();
				
				// 入力チェック（満たしていなければエラー文をSessionに格納しinput画面へ戻す）
				self::inputDataCheck($post_data);
				
				/** 確認フォーム作成 **/
				// div開始
				$form_tag = "<div class='".$name."'>\n";
				// フォームタグの作成
				if ($method != null && $action != null) {
					$form_tag .= $this->utilInstance->formStartTag($name, $method, $action);
				}
				// ポストデータの埋め込み
				$form_tag .= self::formConfirmTag($post_data);
				// submitボタン作成
				if ($submit_text != null) {
					// 戻るボタン
					$form_tag .= $this->utilInstance->jsLinkButton("./".$this->input_file_name, $this->back_button_text);
					$form_tag .= $this->utilInstance->formSubmitTag($submit_text);
				}
				// フォーム終了
				$form_tag .= $this->utilInstance->formEndTag();
				// div終了
				$form_tag .= "</div>\n";
				/** ここまで確認フォーム **/
				break;
			case $this->form_mode_finish:
				// ワンタイムチケット確認
				self::checkOneTimeTicket();
				
				// 直リンクの場合はinput画面へ遷移
				self::linkCheckReferer($this->confirm_name);
				
				/** 完了画面作成 **/
				// div開始
				$form_tag = "<div class='".$name."'>\n";
				// フォームタグの作成
				$form_tag .= "<form>";
				// 完了画面テキストの埋め込み
				$form_tag .= self::formFinishTag($this->finish_text);
				$form_tag .= $this->utilInstance->jsLinkButton("./".$this->input_file_name);
				// フォーム終了
				$form_tag .= "</form>";
				// div終了
				$form_tag .= "</div>\n";
				/** ここまで完了画面 **/
				
				// メール送信
				self::sendMailExec();
				
				// セッション破棄
				$this->utilInstance->removeSession();
				break;
		}
		
		// フォームタグを返す
		return $form_tag;
	}
	
	/**
	 * input画面のタグ生成
	 * @param 	$form_create_data		// 生成するデータ
	 * @return 	$form_tag				// input用タグ
	 */
	private function formInputTag($form_create_data) {
		$form_tag = "";
		foreach ($form_create_data as $key => $value) {
			// データ存在チェック
			$data[$value["name"]] = "";
			if (isset($_SESSION['post_data']['name'])) {
				$data[$value["name"]] = $_SESSION['post_data'][$value["name"]];
			}
			// タグ生成
			switch ($value["type"]) {
				// タイプがtextの場合
				case $this->type_text:
					$form_tag .= "<p><label for='".$value["name"]."'>".$this->inputDataCheck[$value["name"]]['name'].(($this->inputDataCheck[$value["name"]]['required']) ? " *" : "")."</label><input id='".$value["name"]."' type='".$value["type"]."' name='".$value["name"]."' value='".$data[$value["name"]]."'></p>\n";
					break;
				// タイプがtextareaの場合
				case $this->type_textarea:
					$form_tag .= "<p><label for='".$value["name"]."'>".$this->inputDataCheck[$value["name"]]['name'].(($this->inputDataCheck[$value["name"]]['required']) ? " *" : "")."</label><textarea id='".$value["name"]."' name='".$value["name"]."'>".$data[$value["name"]]."</textarea><p>\n";
					break;
				default:
					if ($value["name"] == $this->link_check_referer) {
						// 不正リンク対策
						$form_tag .= "<input type='".$value["type"]."' name='".$value["name"]."' value='".$this->input_name."'>\n";
					} elseif ($value["name"] == $this->one_time_ticket_name) {
						// ワンタイムチケット
						$form_tag .= "<input type='".$value["type"]."' name='".$value["name"]."' value='".$this->one_time_ticket."'>\n";
					} else {
						$form_tag .= "<input type='".$value["type"]."' name='".$value["name"]."'>\n";
					}
					break;
			}
		}
		return $form_tag;
	}
	
	/**
	 * confirm画面のタグ生成
	 * @param 	$post_data		// 生成するデータ
	 * @return 	$form_tag		// confirm用タグ
	 */
	private function formConfirmTag($post_data) {
		// ユーティリティーインスタンス作成
		$util = new Utility();
		
		$form_tag = "";
		if ($post_data != null) {
			foreach ($post_data as $key => $value) {
				// refererならタグをつくらない
				if ($key == $this->link_check_referer) {
					$form_tag .= "<input type='".$this->type_hidden."' name='".$this->link_check_referer."' value='".$this->confirm_name."'>\n";
				} elseif ($key == $this->one_time_ticket_name) {
					$form_tag .= "<input type='".$this->type_hidden."' name='".$this->one_time_ticket_name."' value='".$this->one_time_ticket."'>\n";
				} else {
					$form_tag .= "<p><label>".$this->inputDataCheck[$key]['name']."</label><p class='form_span'>".$this->utilInstance->es($value)."</p></p>\n";
				}
			}
		}
		return $form_tag;
	}
	
	/**
	 * 完了画面のタグ生成
	 * @param 	$finish_text	// 生成するデータ
	 * @return 	$form_tag		// finish用タグ
	 */
	private function formFinishTag($finish_text) {
		$form_tag = "<p>".$finish_text."</p>\n";
		return $form_tag;
	}
	
	/**
	 * 不正リンク対策
	 * @param 	$display_name	// 画面名
	 */
	private function linkCheckReferer($display_name) {
		if ($_POST[$this->link_check_referer] == "" && $_POST[$this->link_check_referer] != $display_name) {
			header("Location:".$this->input_file_name);
			exit;
		}
	}
	
	/**
	 * ワンタイムチケット確認
	 */
	private function checkOneTimeTicket() {
		if (isset($_POST[$this->one_time_ticket_name]) && isset($_SESSION[$this->one_time_ticket_name])) {
			if (!in_array($_POST[$this->one_time_ticket_name], $_SESSION[$this->one_time_ticket_name])) {
				header("Location:".$this->input_file_name);
				exit;
			}
		}
	}
	
	/**
	 * 入力チェック
	 * @param	$post_data	// ポストデータ
	 */
	private function inputDataCheck($post_data) {
		// 必須チェック
		self::checkRequired($post_data);
		// メール形式チェック
		self::checkMailAddress($post_data);
		// 文字数チェック
		self::checkStrNum($post_data);
	}
	
	/**
	 * 必須チェック
	 * @param	$data	// 対象データ
	 */
	private function checkRequired($data) {
		foreach ($this->inputDataCheck as $key => $value) {
			if ($value['required']) {
				if ($data[$key] == "") {
					$_SESSION['input_error'][$key] = $value['name'].$this->inputErrorText['required'];
					$error[$key] = false;
				} else {
					if (isset($_SESSION['input_error'][$key])) {
						unset($_SESSION['input_error'][$key]);
					}
				}
			}
		}
		if (isset($error)) {
			$check_flg = false;
		} else {
			$check_flg = true;
		}
		
		// falseがあればinput画面に遷移しエラー表示
		if (!$check_flg) {
			header("Location:".$this->input_file_name);
			exit;
		}
	}
	
	/**
	 * メールアドレス形式チェック
	 * @param	$data	// 対象データ
	 */
	private function checkMailAddress($data) {
		foreach ($this->inputDataCheck as $key => $value) {
			if ($value['mail']) {
				if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data[$key])){
					$_SESSION['input_error'][$key] = $value['name'].$this->inputErrorText['mail'];
					$error[$key] = false;
				} else {
					if (isset($_SESSION['input_error'][$key])) {
						unset($_SESSION['input_error'][$key]);
					}
				}
			}
		}
		if (isset($error)) {
			$check_flg = false;
		} else {
			$check_flg = true;
		}
		
		// falseがあればinput画面に遷移しエラー表示
		if (!$check_flg) {
			header("Location:".$this->input_file_name);
			exit;
		}
	}
	
	/**
	 * 文字数チェック
	 * @param	$data	// 対象データ
	 */
	private function checkStrNum($data) {
		foreach ($this->inputDataCheck as $key => $value) {
			if ($value['strnum'] > 0) {
				$length = mb_strlen($data[$key], "UTF-8");
				if($length < 0 || $length > $value['strnum']){
					$_SESSION['input_error'][$key] = $value['name'].$this->inputErrorText['strnum'];
					$error[$key] = false;
				} else {
					if (isset($_SESSION['input_error'][$key])) {
						unset($_SESSION['input_error'][$key]);
					}
				}
			}
		}
		if (isset($error)) {
			$check_flg = false;
		} else {
			$check_flg = true;
		}
		
		// falseがあればinput画面に遷移しエラー表示
		if (!$check_flg) {
			header("Location:".$this->input_file_name);
			exit;
		}
	}
	
	/**
	 * メール送信実行
	 */
	private function sendMailExec() {
		
		// 返信メールの有無
		$return_mail = $this->return_mail;
		
		// 管理者メールアドレス
		$admin_mail = $this->admin_mail;
		// 管理者メール用タイトル
		$admin_subject = $this->admin_mail_subject;
		
		// ユーザーメールアドレスと名前
		$user_mail = ((isset($_SESSION["post_data"]["mail"])) ? $_SESSION["post_data"]["mail"] : $admin_mail);
		$user_name = ((isset($_SESSION["post_data"]["name"])) ? $_SESSION["post_data"]["name"] : "");
		
		// ユーザー（返信）メールのタイトル
		$user_subject = $this->user_mail_subject;
		
		// mbstringの日本語設定
		self::mbLanguage($this->mail_language, $this->mail_encode);
		
		// メールヘッダー
		$header = self::createMailHeader($user_name, $user_mail);
		
		// 管理者メールメッセージ文
		$sendmsg = self::createMailText($this->admin_mail_text_subject, $_SESSION["post_data"]);
		
		// 環境フラグ
		$env_flg = $this->utilInstance->checkEnv();
		if (!$env_flg) {
			$result = mb_send_mail($admin_mail, $admin_subject, $sendmsg, $header);
			
			if($result){
				// 返信メール用メッセージ
				if($return_mail && $user_mail){
					// メールヘッダー
					$header = self::createMailHeader($user_name, $admin_mail);
					// ユーザーメールメッセージ文
					$sendmsg = self::createMailText($this->user_mail_text_subject, $_SESSION["post_data"]);
					
					mb_send_mail($user_mail, $user_subject, $sendmsg, $header);
				}
			}else{
				echo $this->send_mail_error_text;
				exit;
			}
		}
	}
	
	/**
	 * メール本文作成
	 * @param 	$post_data	// ポストデータ
	 * @return	$msg		// メール本文
	 */
	private function createMailText($suject, $post_data) {
		$msg = $suject;
		$msg .= "\n\n";
		$msg .= "===================================\n\n";
		foreach ($_SESSION["post_data"] as $key => $value) {
			if ($key == "referer" || $key == "one_time_ticket") {
				continue;
			}
			$msg .= "【".$this->inputDataCheck[$key]['name']."】\n".$value."\n\n";
		}
		$msg .= "===================================\n\n";
		
		return $msg;
	}
	
	/**
	 * メールヘッダー作成
	 * @param $name	// 名前
	 * @param $mail	// メールアドレス
	 * return 		// メールヘッダー
	 */
	private function createMailHeader($name, $mail) {
		return 'From : '.mb_encode_mimeheader($name).' <'.$mail.'>';
	}
	
	/**
	 * mbstringの日本語設定
	 * @param $language 	// ランゲージ
	 * @param $encode 		// エンコード
	 */
	private function mbLanguage($language, $encode) {
		mb_language($language);
		mb_internal_encoding($encode);
	}
}
?>