<?php
  define('DB_DSN', 'mysql:dbname=searchbook;host=localhost');
  define('DB_USER','search');
  define('DB_PASSWORD', 'MyNewPass7!');

  // 文字化け対策
  //$options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");

  $options = array(PDO::MYSQL_ATTR_INIT_COMMAND,'SET NAMES SET utf8');

  // PHPのエラーを表示するように設定
  error_reporting(E_ALL & ~E_NOTICE);

  // データベースの接続
  try {
       $db = new PDO( DB_DSN, DB_USER, DB_PASSWORD, $options);
       $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
       echo $e->getMessage();
       exit;
  }
?>
