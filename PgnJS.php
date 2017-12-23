<?php

/*==============================================================================
 * Mediawiki PHP extension for displaying chess game in PGN format
 *
 * Copyright (C) 2007-2016  Michael Peeters <https://github.com/xeyownt>
 *
 * This file is part of the PgnJS MediaWiki extension
 * <http://www.mediawiki.org/wiki/Extension:PgnJS>.
 *
 * The PgnJS MediaWiki extension is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by * the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * The PgnJS MediaWiki extension is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 *==============================================================================
 */

if (defined('MEDIAWIKI')) {
    $wgHooks['ParserFirstCallInit'][] = 'MWPgnJS::onParserFirstCallInit';
    $wgHooks['BeforePageDisplay'][]   = 'MWPgnJS::onBeforePageDisplay';

    $wgResourceModules['ext.PgnJS'] = array(
        'localBasePath' => __DIR__,
        'remoteExtPath' => 'PgnJS',
        'styles'        => array('PgnViewerJS/dist/css/pgnvjs.css'),
        'position'      => 'top',
    );

    $wgExtensionCredits['parserhook'][] = array(
        'name'        => 'PgnJS',
        'version'     => '0.1.0',
        'author'      => 'Michael Peeters',
        'url'         => 'http://www.mediawiki.org/wiki/Extension:PgnJS',
        'description' => 'Render chess games in PGN or FEN format.'
    );

    class MWPgnJS {
        private static $css_module_added = false;

        static function onParserFirstCallInit( Parser $parser ) {
            // Register parser handler for tag <pgn>
            $parser->setHook( 'pgn', 'MWPgnJS::renderPgnjs' );
        }

        static function onBeforePageDisplay( OutputPage &$out, Skin &$skin )
        {
            global $wgExtensionAssetsPath;

            $script = "<script type=\"text/javascript\" src=\"$wgExtensionAssetsPath/PgnJS/PgnViewerJS/dist/js/pgnvjs.js\"></script>";
            $out->addHeadItem("itemName", $script);
            return true;
        }

        static function renderPgnjs( $input, array $args, Parser $parser, PPFrame $frame ) {
            if( ! self::$css_module_added ) {
                // Tell ResourceLoader that we need our css module
                $parser->getOutput()->addModuleStyles( 'ext.PgnJS' );
                self::$css_module_added = true;
            }
            return PgnJS::renderPgnjs($parser, $input,$args);
        }
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
