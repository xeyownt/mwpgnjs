var examples = {};

examples["1000"] = {
    desc: "Use PgnViewerJS for only displaying a board. See the section \"Boards\" for details on that.",
    html: "<div id=\"board\" style=\"width: 400px\"><\/div>",
    name: "Board with defaults",
    jsStr: "var board = pgnBoard('board', {});",
    jsFn: function() {
        var board = pgnBoard('board', {});
    }
};

examples["1001"] = {
    desc: "Use PgnViewerJS for showing a (short) game. The buttons, the board and the display of the " +
        "moves are all default values, that may be changed by configuration parameters. Parameter pgn is " +
        "mandatory when using \"pgnView\".",
    html: "<div id=\"board\" style=\"width: 400px\"><\/div>",
    name: "Shortest game possible",
    jsStr: "var pgn = \"1. f4 e6 2. g4 Qh4#\";\nvar board = pgnView('board', {pgn: pgn});",
    jsFn: function() {
        var pgn = "1. f4 e6 2. g4 Qh4#";
        var board = pgnView('board', {pgn: pgn});
    }
};

examples["1002"] = {
    desc: "Use PgnViewerJS for displaying a game in typical notation, with diagrams and different styles " +
        "for the moves, the boards, ... This is not implemented yet!! So the diagram on the right is a placeholder.",
    html: "<div id=\"board\" style=\"width: 300px\"><\/div>",
    name: "Printing a game",
    jsStr: "var pgn = \"1. f4 e6 2. g4 Qh4#\";\nvar board = pgnPrint('board', {pgn: pgn});",
    jsFn: function() {
        var pgn = "1. f4 e6 2. g4 Qh4#";
        var board = pgnView('board', {pgn: pgn});
    }
};

examples["1003"] = {
    desc: "Use PgnViewerJS for viewing a game with the option to edit it by adding variations, comments, " +
        "... This is not implemented yet!! So the diagram on the right is a placeholder.",
    html: "<div id=\"board\" style=\"width: 300px\"><\/div>",
    name: "Editing a game",
    jsStr: "var pgn = \"1. f4 e6 2. g4 Qh4#\";\nvar board = pgnEdit('board', {pgn: pgn});",
    jsFn: function() {
        var pgn = "1. f4 e6 2. g4 Qh4#";
        var board = pgnView('board', {pgn: pgn});
    }
};

examples["1020"] = {
    desc: "ChessBoard initializes to the starting position on board with an empty configuration.",
    html: "<div id=\"board\" style=\"width: 400px\"><\/div>",
    name: "Starting Board",
    jsStr: "var board = pgnBoard('board', {});",
    jsFn: function() {
        var board = pgnBoard('board', {});
    }
};
examples["1021"] = {
    desc: "ChessBoard with theme 'zeit' / 'green' and pieceStyle 'merida' / 'case'.",
    html: "<div id=\"board\" style=\"width: 300px\"><\/div>\n<div id=\"board2\" style=\"width: 300px\"><\/div>",
    name: "Theme: Zeit and Style: Merida",
    jsStr: "var board = pgnBoard('board', {" +
        "\n     pieceStyle: 'merida', " +
        "\n     theme: 'zeit'});" +
"\nvar board2 = pgnBoard('board2', {" +
        "\n     pieceStyle: 'case', " +
        "\n     theme: 'green'});",
    jsFn: function() {
        var board = pgnBoard('board', {pieceStyle: 'merida', theme: 'zeit'});
        var board2 = pgnBoard('board2', {pieceStyle: 'case', theme: 'green'});
    }
};

examples["1022"] = {
    desc: "ChessBoard with different positions. See the start position, a short finished game " +
        "and the Ruy Lopez after the first three moves.",
    html: "<div id=\"b1\" style=\"width: 300px; margin: 20px\"><\/div>\n<div id=\"b2\" style=\"width: 300px; margin: 20px\"><\/div>" +
        "\n<div id=\"b3\" style=\"width: 300px; margin: 20px\"><\/div>",
    name: "Different positions",
    jsStr: "var fen1 = 'start';" +
        "\nvar fen2 = \"rnb1kbnr/pppp1ppp/4p3/8/5PPq/8/PPPPP2P/RNBQKBNR w KQkq - 0 3\";" +
        "\nvar fen3 = \"r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq - 0 4\";" +
        "\npgnBoard('b1', {fen: fen1});" +
        "\npgnBoard('b2', {position: fen2});" +
        "\npgnBoard('b3', {position: fen3});",
    jsFn: function() {
        var fen1 = 'start';
        var fen2 = 'rnb1kbnr/pppp1ppp/4p3/8/5PPq/8/PPPPP2P/RNBQKBNR w KQkq - 0 3';
        var fen3 = 'r1bqkbnr/1ppp1ppp/p1n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq - 0 4';
        pgnBoard('b1', {position: fen1});
        pgnBoard('b2', {position: fen2});
        pgnBoard('b3', {position: fen3});
    }
};

var htmlEscape = function(str) {
    return (str + '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/\//g, '&#x2F;')
        .replace(/`/g, '&#x60;');
};

var highlightGroupHeader = function(groupIndex) {
    $('div#examples_list_container h4').removeClass('active');
    $('h4#group_header_' + groupIndex).addClass('active');
};

var highlightExample = function(id) {
    $('div#examples_list_container li').removeClass('active');
    $('li#example_' + id).addClass('active');
};

var showExample = function(number) {
    var groupIndex = parseInt($('li#example_' + number)
        .parent('ul').attr('id').replace('group_container_', ''), 10);

    $('ul#group_container_' + groupIndex).css('display', '');
    highlightGroupHeader(groupIndex);
    highlightExample(number);

    $('#example_name').html(examples[number].name);
    $('#example_single_page_link').attr('href', 'examples/' + number);
    $('#example_desc_container').html(examples[number].desc);
    $('#example_html_container').html(examples[number].html);
    $('#example_js_container').html('<pre class="prettyprint">' + examples[number].jsStr + '</pre>');
    $('#example_show_html_container').html('<pre class="prettyprint">' + htmlEscape(examples[number].html) + '</pre>');
    examples[number].jsFn();
    prettyPrint();
};

var clickExample = function() {
    var number = parseInt($(this).attr('id').replace('example_', ''), 10);
    if (examples.hasOwnProperty(number) !== true) return;

    window.location.hash = number;
    loadExampleFromHash();
};

var loadExampleFromHash = function() {
    var number = parseInt(window.location.hash.replace('#', ''), 10);
    if (examples.hasOwnProperty(number) !== true) {
        number = 1000;
        window.location.hash = number;
    }
    showExample(number);
};

var clickGroupHeader = function() {
    var groupIndex = parseInt($(this).attr('id').replace('group_header_', ''), 10);
    var examplesEl = $('ul#group_container_' + groupIndex);
    if (examplesEl.css('display') === 'none') {
        examplesEl.slideDown('fast');
    }
    else {
        examplesEl.slideUp('fast');
    }
};

var init = function() {
    $('#examples_list_container').on('click', 'li', clickExample);
    $('#examples_list_container').on('click', 'h4', clickGroupHeader);
    loadExampleFromHash();
};
$(document).ready(init);