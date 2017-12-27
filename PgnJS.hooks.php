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

    public static function parserHook( $input, array $args, Parser $parser, PPFrame $frame ) {
        // Tell ResourceLoader that we need our css module
        $parser->getOutput()->addModules( 'ext.PgnJS' );

        return PgnJS::renderPgnjs($parser, $input, $args);
    }
}

class PgnJS {
    const STYLE          = "style";
    const MODE           = "mode";
    const POSITION       = "position";
    const SHOW_NOTATION  = "shownotation";  // Must be lower case or test fail!
    const ORIENTATION    = "orientation";
    const THEME          = "theme";
    const PIECE_STYLE    = "piecestyle";    // Must be lower case or test fail!
    const TIMER_TIME     = "timertime";     // Must be lower case or test fail!
    const LOCALE         = "locale";
    const BOARD_SIZE     = "boardsize";     // Must be lower case or test fail!
    const MOVES_WIDTH    = "moveswidth";    // Must be lower case or test fail!
    const MOVES_HEIGHT   = "movesheight";   // Must be lower case or test fail!
    const SCROLLABLE     = "scrollable";
    private static $board_id = 0;

    // Render <pgn>
    static public function renderPgnjs( $parser, $input, array $args ) {
        $style        = isset($args[self::STYLE]) ? $args[self::STYLE] : "width: 240px";
        $mode         = isset($args[self::MODE]) ? $args[self::MODE] : "view";
        $position     = isset($args[self::POSITION]) ? $args[self::POSITION] : null;
        $showNotation = isset($args[self::SHOW_NOTATION]) ? $args[self::SHOW_NOTATION] : null;
        $orientation  = isset($args[self::ORIENTATION]) ? $args[self::ORIENTATION] : null;
        $theme        = isset($args[self::THEME]) ? $args[self::THEME] : null;
        $pieceStyle   = isset($args[self::PIECE_STYLE]) ? $args[self::PIECE_STYLE] : "merida";
        $timerTime    = isset($args[self::TIMER_TIME]) ? $args[self::TIMER_TIME] : null;
        $locale       = isset($args[self::LOCALE]) ? $args[self::LOCALE] : null;
        $boardSize    = isset($args[self::BOARD_SIZE]) ? $args[self::BOARD_SIZE] : null;
        $movesWidth   = isset($args[self::MOVES_WIDTH]) ? $args[self::MOVES_WIDTH] : null;
        $movesHeight  = isset($args[self::MOVES_HEIGHT]) ? $args[self::MOVES_HEIGHT] : null;
        $scrollable   = isset($args[self::SCROLLABLE]) ? $args[self::SCROLLABLE] : null;

        $board = array();
        $id = "pgnjsb".(++self::$board_id);

        if( $input ) {
            $board['pgn'] = $input;
        } else {
            $mode = 'board';             // No pgn defaults to board mode
        }
        $board['mode'] = $mode;
        if( $position ) {
            $board['position'] = $position;
        }
        if( $showNotation ) {
            $board['showNotation'] = ($showNotation === 'true');
        }
        if( $orientation ) {
            $board['orientation'] = $orientation;
        }
        if( $theme ) {
            $board['theme'] = $theme;
        }
        if( $pieceStyle ) {
            $board['pieceStyle'] = $pieceStyle;
        }
        if( $timerTime ) {
            $board['timerTime'] = $timerTime;
        }
        if( $locale ) {
            $board['locale'] = $locale;
        }
        if( $boardSize ) {
            $board['boardSize'] = $boardSize;
        }
        if( $movesWidth ) {
            $board['movesWidth'] = $movesWidth;
        }
        if( $movesHeight ) {
            $board['movesHeight'] = $movesHeight;
        }
        if( $scrollable ) {
            $board['scrollable'] = ($scrollable === 'true');
        }

        $jsBoard = json_encode($board);
        $script = "<script>window.PgnJSBoards = window.PgnJSBoards || {}; window.PgnJSBoards.$id = $jsBoard;</script>";

        return "<div id=\"$id\" style=\"$style\"></div>$script";
    }
}

?>
