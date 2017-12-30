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
    const C_CLASS        = "class";
    const MODE           = "mode";
    const MODE_DEFAULTS  = "defaults";
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
    const LAYOUT         = "layout";
    const GO_TO          = "goto";          // Should be GOTO but reserved word
    private static $board_id = 0;
    private static $board_defaults = array();
    private static $board_class_defaults = array();

    // Render <pgn>
    static public function renderPgnjs( $parser, $input, array $args ) {
        $style        = isset($args[self::STYLE]) ? $args[self::STYLE] : null;
        $class        = isset($args[self::C_CLASS]) ? $args[self::C_CLASS] : null;
        $mode         = isset($args[self::MODE]) ? $args[self::MODE] : "view";
        $position     = isset($args[self::POSITION]) ? $args[self::POSITION] : null;
        $showNotation = isset($args[self::SHOW_NOTATION]) ? $args[self::SHOW_NOTATION] : null;
        $orientation  = isset($args[self::ORIENTATION]) ? $args[self::ORIENTATION] : null;
        $theme        = isset($args[self::THEME]) ? $args[self::THEME] : null;
        $pieceStyle   = isset($args[self::PIECE_STYLE]) ? $args[self::PIECE_STYLE] : null;
        $timerTime    = isset($args[self::TIMER_TIME]) ? $args[self::TIMER_TIME] : null;
        $locale       = isset($args[self::LOCALE]) ? $args[self::LOCALE] : null;
        $boardSize    = isset($args[self::BOARD_SIZE]) ? $args[self::BOARD_SIZE] : null;
        $movesWidth   = isset($args[self::MOVES_WIDTH]) ? $args[self::MOVES_WIDTH] : null;
        $movesHeight  = isset($args[self::MOVES_HEIGHT]) ? $args[self::MOVES_HEIGHT] : null;
        $scrollable   = isset($args[self::SCROLLABLE]) ? $args[self::SCROLLABLE] : null;
        $layout       = isset($args[self::LAYOUT]) ? $args[self::LAYOUT] : null;
        $goto         = isset($args[self::GO_TO]) ? $args[self::GO_TO] : null;

        $board = array();
        if( $mode !== self::MODE_DEFAULTS) {
            foreach( explode(' ', $class) as $c ) {
                if( $c and isset(self::$board_class_defaults[$c]) ) {
                    $board += self::$board_class_defaults[$c];  // First listed class as precedence
                }
            }
            $board += self::$board_defaults;  // non-class defaults have less precedence
            $board += array( 'style' => 'width: 240px', 'pieceStyle' => 'merida' );  // Finally hard-coded defaults
        }
        $id = "pgnjs".(++self::$board_id);

        if( $input ) {
            $board['pgn'] = $input;
        } else {
            if( ($mode !== 'edit') and ($mode !== self::MODE_DEFAULTS) ) {
                $mode = 'board';         // Fall-back to 'board' mode, unless in edit or defaults mode
            }
        }
        if( $style ) {
            $board['style'] = $style;
        }
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
        if( $layout ) {
            $board['layout'] = $layout;
        }
        if( $goto && ($goto === 'last' or $goto === 'first') ) {
            $board['goto'] = $goto;
        }

        if( $mode === self::MODE_DEFAULTS ) {
            // If mode is defaults, we save the config as defaults
            $has_class = false;
            foreach( explode(' ', $class) as $c ) {
                if( $c ) {
                    self::$board_class_defaults[$c] = $board;
                    $has_class = true;
                }
            }
            // non-class defaults assigned only if no class given
            if( !$has_class ) {
                self::$board_defaults = $board;
            }
            return "";
        } else {
            // mode is not setting defaults, generate HTML and JS to display the board
            $board['mode'] = $mode;                     // must set the mode for our parser

            // Remove attributes unknown to PgnViewerJS
            $style = $board['style'];
            unset($board['style']);

            $jsBoard = json_encode($board);
            $script = "<script>window.PgnJSBoards = window.PgnJSBoards || {}; window.PgnJSBoards.$id = $jsBoard;</script>";

            $class_attr = $class ? " class=\"$class\"" : "";
            return "<div id=\"$id\"$class_attr style=\"$style\"></div>$script";
        }
    }
}

?>
