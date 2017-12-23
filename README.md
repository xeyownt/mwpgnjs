# PgnJS
PgnJS is a MediaWiki extension that embeds chess games (boards and moves) in a wiki page. Chess games are
simply given in PGN format in a `<pgn>` tag and displayed thanks to the powerful javascript engine
[**PgnViewerJS**](https://github.com/mliebelt/PgnViewerJS). In fact most of the functionality is provided by
PgnViewerJS. This extension only implements the parsing of the `<pgn>` tag.

## Status
Currently this extension is in *alpha* status (still in development). It works for me on my wiki.

Tested on
* Mediawiki 1.27.4.
* Mediawiki 1.22.1 (older version of this extension).

## Installation

For now the extension is still in development status, so the easiest is to clone the whole repository
into your MediaWiki `extensions` folder:
```bash
cd /path/to/wiki/extensions
git clone --recursive https://github.com/xeyownt/mwpgnjs.git PgnJS
```
Don't forget the `--recursive` keyword since the extension uses submodules.

Then add at the bottom of your `LocalSettings.php` file:
```php
// For MW 1.25 or above:
wfLoadExtension( 'PgnJS' );
// For MW 1.24 or earlier:
require_once "$IP/extensions/PgnJS/PgnJS.php";
```

Done! Navigate to `Special:Version` on your wiki to verify that the extension is successfully installed.

### Update

To update the extension:

```
cd /path/to/wiki/extensions/PgnJS
git pull --recurse-submodules=yes origin
```

Again, don't forget the `--recurse-submodules` since the extension uses submodules.

## Usage
The extension provides a new tag `<pgn>`.

To embed a new chess game on your page, simply enclose the PGN representation of that game in a `<pgn>`
tag.

Some examples:

Code                  | Result
----------------------|-----------
`<pgn/>` | An empty board
`<pgn>1. f4 e6 2. g4 Qh4</pgn>` | A board with moves
`<pgn style="width: 320px">1. f3 e6 2. g4 Qh4</pgn>` | Same but with some styling

## Links

* [**PgnViewerJS** on GitHub](https://github.com/mliebelt/PgnViewerJS).
