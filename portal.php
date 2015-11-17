<?php
$member_cookie_name = "coderdojomember";
if(!isset($_COOKIE[$member_cookie_name])) {
	header("Location: http://member.coderdojoennis.com/?returnurl=http" . (isset($_SERVER['HTTPS']) ? 's' : '') . "://" . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
	die();
}

$ninjaBaseDir = "ninja";

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php

if(isset($_COOKIE[$member_cookie_name])) {
    parse_str($_COOKIE[$member_cookie_name], $memberCookieArray);
    $username = $memberCookieArray['username'];

    $ninjaDir = $ninjaBaseDir.'/'.$username;

    // 
	if (!file_exists($ninjaDir)) {
	    mkdir($ninjaDir, 0755, true);
	}

	if (!file_exists($ninjaDir.'/index.html')) {
	    exec('cp index.html.template '.$ninjaDir.'/index.html');
	}

}
?>

Webhub Portal

</body>
</html>

