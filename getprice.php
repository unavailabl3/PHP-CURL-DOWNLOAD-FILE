<?php

$loginUrl = "http://test.com/api/login";
$pricelistsUrl = "https://test.com/api/v1/pricelists";
$clientNo = "<data>";
$clientLogin = "<data>";
$password = "<data>";
$postbody = json_encode(array("clientNo"=>$clientNo, "clientLogin"=>$clientLogin, "password"=>$password));
//Login
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postbody);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
curl_setopt($ch, CURLOPT_POSTREDIR, 3);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$rez = curl_exec($ch);
$log = json_decode($rez);
$token = $log->csrf_token;
$userId = json_decode($log->user)->id;
//Getlol
curl_setopt($ch, CURLOPT_URL, $pricelistsUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("authorization: Bearer $token"));
$content = curl_exec($ch);
$lol = json_decode($content)->data->data[0]->xml->lol;
$pricelistUrl = "https://test.com/api/v1/pricelists/get?lol=$lol&type=xml&userId=$userId";
//Download
curl_setopt($ch, CURLOPT_URL, $pricelistUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$content = curl_exec($ch);
file_put_contents('pricelist.zip', $content);
curl_close($ch);
unlink('cookie.txt');

?>