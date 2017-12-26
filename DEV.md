# PgnJS DEV

Here we give some information around the development of the PgnJS extension.
* Goals
* Design
* Tasks

## Goals

* Provide a simple tag `<pgn>`.

## Design
Design? :stuck_out_tongue_winking_eye:

* Rely on PgnViewerJS for most of the work.

### Asynchronous loading

MW ResourceLoader loads our CSS & JS modules asynchronously. As a result, we must make sure that all data
and resources is available when starting displaying the boards.

In the original design, we would replace all ocurrences of the `<pgn>` tag with `<div>` and `<script>`
tags. However this makes the synchronization more difficult.

In our case, we can exploit the fact that all data is available in the wikitext, and that this data is
processed by the PHP extension. A simpler design is then to collect all the information in `<pgn>` tags
(through the parser hook), and then produce a single javascript array that will contain the information
for all `<pgn>` tags. In MW, this can be done via the
[`OutputPage::addJsConfigVars`](https://www.mediawiki.org/wiki/Manual:OutputPage.php) API. When
initialized, the data will be available in JS using the `mw.config` object.

To make sure that the page is completely loaded before consuming this variable, we use the following
[construction](https://www.mediawiki.org/wiki/ResourceLoader/Developing_with_ResourceLoader#JavaScript)
in our JS module:

´´´javascript
// init.js
$( function () {
    // This code must not be executed before the document is loaded.
    Foo.sayHello( $( '#hello' ) );
});
´´´

This way the JS scripts starts only when the HTML is completely loaded.


### PHP versus Javascript

Strictly speaking we don't need the javascript to produce the board, only to deal with the dynamic part
(piece movement, user interaction). 

## Tasks

### Sync with PgnViewerJS
Currently we import only a subjet of the JS libraries that PgnViewJS distributes.

The list of libraries we import are:
* In file `extension.json` (for MW1.25+).
* In file `PgnJS.php` (for MW1.24).

Each time we import a new version of PngViewJS, we must check that these lists
are still ok and update if necessary. This is a bit cumbersome. Ideally PgnViewJS
would export a single library that we would import. However 2 issues prevent this:

* Issue #3 - i18next is not exported correctly.
* Issue #4 - Must not import another jQuery library.


[//]: # ( vim: set tw=105: )