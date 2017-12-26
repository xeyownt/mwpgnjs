'use strict';

// Let's export i18next correctly
$.i18n = $.i18n || module.exports;
i18n = $.i18n;

for (var boardId in window.PgnJSBoards) { 
    console.warn( 'Found a board ' + boardId + ' in window: ' + window.PgnJSBoards[boardId]);
    window.PgnJSBoards[boardId] = pgnView(boardId, { pgn: window.PgnJSBoards[boardId] });
}

