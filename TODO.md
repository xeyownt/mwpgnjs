# PgnJS TODO

## Features

### PgnViewerJS features
* [X] Support the 4 modes: board, view, edit and print.
* [X] Support attribute `position` (in combination with PGN moves).
* [ ] Support attribute `pgnFile`. But where would that file come from?
* [X] Support attribute `showNotation`.
* [X] Support attribute `orientation`.
* [X] Support attribute `theme`.
* [X] Support attribute `pieceStyle` (but default set to `merida`).
* [X] Support attribute `timerTime`.
* [X] Support attribute `locale`.
* [X] Support attribute `boardSize`.
* [X] Support attribute `showFen`.
* [X] Support attribute `size`.
* [X] Support attribute `layout`.
* [X] Support attribute `movesWidth`.
* [X] Support attribute `movesHeight`.
* [X] Support attribute `scrollable`.
* [X] Support attribute `headers`.
* [ ] Support different layouts (through 
[Allow different markup](http://mliebelt.github.io/PgnViewerJS/docu/examples.html#1217)).

### New features
* [X] Attribute `goto` (to show board at a given position in view and edit mode).
* [ ] Align with PgnViewerJS issue on attribute `goto` (mliebelt/PgnViewerJS#75).
* [X] Attribute `class` to specify classnames for CSS.
* [X] Mode `defaults` to save configuration defaults (link with given classnames).
* [X] Transclude wiki templates in PGN string.
* [X] Configure theme, piece style... through the user preference page.
* [X] Use user locale as default.
* [ ] Use the locale for the current request (this implies reading it in javascript).
* [X] Add timerTime to user preference.

### Read locale for current request
Locale can be overridden in the URL using `&uselang=xyz` (see
[Manual:Language](https://www.mediawiki.org/wiki/Manual:Language)). This information is stored the
context object, which must never be accessed from parser hooks (remember that parser results can be
cached).

The solution would then be to read the locale from Javascript context instead.

## Wanted PgnViewerJS features

Some of these features might already be listed in PgnViewerJS plans.

* [ ] In View mode, add a button to copy the current FEN (as displayed), or PGN. However too many buttons
  would not be nice, so maybe add this as a right click menu (for instance, replace the black/white
  button with a menu button.)
* [ ] In View mode, add a button to switch to edit mode.
* [ ] In View mode, add a button to trigger StockFish analysis.
* [ ] Allow to store some user global defaults (for instance configured via the menu button). This would
  be the theme, the last / possible move highlights...
* [ ] In View / Edit mode, add an attribute to indicate which moves to select on page creation. This
  would useful for instance when listing various openings. We should the PGN and resulting position
  without the need to click on buttons. The user may then use the buttons to play back and forth the
  opening moves.
* [ ] The ability to display the same board several times, at several different move. For instance, see
  the wikipedia page on [Fool's mate](https://en.wikipedia.org/wiki/Fool%27s_mate). A typical use case in
  wiki, is to give a lot of details about a game development, and show side snapshots of that game
  (usually with a given legend). That feature is similar to the `print` mode, but mixed with the ability
  to have a custom layout.
* [ ] Annotation of squares (different colors) and arrows (different colors) per move, as annotation in
  PGN.
  * For arrows, see [this question on SO](https://stackoverflow.com/questions/25527902/drawing-arrows-on-a-chess-board-in-javascript).
  * A solution could be to use a more powerful UI, like [Chessground](https://github.com/ornicar/chessground),
    the open-source UI on lichess.org.

### Start at a user-given move (attribute `goto`)
In inspector, the event attached to last button is:
```
function() {
  var fen = that.mypgn.getMove(that.mypgn.getMoves().length - 1).fen;
  makeMove(that.currentMove, that.mypgn.getMoves().length - 1, fen);
}
```
By using the inspector, we see that the last move button is named something like `pgnjsb1Buttonlast`. We
can simulate a click with
```javascript
$('#' + id + 'Buttonlast').trigger('click');
```

## Dev

* [ ] Import back CSS delivered with FontAwesome / PgnViewerJS.
* [ ] Sanitize PGN text in <pgn> tag. What if it contains double-quotes `"`?
* [ ] Sanitize <pgn> tag attributes?
* [ ] Find a better solution to define `__globalCustomDomain` that adding a single `<script>` element.
* [ ] How can we boost rendering performance? Can we do Server-Side Rendering (ask PgnViewerJS) to render
  in PHP and serve that as a static board, then click a button will go interactive, possibly with delay.

## MediaWiki

* [ ] Generate MW extension with either the cookiecutter-mediawiki-extension script or mwstew brol.

## Build

* [X] Automate sync'ing of PgnJS.php and extension.json. Done: we deprecated MW1.24.

[//]: # ( vim: set tw=105: )
