<?php
include_once 'config/Config.php';
include_once 'logic/Form.php';

// 設定呼出
$config = new Config();
// フォーム設定作成
$form = new Form($config);
// フォームタグ作成
$form_tag = $form->startForm($config->FORM_MODE_FINISH);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>My_System｜Form</title>
<link rel="stylesheet" type="text/css" href="css/form.css" />
</head>
<body>
<section class="contactform">
	<?php echo $form_tag; ?>
</section>
</body>
</html>