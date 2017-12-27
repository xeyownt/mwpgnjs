'use strict';

// Let's export i18next correctly
$.i18n = $.i18n || module.exports;
i18n = $.i18n;

// console.log('PgnJS.js loaded');

$( function () {
    // This code must not be executed before the document is loaded.
    var pgnJSBoards = mw.config.get("pgnJSBoards");
    for (var id in pgnJSBoards) { 
        // console.log( 'Found a board ' + id + ' in window: ' + pgnJSBoards[id]);
        var mode = pgnJSBoards[id].mode;
        delete pgnJSBoards[id].mode; // See PgnViewerJS doc: Don't try to set that on your own!
        switch(mode) {
            case "board":
                pgnJSBoards[id] = pgnBoard(id, pgnJSBoards[id]);
                break;
            case "view":
                pgnJSBoards[id] = pgnView(id, pgnJSBoards[id]);
                break;
            case "edit":
                pgnJSBoards[id] = pgnEdit(id, pgnJSBoards[id]);
                break;
            case "print":
                pgnJSBoards[id] = pgnPrint(id, pgnJSBoards[id]);
                break;
            default:
                console.warn("Unknown mode " + mode);
        }
    }
} );

