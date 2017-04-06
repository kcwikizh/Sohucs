<?php

/**
 * Sohucs
 * @version 0.1
 * @license Apache License 2.0
 * @see http://changyan.kuaizhan.com
 */


// 安装方法
// 请将下面三行代码按照相应的要求填入并复制到LocalSettings.php尾部
// require_once("$IP/extensions/Sohucs/Sohucs.php");
// $wgSohucsAppid = 搜狐畅言生成的appid
// $wgSohucsConf = 搜狐畅言生成的conf

if( !defined( 'MEDIAWIKI' ) ) die( -1 );

// Credits
$wgExtensionCredits['specialpage'][] = array(
		'path'              => __FILE__,
		'name'              => 'Sohucs',
		'version'           => '0.1',
		'author'            => 'Adpex',
		'description'       => 'Sohucs Extension',
		'url'               => 'http://changyan.kuaizhan.com'
);

// Register Sohucs tag.
$wgExtensionFunctions[] = "SohucsExtension";
//$wgExtensionMessagesFiles['Sohucs'] = dirname( __FILE__ ) . '/Sohucs.i18n.php';

// Add hooks
$wgHooks['SkinAfterContent'][] = 'Sohucs::onSkinAfterContent';
$wgHooks['SkinAfterBottomScripts'][] = 'Sohucs::onSkinAfterBottomScripts';

if (function_exists('hash_hmac')){
	$wgHooks['UserLoginComplete'][] = 'Sohucs::onUserLoginComplete';
	$wgHooks['UserLogoutComplete'][] = 'Sohucs::onUserLogoutComplete';
}

// Sohucs tag
function SohucsExtension() {
	global $wgParser;
	# register the extension with the WikiText parser
	# the first parameter is the name of the new tag.
	# In this case it defines the tag <example> ... </example>
	# the second parameter is the callback function for
	# processing the text between the tags
	$wgParser->setHook( "Sohucs", "render_Sohucs" );
}

// Renders Sohucs embed code
function render_Sohucs($input, $argv, $parser = null) {
	global $wgSohucsAppid, $wgTitle, $wgRequest, $wgSohucsConf, $wgSid;
	if ($wgSohucsShortName == "") {
		echo('Please, set $wgSohucsAppid in LocalSettings.php');
		die(1);
	}
	
	if ($wgSohucsShortName == "") {
		echo('Please, set $wgSohucsConf in LocalSettings.php');
		die(1);
	}
	session_start();
	$wgSid = md5($_SERVER['PHP_SELF']);
	
	if (!$parser) $parser =& $GLOBALS['wgParser'];
	$output = <<<eot
		<div id="SOHUCS" sid="{$wgTitle->getArticleID()}"></div>
eot;
	return $output;
}

class Sohucs{
	// Event 'SkinAfterContent': Allows extensions to add text after the page content and article metadata.
	// &$data: (string) Text to be printed out directly (without parsing)
	// This hook should work in all skins. Just set the &$data variable to the text you're going to add.
	// Documentation: \mediawiki-1.16.0\docs\hooks.txt
	public static function onSkinAfterContent(&$data, $skin = null)
	{
		global $wgSohucsAppid, $wgTitle, $wgRequest, $wgOut, $wgSohucsConf, $wgSid;
	
		if($wgTitle->isSpecialPage()
			|| $wgTitle->getArticleID() == 0
			|| !$wgTitle->canTalk()
			|| $wgTitle->isTalkPage()
			|| method_exists($wgTitle, 'isMainPage') && $wgTitle->isMainPage()
			|| in_array($wgTitle->getNamespace(), array(NS_MEDIAWIKI, NS_TEMPLATE, NS_CATEGORY))
			|| $wgOut->isPrintable()
			|| $wgRequest->getVal('action', 'view') != "view")
			return true;
		
		if(empty($wgSohucsAppid))
		{
			echo('Please, set $wgSohucsAppid in LocalSettings.php');
			die(1);
		}
		
		if(empty($wgSohucsConf))
		{
			echo('Please, set $wgSohucsConf in LocalSettings.php');
			die(1);
		}
		//session_start();
		$wgSid = md5($_SERVER['PHP_SELF']);
		$data = <<<eot
		<div id="SOHUCS" sid="{$wgTitle->getArticleID()}"></div>
eot;
		
			
		return true;
	}
	
	// --------------------- Sohucs bottom script -------------------------
	
	// Event 'SkinAfterBottomScripts': At the end of Skin::bottomScripts()
	// $skin: Skin object
	// &$text: bottomScripts Text
	// Append to $text to add additional text/scripts after the stock bottom scripts.
	// Documentation: \mediawiki-1.13.0\docs\hooks.txt
	public static function onSkinAfterBottomScripts($skin, &$text)
	{
		global $wgSohucsAppid,$wgSohucsConf, $wgSid;
		if(empty($wgSohucsAppid))
		{
			echo('Please, set $wgSohucsAppid in LocalSettings.php');
			die(1);
		}
		
		if(empty($wgSohucsConf))
		{
			echo('Please, set $wgSohucsConf in LocalSettings.php');
			die(1);
		}
		
		$text .= <<<eot
<!-- Sohucs bottom script -->
<script charset="utf-8" type="text/javascript" src="https://changyan.sohu.com/upload/changyan.js" ></script>
<script type="text/javascript">
window.changyan.api.config({
appid: '{$wgSohucsAppid}',
conf: '{$wgSohucsConf}',
});
</script>

eot;
		return true;
	}
}
