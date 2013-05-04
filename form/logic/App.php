<?php
/**
 * Appクラス
 */
class App {
	// 設定オブジェクト格納用
	protected $configure;
	
	// ユーティリティーインスタンス格納用
	protected $utilInstance;
	
	/* ワンタイムチケット */
	protected $one_time_ticket;
	
	/**
	 * コンストラクタ
	 * @param 	$config		// configオブジェクト
	 */
	function __construct($config) {
		// セッションスタート
		session_start();
		
		// 設定オブジェクト格納用
		$this->configure = $config;
		
		// ユーティリティーインスタンス生成
		$this->utilInstance = new Utility();
		
		// ワンタイムチケットの生成
		$this->one_time_ticket = md5(uniqid(mt_rand(), TRUE));
		$_SESSION['one_time_ticket'][] = $this->one_time_ticket;
	}
}