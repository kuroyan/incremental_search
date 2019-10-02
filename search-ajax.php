<?php
ini_set('display_error',1);
//
function h($str) {
  return htmlspecialchars($str);
}

function to_highlight_keyword($arr, $str) {
  foreach ($arr as $val) {
    $val = preg_quote($val, '/');
    $str = preg_replace_callback(
        "/<.*?>|($val)/i", 
       function($matches) { return (substr($matches[0],0,1) =="<")?  $matches[0]: "<strong>$matches[1]</strong>";}, $str );

//置換前の関数（php7では非推奨）
//create_function($matches){ (substr($matches[0], 0, 1) == "<")? ' . ' $matches[0] : "<strong>$matches[1]</strong>";},$str);

  }
  return $str;
}

$q = $_GET["q"];

//全角スペースを半角空白に置き換える
$q = mb_convert_kana($q, "s");
//半角スペースを半角空白に置き換える
$keywords = preg_split("'[\\s,]+'", $q, -1, PREG_SPLIT_NO_EMPTY);

include'./DB_Connect.php';

$kcnt = count($keywords); //キーワード数

$query_w =  $kcnt > 0 ? ' where ' : '';
for ($i=0; $i<$kcnt; $i++) {
  $query_w = $query_w . "name like '%" . $keywords[$i] . "%' ";
  if ($i < $kcnt - 1) {
     $query_w .= "and ";
  }
}

$query = "select * from books" . $query_w;
$result = $db->query($query);

$set = array();    //結果格納用
$count = 0;        //全件数
if ($result != FALSE) {
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    array_push($set, $row);
    $count++;
  }
}
?>
<hr />
<h2>Search Result (<?php print $count ?>)</h2>
<ol>
<?php 

if (count($set) > 0) {
  foreach($set as $rec) { 
    $name = to_highlight_keyword($keywords, h($rec["name"]));
?>
<li>
<?php print h($rec["isbn"]); ?> : 
<a href="http://www.amazon.co.jp/dp/<?php print h($rec["isbn"]) ?>" target="_blank">
<?php print $name; ?>(<?php print h($rec["publisher"]); ?>)
</a>
</li>
<?php
  }
} else { 
?>
<li>No Data</li>
<?php 
} 
?>
</ol>
