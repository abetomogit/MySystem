<?php
/**
 * Utilityクラス
 */
class Utility {
	/**
	 * フォーム開始タグ作成
	 * @param 	$name		// フォーム名
	 * @param 	$method		// フォームのメソッド（post/get）
	 * @param 	$action		// フォームのアクション
	 * @return 				// フォーム開始タグ
	 */
	public function formStartTag($name, $method, $action) {
		return "<form name='".$name."' action='".$action."' method='".$method."'>\n";
	}
	
	/**
	 * フォーム終了タグ作成
	 * @return	// フォーム終了タグ
	 */
	public function formEndTag() {
		return "</form>\n";
	}
	
	/**
	 * フォームsubmitタグ作成
	 * @param 	$submit_button_text		//　submitボタンのテキスト
	 * @return 							// フォームタグ
	 */
	public function formSubmitTag($submit_text) {
		return "<input type='submit' value='".$submit_text."' class='submit'>\n";
	}
	
	/**
	 * データをセッションに格納
	 * @param	$post_data	// ポストデータ
	 */
	public function addSession($post_data) {
		unset($_SESSION["post_data"]);
		foreach ($post_data as $key => $value) {
			$_SESSION["post_data"][$key] = self::es($value);
		}
	}
	
	/**
	 * セッションを破棄
	 * @param	$post_data	// ポストデータ
	 */
	public function removeSession() {
		session_destroy();
	}
	
	/**
	 * javascriptボタン
	 * @param	url		// リンク先
	 * 					// ボタンタグ
	 */
	public function jsLinkButton($url, $text = null) {
		if ($text == null) { $text = "戻る"; }
		return "<input class='submit' type='button' value='".$text."' onClick='location.href=\"./".$url."\"'>";
	}
	
	/**
	 * 文字エスケープ
	 * @param	$str	// ポストデータ
	 * @return			// エスケープ文字
	 */
	public function es($str) {
		return htmlspecialchars($str, ENT_QUOTES);
	}
	
	/**
	 * マジッククォートの設定がONならエスケープ文字を削除
	 * @param	$str	// ポストデータ
	 * @return			// エスケープ文字
	 */
	public function removeMagicQuotesGpc($data) {
		$resArray = $data;
		foreach ($resArray as $key => $value) {
			if (get_magic_quotes_gpc()) {
				$resArray[$key] = stripslashes($value);
			}
		}
		return $resArray;
	}
	
	/**
	 * 環境チェック
	 * @param	$str	// ポストデータ
	 * @return	bool	// true:ローカル以外/false:ローカル
	 */
	public function checkEnv() {
		// ローカル環境ならfalseを返す
		if ($_SERVER["SERVER_NAME"] != "localhost") {
			return false;
		} else {
			return true;
		}
	}
}
?>