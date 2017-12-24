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

class PgnJSHooks {
    public static function onParserFirstCallInit( Parser &$parser ) {
        // Register parser handler for tag <pgn>
        $parser->setHook( 'pgn', 'PgnJSHooks::parserHook' );
    }

    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        global $wgExtensionAssetsPath;

        $script = "<script type=\"text/javascript\" src=\"$wgExtensionAssetsPath/PgnJS/PgnViewerJS/dist/js/pgnvjs.js\"></script>";
         $out->addHeadItem("itemName", $script);
        return true;
    }

    public static function parserHook( $input, array $args, Parser $parser, PPFrame $frame ) {
        // Tell ResourceLoader that we need our css module
        $parser->getOutput()->addModuleStyles( 'ext.PgnJS.styles' );

        return PgnJS::renderPgnjs($parser, $input, $args);
    }
}

class PgnJS {
    const STYLE  = "style";
    private static $board_id = 0;

    // Render <pgn>
    static public function renderPgnjs( $parser, $input, array $args ) {
        $style = isset($args[self::STYLE]) ? $args[self::STYLE] : "width: 240px";
        $id    = ++self::$board_id;
        return "<div id=\"b$id\" style=\"$style\"></div><script>var board = pgnView('b$id', {pgn: \"$input\"});</script>";
    }
}

?>
