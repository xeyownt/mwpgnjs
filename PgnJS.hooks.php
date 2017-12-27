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
    private static $boards = array();

    public static function onBeforePageDisplay( OutputPage &$out, Skin &$skin ) {
        $out->addJsConfigVars( 'pgnJSBoards', self::$boards );
        return true;
    }

    public static function onParserFirstCallInit( Parser &$parser ) {
        // Register parser handler for tag <pgn>
        $parser->setHook( 'pgn', 'PgnJSHooks::parserHook' );
    }

    public static function parserHook( $input, array $args, Parser $parser, PPFrame $frame ) {
        // Tell ResourceLoader that we need our css module
        $parser->getOutput()->addModules( 'ext.PgnJS' );

        return PgnJS::renderPgnjs(self::$boards, $parser, $input, $args);
    }
}

class PgnJS {
    const STYLE      = "style";
    const MODE       = "mode";
    const POSITION   = "position";
    private static $board_id = 0;

    // Render <pgn>
    static public function renderPgnjs( &$boards, $parser, $input, array $args ) {
        $style    = isset($args[self::STYLE]) ? $args[self::STYLE] : "width: 240px";
        $mode     = isset($args[self::MODE]) ? $args[self::MODE] : "view";
        $position = isset($args[self::POSITION]) ? $args[self::POSITION] : "start";
        $id = "pgnjs-b".(++self::$board_id);

        if( $input ) {
            $boards[$id]['pgn'] = $input;
        } else {
            $mode = 'board';             // No pgn defaults to board mode
        }
        $boards[$id]['mode'] = $mode;
        $boards[$id]['position'] = $position;
        return "<div id=\"$id\" style=\"$style\"></div>";
    }
}

?>
