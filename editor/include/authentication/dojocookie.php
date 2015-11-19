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
?>
	{
		xtype: "form",
		<?php if(!ext_isXHR()) { ?>renderTo: "adminForm", <?php } ?>
		title: "<?php echo ext_Lang::msg('actlogin') ?>",
		id: "simpleform",
		labelWidth: 125, // label settings here cascade unless overridden
		url: "<?php echo basename( $GLOBALS['script_name']) ?>",
		frame: true,
		keys: {
		    key: Ext.EventObject.ENTER,
		    fn  : function(){
				if (simple.getForm().isValid()) {
					Ext.get( "statusBar").update( "Please wait..." );
					Ext.getCmp("simpleform").getForm().submit({
						reset: false,
						success: function(form, action) { location.reload() },
						failure: function(form, action) {
							if( !action.result ) return;
							Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error, function() {
							this.findField( 'password').setValue('');
							this.findField( 'password').focus();
							}, form );
							Ext.get( 'statusBar').update( action.result.error );
						},
						scope: Ext.getCmp("simpleform").getForm(),
						params: {
							option: "com_extplorer", 
							action: "login",
							type : "extplorer"
						}
					});
    	        } else {
        	        return false;
            	}
            }
		},
		items: [{
            xtype:"textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscusername', true ) ?>",
			name: "username",
			width:175,
			allowBlank:false
		},{
			xtype:"textfield",
			fieldLabel: "<?php echo ext_Lang::msg( 'miscpassword', true ) ?>",
			name: "password",
			inputType: "password",
			width:175,
			allowBlank:false
		}, new Ext.form.ComboBox({
			
			fieldLabel: "<?php echo ext_Lang::msg( 'misclang', true ) ?>",
			store: new Ext.data.SimpleStore({
		fields: ['language', 'langname'],
		data :	[
		<?php 
		$langs = get_languages();
		$i = 0; $c = count( $langs );
		foreach( $langs as $language => $name ) {
			echo "['$language', '$name' ]";
		if( ++$i < $c ) echo ',';
		}
		?>
			]
	}),
			displayField:"langname",
			valueField: "language",
			value: "<?php echo ext_Lang::detect_lang() ?>",
			hiddenName: "lang",
			disableKeyFilter: true,
			editable: false,
			triggerAction: "all",
			mode: "local",
			allowBlank: false,
			selectOnFocus:true
		}),
		{
			xtype: "displayfield",
			id: "statusBar"
		}
		],
		buttons: [{
			text: "<?php echo ext_Lang::msg( 'btnlogin', true ) ?>", 
			type: "submit",
			handler: function() {
				Ext.get( "statusBar").update( "Please wait..." );
				Ext.getCmp("simpleform").getForm().submit({
					reset: false,
					success: function(form, action) { location.reload() },
					failure: function(form, action) {
						if( !action.result ) return;
						Ext.Msg.alert('<?php echo ext_Lang::err( 'error', true ) ?>', action.result.error, function() {
							this.findField( 'password').setValue('');
							this.findField( 'password').focus();
							}, form );
						Ext.get( 'statusBar').update( action.result.error );
						
					},
					scope: Ext.getCmp("simpleform").getForm(),
					params: {
						option: "com_extplorer", 
						action: "login",
						type : "extplorer"
					}
				});
			}
		},<?php if(!ext_isXHR()) { ?>
		{
			text: '<?php echo ext_Lang::msg( 'btnreset', true ) ?>', 
			handler: function() { simple.getForm().reset(); } 
		}
		<?php 
		} else {?>
		{
			text: "<?php echo ext_Lang::msg( 'btncancel', true ) ?>", 
			handler: function() { Ext.getCmp("dialog").destroy(); }
		}
		<?php 
		} ?>
		]
	}
	

<?php		
	}

	function onLogout() {
	    unset($_COOKIE[$GLOBALS['dojocookie_name']]);
	    setcookie($GLOBALS['dojocookie_name'], null, -1, '/');
		logout();
	}
} 
?>
