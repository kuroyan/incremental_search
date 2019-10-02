<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<title>Incremental Search</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link type="text/css" rel="stylesheet" href="search.css" />
<script type="text/javascript">
// <![CDATA[
function createXmlHttpRequest() {
  if (window.XMLHttpRequest) {                       // Firefox,Opera,Safari,IE7
    return new XMLHttpRequest();
  } else if (window.ActiveXObject) {                 // IE5,IE6
    try {
      return new ActiveXObject("Msxml2.XMLHTTP");    // MSXML3
    } catch(e) {
      return new ActiveXObject("Microsoft.XMLHTTP"); // MSXML2まで
    }
  } else {
    return null;
  }
}

var keyword_save = "~^|"; //dummy
var xmlhttp = null;
var baseTime = new Date();
var c = 0;
function query(flg) {
  var q = document.getElementById('q').value; //キーワード欄への入力内容
  var keyword = encodeURI(q);                 //URIエンコード
  console.log(q);

  var elapsed = parseInt((new Date()).getTime() - baseTime.getTime()); //前回入力時刻との差（ミリ秒）
  baseTime = new Date(); //基準時間の更新
  //600ミリ秒内に次の文字が入力された場合はキーワード入力中とみなして中断する
  if (elapsed < 600) { 
    //keyword_save = keyword; //キーワード保存
    return; 
  } 
  if (!xmlhttp) xmlhttp = createXmlHttpRequest(); //XMLHttpRequestを生成する
  if (!xmlhttp || xmlhttp.readyState == 1 || xmlhttp.readyState == 2 || xmlhttp.readyState == 3) {
    //keyword_save = keyword; //キーワード保存
    return; 
  }

  if (keyword_save != keyword) {
    console.log( keyword);

    xmlhttp.open("GET", "search-ajax.php?q=" + keyword, true);
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("result").innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.send(null)
    keyword_save = keyword; //キーワード保存
  }
}
window.onload = function() {
  var q = document.getElementById("q");
  if (q.addEventListener) {
    q.addEventListener("keyup", query, false) //Firefox, Opera, Safari
  } else {
    q.attachEvent("onkeyup", query); //IE
  }
  setInterval("query('1')", 1000); 
}
// ]]>
</script>
</head>
<?php
if (!isset($q)) { $q = ''; }
?>
<body>
<a href ="https://codezine.jp/article/detail/2305" target="blunk"><h1>Incremental Search</h1></a>
<div class="container">
keyword: <input id="q" name="q" type="text" autocomplete="off" value="<?php print $q ?>" />
<input type="button" value="clear" onclick="javascript:document.getElementById('q').value = '';" />
<div id="result"></div>
</div>
<div id="footer"><address>&copy; makejapan.jp KUROYAN, all rights reserved.</address></div>
</body>
</html>
