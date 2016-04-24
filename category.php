<?php

function getCategories($service_url) {

  $categoryColor['6'] = "";
  $categoryColor['7'] = "#7f8c8d";
  $categoryColor['16'] = "";
  $categoryColor['9'] = "#3498db";
  $categoryColor['14'] = "";
  $categoryColor['19'] = "";
  $categoryColor['15'] = "#83d6dd";
  $categoryColor['10'] = "#f1c40f";
  $categoryColor['17'] = "";
  $categoryColor['18'] = "";
  $categoryColor['12'] = "#d35400";
  $categoryColor['13'] = "#2980b9";
  $categoryColor['8'] = "#c0392b";

  $categoryImageWhiteUrl['6'] = "";
  $categoryImageWhiteUrl['7'] = "http://i.imgur.com/Eeiw9rj.png";
  $categoryImageWhiteUrl['16'] = "";
  $categoryImageWhiteUrl['9'] = "http://i.imgur.com/SW3ukN4.png";
  $categoryImageWhiteUrl['14'] = "";
  $categoryImageWhiteUrl['19'] = "";
  $categoryImageWhiteUrl['15'] = "http://i.imgur.com/53OqZp0.png";
  $categoryImageWhiteUrl['10'] = "http://i.imgur.com/8F0TNmS.png";
  $categoryImageWhiteUrl['17'] = "";
  $categoryImageWhiteUrl['18'] = "";
  $categoryImageWhiteUrl['12'] = "http://i.imgur.com/PSokHYk.png";
  $categoryImageWhiteUrl['13'] = "http://i.imgur.com/f1prUtZ.png";
  $categoryImageWhiteUrl['8'] = "http://i.imgur.com/83NEeCT.png";

  $categoryImageColorUrl['6'] = "";
  $categoryImageColorUrl['7'] = "http://i.imgur.com/tQDiaI2.png";
  $categoryImageColorUrl['16'] = "";
  $categoryImageColorUrl['9'] = "http://i.imgur.com/Z8KtdVr.png";
  $categoryImageColorUrl['14'] = "";
  $categoryImageColorUrl['19'] = "";
  $categoryImageColorUrl['15'] = "http://i.imgur.com/U9LMZBF.png";
  $categoryImageColorUrl['10'] = "http://i.imgur.com/z1xEAa1.png";
  $categoryImageColorUrl['17'] = "";
  $categoryImageColorUrl['18'] = "";
  $categoryImageColorUrl['12'] = "http://i.imgur.com/AG3sLfY.png";
  $categoryImageColorUrl['13'] = "http://i.imgur.com/4Uv2m8e.png";
  $categoryImageColorUrl['8'] = "http://i.imgur.com/LuJlx8p.png";

  $curl = curl_init($service_url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $curl_response = curl_exec($curl);
  if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
  }
  curl_close($curl);

  $response = json_decode(utf8_encode($curl_response));

  $listCategories = $response->categories;
  foreach ($listCategories as $category) {
    $category->categoryImageColorUrl = $categoryImageColorUrl[$category->id];
    $category->categoryImageWhiteUrl = $categoryImageWhiteUrl[$category->id];
    $category->categoryColor = $categoryColor[$category->id];
  }

  return $listCategories;
}

$service_url = 'http://eonet.sci.gsfc.nasa.gov/api/v2.1/categories';
$categories = getCategories($service_url);
echo(json_encode($categories, JSON_UNESCAPED_SLASHES));

?>