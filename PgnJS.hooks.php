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
    const A_STYLE          = "style";
    const A_CLASS          = "class";
    const A_MODE           = "mode";
    const MODE_DEFAULTS    = "defaults";
    const A_POSITION       = "position";
    const A_SHOW_NOTATION  = "shownotation";  // Must be lower case or test fail!
    const A_ORIENTATION    = "orientation";
    const A_THEME          = "theme";
    const A_PIECE_STYLE    = "piecestyle";    // Must be lower case or test fail!
    const A_TIMER_TIME     = "timertime";     // Must be lower case or test fail!
    const A_LOCALE         = "locale";
    const A_BOARD_SIZE     = "boardsize";     // Must be lower case or test fail!
    const A_SHOW_FEN       = "showfen";       // Must be lower case or test fail!
    const A_SIZE           = "size";          // Must be lower case or test fail!
    const A_MOVES_WIDTH    = "moveswidth";    // Must be lower case or test fail!
    const A_MOVES_HEIGHT   = "movesheight";   // Must be lower case or test fail!
    const A_SCROLLABLE     = "scrollable";
    const A_HEADERS        = "headers";
    const A_LAYOUT         = "layout";
    const A_GOTO           = "goto";
    const DEFAULTS         = array( 'style' => 'width: 240px', 'pieceStyle' => 'merida' );
    private static $board_id = 0;
    private static $board_defaults = array();
    private static $board_class_defaults = array();

    // Render <pgn>
    static public function renderPgnjs( $parser, $input, array $args ) {
        $a_style        = isset($args[self::A_STYLE]) ? $args[self::A_STYLE] : null;
        $a_class        = isset($args[self::A_CLASS]) ? $args[self::A_CLASS] : null;
        $a_mode         = isset($args[self::A_MODE]) ? $args[self::A_MODE] : "view";
        $a_position     = isset($args[self::A_POSITION]) ? $args[self::A_POSITION] : null;
        $a_showNotation = isset($args[self::A_SHOW_NOTATION]) ? $args[self::A_SHOW_NOTATION] : null;
        $a_orientation  = isset($args[self::A_ORIENTATION]) ? $args[self::A_ORIENTATION] : null;
        $a_theme        = isset($args[self::A_THEME]) ? $args[self::A_THEME] : null;
        $a_pieceStyle   = isset($args[self::A_PIECE_STYLE]) ? $args[self::A_PIECE_STYLE] : null;
        $a_timerTime    = isset($args[self::A_TIMER_TIME]) ? $args[self::A_TIMER_TIME] : null;
        $a_locale       = isset($args[self::A_LOCALE]) ? $args[self::A_LOCALE] : null;
        $a_boardSize    = isset($args[self::A_BOARD_SIZE]) ? $args[self::A_BOARD_SIZE] : null;
        $a_showFen      = isset($args[self::A_SHOW_FEN]) ? $args[self::A_SHOW_FEN] : null;
        $a_size         = isset($args[self::A_SIZE]) ? $args[self::A_SIZE] : null;
        $a_layout       = isset($args[self::A_LAYOUT]) ? $args[self::A_LAYOUT] : null;
        $a_movesWidth   = isset($args[self::A_MOVES_WIDTH]) ? $args[self::A_MOVES_WIDTH] : null;
        $a_movesHeight  = isset($args[self::A_MOVES_HEIGHT]) ? $args[self::A_MOVES_HEIGHT] : null;
        $a_scrollable   = isset($args[self::A_SCROLLABLE]) ? $args[self::A_SCROLLABLE] : null;
        $a_headers      = isset($args[self::A_HEADERS]) ? $args[self::A_HEADERS] : null;
        $a_goto         = isset($args[self::A_GOTO]) ? $args[self::A_GOTO] : null;

        $board = array();
        if( $a_mode !== self::MODE_DEFAULTS) {
            foreach( explode(' ', $a_class) as $c ) {
                if( $c and isset(self::$board_class_defaults[$c]) ) {
                    $board += self::$board_class_defaults[$c];  // First listed class as precedence
                }
            }
            $board += self::$board_defaults;  // non-class defaults have less precedence
            $board += self::DEFAULTS;         // Finally hard-coded defaults have least precedence
        }
        $id = "pgnjs".(++self::$board_id);

        if( $input ) {
            $board['pgn'] = $input;
        } else {
            if( ($a_mode !== 'edit') and ($a_mode !== self::MODE_DEFAULTS) ) {
                $a_mode = 'board';         // Fall-back to 'board' mode, unless in edit or defaults mode
            }
        }
        if( $a_style ) {
            $board['style'] = $a_style;
        }
        if( $a_position ) {
            $board['position'] = $a_position;
        }
        if( $a_showNotation ) {
            $board['showNotation'] = ($a_showNotation === 'true');
        }
        if( $a_orientation ) {
            $board['orientation'] = $a_orientation;
        }
        if( $a_theme ) {
            $board['theme'] = $a_theme;
        }
        if( $a_pieceStyle ) {
            $board['pieceStyle'] = $a_pieceStyle;
        }
        if( $a_timerTime ) {
            $board['timerTime'] = $a_timerTime;
        }
        if( $a_locale ) {
            $board['locale'] = $a_locale;
        }
        if( $a_boardSize ) {
            $board['boardSize'] = $a_boardSize;
        }
        if( $a_showFen ) {
            $board['showFen'] = ($a_showFen === 'true');
        }
        if( $a_size ) {
            $board['size'] = $a_size;
        }
        if( $a_layout ) {
            $board['layout'] = $a_layout;
        }
        if( $a_movesWidth ) {
            $board['movesWidth'] = $a_movesWidth;
        }
        if( $a_movesHeight ) {
            $board['movesHeight'] = $a_movesHeight;
        }
        if( $a_scrollable ) {
            $board['scrollable'] = ($a_scrollable === 'true');
        }
        if( $a_headers ) {
            $board['headers'] = ($a_headers === 'true');
        }
        if( $a_goto && ($a_goto === 'last' or $a_goto === 'first') ) {
            $board['goto'] = $a_goto;
        }

        if( $a_mode === self::MODE_DEFAULTS ) {
            // If mode is defaults, we save the config as defaults
            $has_class = false;
            foreach( explode(' ', $a_class) as $c ) {
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
            $board['mode'] = $a_mode;                     // must set the mode for our parser

            // Remove attributes unknown to PgnViewerJS
            $a_style = $board['style'];
            unset($board['style']);

            $jsBoard = json_encode($board);
            $script = "<script>window.PgnJSBoards = window.PgnJSBoards || {}; window.PgnJSBoards.$id = $jsBoard;</script>";

            $class_attr = $a_class ? " class=\"$a_class\"" : "";
            return "<div id=\"$id\"$class_attr style=\"$a_style\"></div>$script";
        }
    }
}

?>
