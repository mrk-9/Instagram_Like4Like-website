<?php

$username = "decmondz1129@yahoo.com";
$password = "1129-dev";

$useragent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/50.0.2661.102 Chrome/50.0.2661.102 Safari/537.36";
$cookie=$username.".txt";

@unlink(dirname(__FILE__)."/".$cookie);

$url="https://www.instagram.com/accounts/login/?force_classic_login";

$ch  = curl_init();        

$arrSetHeaders = array(
    "User-Agent: $useragent",
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.5',
    'Accept-Encoding: deflate, br',
    'Connection: keep-alive',
    'cache-control: max-age=0',
);

curl_setopt($ch, CURLOPT_HTTPHEADER, $arrSetHeaders);         
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/".$cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/".$cookie);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$page = curl_exec($ch);

curl_close($ch);          

// try to find the actual login form
if (!preg_match('/<form method="POST" id="login-form" class="adjacent".*?<\/form>/is', $page, $form)) {
    die('Failed to find log in form!');
}

$form = $form[0];

// find the action of the login form
if (!preg_match('/action="([^"]+)"/i', $form, $action)) {
    die('Failed to find login form url');
}

$url2 = $action[1]; // this is our new post url
// find all hidden fields which we need to send with our login, this includes security tokens
$count = preg_match_all('/<input type="hidden"\s*name="([^"]*)"\s*value="([^"]*)"/i', $form, $hiddenFields);

$postFields = array();

// turn the hidden fields into an array
for ($i = 0; $i < $count; ++$i) {
    $postFields[$hiddenFields[1][$i]] = $hiddenFields[2][$i];
}

// add our login values
$postFields['username'] = $username;
$postFields['password'] = $password;   

$post = '';

// convert to string, this won't work as an array, form will not accept multipart/form-data, only application/x-www-form-urlencoded
foreach($postFields as $key => $value) {
    $post .= $key . '=' . urlencode($value) . '&';
}

$post = substr($post, 0, -1);   

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $page, $matches);

$cookieFileContent = '';

foreach($matches[1] as $item) 
{
    $cookieFileContent .= "$item; ";
}

$cookieFileContent = rtrim($cookieFileContent, '; ');
$cookieFileContent = str_replace('sessionid=; ', '', $cookieFileContent);

$oldContent = file_get_contents(dirname(__FILE__)."/".$cookie);
$oldContArr = explode("\n", $oldContent);

if(count($oldContArr))
{
    foreach($oldContArr as $k => $line)
    {
        if(strstr($line, '# '))
        {
            unset($oldContArr[$k]);
        }
    }

    $newContent = implode("\n", $oldContArr);
    $newContent = trim($newContent, "\n");

    file_put_contents(
        dirname(__FILE__)."/".$cookie,
        $newContent
    );    
}

$arrSetHeaders = array(
    'origin: https://www.instagram.com',
    'authority: www.instagram.com',
    'upgrade-insecure-requests: 1',
    'Host: www.instagram.com',
    "User-Agent: $useragent",
    'content-type: application/x-www-form-urlencoded',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.5',
    'Accept-Encoding: deflate, br',
    "Referer: $url",
    "Cookie: $cookieFileContent",
    'Connection: keep-alive',
    'cache-control: max-age=0',
);

$ch  = curl_init();
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/".$cookie);
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/".$cookie);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $arrSetHeaders);     
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_REFERER, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);        
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
sleep(5);
$page = curl_exec($ch);


preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $page, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie1);
    $cookies = array_merge($cookies, $cookie1);
}
var_dump($page);      

curl_close($ch);
?>