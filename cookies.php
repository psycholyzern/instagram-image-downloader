<?php

$username = "YOUR_USERNAME";
$password = "YOUR_PASSWORD";

$cookie_jar = "cookies.txt";

$f = @fopen($cookie_jar, "r+");
if ($f !== false) {
    ftruncate($f, 0);
    fclose($f);
}

$url = 'https://www.instagram.com';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Connection: keep-alive',
'Upgrade-Insecure-Requests: 1',
'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
'Accept-Encoding: gzip, deflate, sdch, br',
'Accept-Language: en-US,en;q=0.8',
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_USERAGENT,'User-Agent: Mozilla/5.0 (Linux; Android 6.0.1; A0001 Build/MOB31E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36' );
$r = curl_exec($ch);
curl_close($ch);

preg_match("/csrftoken\=(.*?)\;/", $r, $m);

$url = 'https://www.instagram.com/accounts/login/ajax/';
$fields = array(
'username' => $username,
'password' => $password
);
$fields_string = http_build_query($fields);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Host: www.instagram.com',
'Connection: keep-alive',
'Origin: https://www.instagram.com',
'X-Instagram-AJAX: 1',
'User-Agent: Mozilla/5.0 (Linux; Android 6.0.1; A0001 Build/MOB31E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
'Content-Type: application/x-www-form-urlencoded',
'Accept: */*',
'X-Requested-With: XMLHttpRequest',
'X-CSRFToken: '.$m[1],
'Referer: https://www.instagram.com/',
'Accept-Encoding: gzip, deflate, br',
'Accept-Language: en-US,en;q=0.8'
));
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,true);
$r= curl_exec($ch);
$i = curl_getinfo($ch);
curl_close($ch);

//print_r($r);
echo(file_get_contents($cookie_jar));

