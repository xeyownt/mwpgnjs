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

My first idea was to exploit the fact that all data is available in the wikitext, and that this data is
processed by the PHP extension. A simpler design is then to collect all the information in `<pgn>` tags
(through the parser hook), and then produce a single javascript array that will contain the information
for all `<pgn>` tags. In MW, this can be done via the
[`OutputPage::addJsConfigVars`](https://www.mediawiki.org/wiki/Manual:OutputPage.php) API. When
initialized, the data will be available in JS using the `mw.config` object.

This idea however fails when MediaWiki uses cache (such as *memcached*). In that case, the parser hook
is only called when the wiki text is changed. As a result, we must fall-back on the generation of
inline `<script>` tags. These tags will save the board attributes as a javascript array (serialized from
the PHP array using `json_encode()`), and save it in the global variable `window.PgnJSBoards`.

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

### Create a new release

After adding a few features or fixing a few bugs, we create a new git tag and create a new release.
By creating a new tag, GitHub will automatically add a new release in the [release tab](releases/).

All tags follow the [Semantic Versioning](https://semver.org) standard.
To create a new tag, we use the make targets (`make` will give you a list):
* `make new-version-patch` to create a new PATCH version. Use this when the new release only fix bugs in a
  backward-compatible way.
* `make new-version-minor` to create a new MINOR version. Use this when the new release provides new
  functionality in a backward-compatible way.
* `make new-version-major` to create a new MAJOR version. Use this when the new release breaks
  backward-comatibility

These targets will only change local files (`VERSION`, `extension.json`...) but will not create any
commit. To create a new git commit, use target `release`.

So typically, to create a new PATCH release, and push to GitHub:

```bash
make new-version-patch
make release
git push origin master --tags
```

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
