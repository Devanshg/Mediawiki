<?php
/**
 * Config file for mediawiki
 *
 * The file is composed of XOOPS basic preferences and mediawiki LocalSettings.php
 * You are not supposed to change most of the parameters
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since		1.58
 * @version		$Id$
 * @package		module::mediawiki
 */
 
global $xoopsUser, $xoopsModule, $xoopsConfig, $xoopsOption, $xoopsLogger, $xoopsTpl;
global $wgUsePathInfo;

# If PHP's memory limit is very low, some operations may fail.
@ini_set( 'memory_limit', '20M' );

$mainfile = dirname(dirname(dirname(__FILE__)))."/mainfile.php";
include_once $mainfile;
define("MEDIAWIKI_DIRNAME", basename(dirname(__FILE__)));
require_once(XOOPS_ROOT_PATH."/modules/".MEDIAWIKI_DIRNAME."/include/functions.php");

if(!defined("NS_MAIN")) {
	require_once(XOOPS_ROOT_PATH."/modules/".MEDIAWIKI_DIRNAME."/includes/Defines.php");
}

// define user name prefix for the module, must be capitalized!
define("MEDIAWIKI_USERPREFIX", "");

# This file was automatically generated by the MediaWiki installer.
# If you make manual changes, please keep track in case you need to
# recreate them later.

# If you customize your file layout, set $IP to the directory that contains
# the other MediaWiki files. It will be used as a base to locate files.
if( defined( 'MW_INSTALL_PATH' ) ) {
	$IP = MW_INSTALL_PATH;
} else {
	$IP = dirname( __FILE__ );
}

$path = array( $IP, "$IP/includes", "$IP/languages" );
set_include_path( implode( PATH_SEPARATOR, $path ) . PATH_SEPARATOR . get_include_path() );

require_once( "includes/DefaultSettings.php" );

/** We speak UTF-8 all the time now, unless some oddities happen */
//$wgInputEncoding	= 'UTF-8';
$wgOutputEncoding	= empty($GLOBALS["xlanguage"]['charset_base'])?_CHARSET:$GLOBALS["xlanguage"]['charset_base'];
//$wgEditEncoding		= '';

if ( $wgCommandLineMode ) {
	if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
		//die( "This script must be run from the command line\n" );
	}
} elseif ( empty( $wgNoOutputBuffer ) ) {
	## Compress output if the browser supports it
	//if( !ini_get( 'zlib.output_compression' ) ) @ob_start( 'ob_gzhandler' );
}

$wgSitename         = mediawiki_encoding_xoops2mediawiki($xoopsConfig["sitename"]);

$wgServer = preg_replace( '{^(http[s]?://[^/]*).*$}', '$1', XOOPS_URL );
$wgInternalServer = $wgServer;

$ScriptPath = str_replace( $wgServer, '', XOOPS_URL."/modules/".MEDIAWIKI_DIRNAME ); # was SCRIPT_NAME
$wgScriptPath	    = $ScriptPath;
$wgScript           = "$wgScriptPath/index.php";
$wgRedirectScript   = "$wgScriptPath/redirect.php";

## For more information on customizing the URLs please see:
## http://meta.wikimedia.org/wiki/Eliminating_index.php_from_the_url
## If using PHP as a CGI module, the ?title= style usually must be used.
if($wgUsePathInfo){
	$wgArticlePath      = "$wgScript/$1";
	
	// $_SERVER['PATH_INFO'] used in WebRequest::WebRequest() would escape trailing "delimitors"
	if(!empty($_SERVER['PATH_INFO'])){
		if(preg_match("/([\.\?]+)$/", $_SERVER['REQUEST_URI'], $matches)){
			$_SERVER['PATH_INFO'] .= $matches[1];
		}
	}
}else{
	$wgArticlePath      = "$wgScript?title=$1";
}
$wgStylePath        = "$wgScriptPath/skins";
$wgStyleDirectory   = "$IP/skins";
$wgLogo             = "$wgScriptPath/images/mediawiki.png";

$wgUploadPath       = str_replace( $wgServer, '', XOOPS_UPLOAD_URL )."/".MEDIAWIKI_DIRNAME;
$wgUploadDirectory  = XOOPS_UPLOAD_PATH."/".MEDIAWIKI_DIRNAME;

/**
 * This will cache static pages for non-logged-in users to reduce
 * database traffic on public sites.
 * Must set $wgShowIPinHeader = false
 */
$wgUseFileCache = @(mediawiki_getStyle())?false: ( is_object($xoopsModule) && $xoopsModule->getVar('dirname') == MEDIAWIKI_DIRNAME && @$xoopsConfig['module_cache'][$xoopsModule->getVar('mid')] > 0 );
/** Directory where the cached page will be saved */
$wgFileCacheDirectory = XOOPS_CACHE_PATH."/".MEDIAWIKI_DIRNAME;
if($wgUseFileCache && !$xoopsUser && !file_exists($wgFileCacheDirectory)) { mkdir($wgFileCacheDirectory,0775); } # create if necessary

$wgShowIPinHeader	= false; # For non-logged in users

$wgEnableEmail = true;
$wgEnableUserEmail = true;

$wgEmergencyContact = $xoopsConfig["adminmail"];
$wgPasswordSender	= $xoopsConfig["adminmail"];

## For a detailed description of the following switches see
## http://meta.wikimedia.org/Enotif and http://meta.wikimedia.org/Eauthent
## There are many more options for fine tuning available see
## /includes/DefaultSettings.php
## UPO means: this is also a user preference option
$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;

$wgDBserver         = XOOPS_DB_HOST;
$wgDBname           = XOOPS_DB_NAME;
$wgDBuser           = XOOPS_DB_USER;
$wgDBpassword       = XOOPS_DB_PASS;
$wgDBprefix         = $GLOBALS["xoopsDB"]->prefix("mediawiki")."_";
$wgDBtype           = "mysql";
$wgDBport           = "5432";

# Experimental charset support for MySQL 4.1/5.0.
$wgDBmysql5 = false;

## Shared memory settings
$wgMainCacheType = CACHE_NONE;
$wgMemCachedServers = array();

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads		= true;
$wgFileExtensions = array_merge( $wgFileExtensions, array( 'mp4', 'flv' ) );
$wgUseImageResize		= true;
# $wgUseImageMagick = true;
# $wgImageMagickConvertCommand = "/usr/bin/convert";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
# $wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
$wgUseTeX	         = false;
$wgMathPath         = "{$wgUploadPath}/math";
$wgMathDirectory    = "{$wgUploadDirectory}/math";
$wgTmpDirectory     = "{$wgUploadDirectory}/tmp";

$wgLocalInterwiki   = $wgSitename;

$wgLanguageCode = strtolower(_LANGCODE);

//$wgProxyKey = "72eb0f8e1449e71e20d38d9a79d596a27d96c33f747de07872ce348049dae627";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook':
$wgDefaultSkin = 'monobook';

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgEnableCreativeCommonsRdf = true;
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = XOOPS_URL;
$wgRightsText = $wgSitename;
$wgRightsIcon = XOOPS_URL."/images/s_poweredby.gif";
# $wgRightsCode = ""; # Not yet used

$wgDiff3 = "";

# When you make changes to this configuration file, this will make
# sure that cached pages are cleared.
$configdate = gmdate( 'YmdHis', @filemtime( __FILE__ ) );
$wgCacheEpoch = max( $wgCacheEpoch, $configdate );

// TODO: convertor for Xoops group -> mediawiki group
// Implicit group for all visitors
$wgGroupPermissions['*'    ]['createaccount']   = true;
$wgGroupPermissions['*'    ]['read']            = true;
$wgGroupPermissions['*'    ]['edit']            = false;
$wgGroupPermissions['*'    ]['createpage']      = false;
$wgGroupPermissions['*'    ]['createtalk']      = false;

// Implicit group for all logged-in accounts
$wgGroupPermissions['user' ]['move']            = true;
$wgGroupPermissions['user' ]['read']            = true;
$wgGroupPermissions['user' ]['edit']            = true;
$wgGroupPermissions['user' ]['createpage']      = true;
$wgGroupPermissions['user' ]['createtalk']      = true;
$wgGroupPermissions['user' ]['upload']          = true;
$wgGroupPermissions['user' ]['reupload']        = true;
$wgGroupPermissions['user' ]['reupload-shared'] = true;
$wgGroupPermissions['user' ]['minoredit']       = true;

// Implicit group for accounts that pass $wgAutoConfirmAge
$wgGroupPermissions['autoconfirmed']['autoconfirmed'] = true;

// Users with bot privilege can have their edits hidden
// from various log pages by default
$wgGroupPermissions['bot'  ]['bot']             = true;
$wgGroupPermissions['bot'  ]['autoconfirmed']   = true;

// Most extra permission abilities go to this group
$wgGroupPermissions['sysop']['block']           = true;
$wgGroupPermissions['sysop']['createaccount']   = true;
$wgGroupPermissions['sysop']['delete']          = true;
$wgGroupPermissions['sysop']['deletedhistory']  = true; // can view deleted history entries, but not see or restore the text
$wgGroupPermissions['sysop']['editinterface']   = true;
$wgGroupPermissions['sysop']['import']          = true;
$wgGroupPermissions['sysop']['importupload']    = true;
$wgGroupPermissions['sysop']['move']            = true;
$wgGroupPermissions['sysop']['patrol']          = true;
$wgGroupPermissions['sysop']['protect']         = true;
$wgGroupPermissions['sysop']['rollback']        = true;
$wgGroupPermissions['sysop']['upload']          = true;
$wgGroupPermissions['sysop']['reupload']        = true;
$wgGroupPermissions['sysop']['reupload-shared'] = true;
$wgGroupPermissions['sysop']['unwatchedpages']	= true;
$wgGroupPermissions['sysop']['autoconfirmed']   = true;

// Permission to change users' group assignments
$wgGroupPermissions['bureaucrat']['userrights'] = true;

// Experimental permissions, not ready for production use
//$wgGroupPermissions['sysop']['deleterevision'] = true;
//$wgGroupPermissions['bureaucrat']['hiderevision'] = true;

/**
 * Set an offset from UTC in hours to use for the default timezone setting
 * for anonymous users and new user accounts.
 *
 */
$timeoffset_xoops = empty($xoopsUser)?$xoopsConfig['default_TZ']:$xoopsUser->getVar("timezone_offset");
$wgLocalTZoffset = @(floatval($timeoffset_xoops) + floatval($xoopsConfig['server_TZ']));

/* Installing this extension may lead to security and technical problems 
 * as well as data corruption.
 */
require_once("extensions/FCKeditor.php");

$wgFCKUseEditor          = false;      // When set to 'true' the FCKeditor is the default editor.
$wgFCKEditorDir          = XOOPS_URL."/class/xoopseditor/FCKeditor";
$wgFCKEditorToken        = "__USE_EDITOR__";  
$wgFCKEditorToolbarSet   = "Wiki";
$wgFCKEditorHeight       = "600";
$wgFCKEditorAllow_a_tags      = true; // <a> </a>   : Set this to true if you want to use the **external** link 
                                       // generator of the FCKeditor.
$wgFCKEditorAllow_img_tags    = true; // <img />    : Set this to true if you want to use the 
                                       // file browser and/or the smilies of the FCKeditor.
$wgFCKexcludedNamespaces = array(8,1,-1);    // eg. "8" for disabling the editor within the MediaWiki namespace.

/**
 * Enable use of AJAX features.
 */
$wgUseAjax = true;
$wgAjaxExportList[] = 'wfSajaxSearchImageFCKeditor';

require_once("extensions/videoflash.php");

require_once("extensions/SimpleTable.php");

require_once("$IP/extensions/HarvardReferences.php");
$wgHarvardReferencesOn = true;

require_once( $IP.'/extensions/TagParser/TagParser.php' );

require_once("extensions/ParserFunctions/ParserFunctions.php");
$wgPFEnableStringFunctions = true;
?>