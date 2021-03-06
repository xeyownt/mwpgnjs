<?php

/**
 * Mediawiki PHP extension for displaying chess game in PGN format
 *
 * Copyright (C) 2007-2017  Michael Peeters <https://github.com/xeyownt>
 *
 * This file is part of the PgnJS MediaWiki extension
 * <http://www.mediawiki.org/wiki/Extension:PgnJS>.
 *
 * @file
 * @ingroup Extensions
 */

// namespace MediaWiki\Extension\PgnJS;

if ( function_exists( 'wfLoadExtension' ) ) {
    wfLoadExtension( 'PgnJS' );
    // Keep i18n globals so mergeMessageFileList.php doesn't break
    $wgMessagesDirs['PgnJS'] = __DIR__ . '/i18n';
    // $wgExtensionMessagesFiles['PgnJS'] = __DIR__ . '/i18n/PgnJS.i18n.php';
    wfWarn(
           'Deprecated PHP entry point used for PgnJS extension. Please use wfLoadExtension ' .
           'instead, see https://www.mediawiki.org/wiki/Extension_registration for more details.'
    );
    return true;
} else {
    $wgHooks['ParserFirstCallInit'][] = 'PgnJSHooks::onParserFirstCallInit';
    $wgHooks['GetPreferences'][] = 'PgnJSHooks::onGetPreferences';
    $wgHooks['UserSaveOptions'][] = 'PgnJSHooks::onUserSaveOptions';

    // $wgMessagesDirs['PgnJS'] = __DIR__ . '/i18n';
    $wgExtensionMessagesFiles['PgnJS'] = __DIR__ . '/i18n/PgnJS.i18n.php';

    $wgResourceModules['ext.PgnJS'] = array(
        'localBasePath' => __DIR__,
        'remoteExtPath' => 'PgnJS',
        'styles'        => array(
            'PgnViewerJS/dist/css/pgnvjs.css',
            'modules/ext.PgnJS.css'
        ),
        'scripts'       => array(
            'PgnViewerJS/js/jquery-ui.js',
            'PgnViewerJS/chess.js/chess.js',
            'PgnViewerJS/chessboardjs/js/chessboard.js',
            'PgnViewerJS/chessboardjs/js/json3.min.js',
            'PgnViewerJS/js/mousetrap.js',
            'PgnViewerJS/js/jquery.multiselect.js',
            'PgnViewerJS/js/jquery.timer.js',
            'PgnViewerJS/js/pgn.js',
            'PgnViewerJS/js/pgn-parser.js',
            'PgnViewerJS/js/pgnvjs.js',
            'PgnViewerJS/js/i18next-1.11.2.js',
            'modules/ext.PgnJS.js'
        ),
        'position'      => 'top',
        'dependencies'  => array( 'jquery.ui.core', 'jquery.ui.widget' ),
    );

    $wgExtensionCredits['parserhook'][] = array(
        'name'        => 'PgnJS',
        'version'     => '0.5.1',
        'license-name'=> 'Apache-2.0',
        'author'      => 'Michaël Peeters',
        'url'         => 'http://www.mediawiki.org/wiki/Extension:PgnJS',
        'description' => 'Display chess games (boards and moves) in PGN format interactively'
    );

    $wgAutoloadClasses['PgnJSHooks'] = __DIR__ . '/PgnJS.hooks.php';
}

?>
