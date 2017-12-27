# PgnJS TODO

## Features

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

## Dev

* [ ] Import back CSS delivered with FontAwesome / PgnViewerJS.
* [ ] Sanitize PGN text in <pgn> tag. What if it contains double-quotes `"`?
* [ ] Sanitize <pgn> tag attributes?

## MediaWiki

* [ ] Generate MW extension with either the cookiecutter-mediawiki-extension script or mwstew brol.

## Build

* [ ] Automate sync'ing of PgnJS.php and extension.json.

[//]: # ( vim: set tw=105: )
