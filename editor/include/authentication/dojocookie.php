<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * 
 */
 
class ext_dojocookie_authentication {
	function onAuthenticate($credentials, $options=null ) {

		if(isset($_COOKIE['coderdojomember'])) {
			parse_str($_COOKIE['coderdojomember'], $memberCookieArray);
			// these two set in conf.php
			// $_SESSION['credentials_dojocookie']['username'] = $memberCookieArray['username'];
			// $_SESSION['credentials_dojocookie']['password'] = NULL;
			$_SESSION['file_mode'] = 'dojocookie';
			$GLOBALS["home_dir"]	= $GLOBALS["user_root_dir"] . "/" . $_SESSION['credentials_dojocookie']['username'];
			$GLOBALS["home_url"]	= "http://localhost";
			$GLOBALS["show_hidden"]	= 1;
			$GLOBALS["no_access"]	= NULL;
			$GLOBALS["permissions"]	= 1;
		} else {
			return false;
		}
		
		return true;		
	}
	
	function onShowLoginForm() {
	}

	function onLogout() {
	    unset($_COOKIE[$GLOBALS['dojocookie_name']]);
	    setcookie($GLOBALS['dojocookie_name'], null, -1, '/');
		logout();
	}
} 
?>
<body>
</body>