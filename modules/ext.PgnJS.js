'use strict';

// Let's export i18next correctly
$.i18n = $.i18n || module.exports;
i18n = $.i18n;

// console.log('PgnJS.js loaded');

$( function () {
    // This code must not be executed before the document is loaded.
    // console.log( 'Start parsing ' + typeof window.PgnJSBoards);
    for (var id in window.PgnJSBoards) { 
        // console.log( 'Found a board ' + id + ' in window: ' + window.PgnJSBoards[id]);
        var mode = window.PgnJSBoards[id].mode;
        delete window.PgnJSBoards[id].mode; // See PgnViewerJS doc: Don't try to set that on your own!
        switch(mode) {
            case "board":
                window.PgnJSBoards[id] = PGNV.pgnBoard(id, window.PgnJSBoards[id]);
                break;
            case "view":
                go_to = window.PgnJSBoards[id].goto;
                window.PgnJSBoards[id] = PGNV.pgnView(id, window.PgnJSBoards[id]);
                if( go_to && go_to === 'last' ) {
                    $('#' + id + 'Buttonlast').trigger('click');
                }
                break;
            case "edit":
                go_to = window.PgnJSBoards[id].goto;
                window.PgnJSBoards[id] = PGNV.pgnEdit(id, window.PgnJSBoards[id]);
                if( go_to && go_to === 'last' ) {
                    $('#' + id + 'Buttonlast').trigger('click');
                }
                break;
            case "print":
                window.PgnJSBoards[id] = PGNV.pgnPrint(id, window.PgnJSBoards[id]);
                break;
            default:
                console.warn("Unknown mode " + mode);
        }
    }
} );

