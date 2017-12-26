'use strict';

// Let's export i18next correctly
$.i18n = $.i18n || module.exports;
i18n = $.i18n;

console.log('PgnJS.js loaded');

$( function () {
    // This code must not be executed before the document is loaded.
    var pgnJSBoards = mw.config.get("pgnJSBoards");
    for (var id in pgnJSBoards) { 
        console.log( 'Found a board ' + id + ' in window: ' + pgnJSBoards[id]);
        pgnJSBoards[id] = (pgnJSBoards[id]) ? pgnView(id, { pgn: pgnJSBoards[id] }) : pgnBoard(id, {});
    }
} );

