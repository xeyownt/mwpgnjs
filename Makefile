EXT := PgnJS

.PHONY: all
all:
	@echo "PgnJS Mediawiki Extension makefile"
	@echo
	@echo "Available targets:"
	@echo "    make install           : Install as Mediawiki extension (since MW 1.25)."
	@echo
	@echo "        !!! install WILL DELETE ALL NON-NECESSARY FILES !!!"
	@echo
	@echo "    make i18n              : Generate i18n json file"
	@echo
	@echo "    make new-version-patch : Generate new version number, increase PATCH."
	@echo "    make new-version-minor : Generate new version number, increase MINOR."
	@echo "    make new-version-major : Generate new version number, increase MAJOR."
	@echo
	@echo "    make release           : Make new git release."
	@echo

.PHONY: install-clean
install-clean:
	rm -rf .git COPYING doc Makefile *.md VERSION img tests
	mv -t . PgnViewerJS/dist PgnViewerJS/chess.js PgnViewerJS/chessboardjs PgnViewerJS/js && rm -rf PgnViewerJS && mkdir PgnViewerJS && mv -t PgnViewerJS chess.js chessboardjs dist js

.PHONY: install
install: install-clean
	# echo append in case LocalSettings is a symlink, to preserve symlink
	egrep -q "^[[:blank:]]*wfLoadExtension[[:blank:]]*\([[:blank:]]*'$(EXT)'[[:blank:]]*\)[[:blank:]]*;[[:blank:]]*\$$" ../../LocalSettings.php || echo "wfLoadExtension( '$(EXT)' );" >> ../../LocalSettings.php

.PHONY: new-version-patch-helper
new-version-patch-helper:
	echo $(shell cat VERSION) | awk -F '.' '{printf "%d.%d.%d", $$1, $$2, ($$3 + 1);}' > VERSION
	@echo "New PATCH version $$(cat VERSION)"

.PHONY: new-version-minor-helper
new-version-minor-helper:
	echo $(shell cat VERSION) | awk -F '.' '{printf "%d.%d.%d", $$1, ($$2 + 1), 0;}' > VERSION
	@echo "New MINOR version $$(cat VERSION)"

.PHONY: new-version-major-helper
new-version-major-helper:
	echo $(shell cat VERSION) | awk -F '.' '{printf "%d.%d.%d", ($$1 + 1), 0, 0;}' > VERSION
	@echo "New MAJOR version $$(cat VERSION)"

.PHONY: new-version-helper
new-version-helper:
	sed -ri "/\"version\"/s/\"version\".*/\"version\": \"$$(cat VERSION)\",/" extension.json
	sed -ri "/'version'/s/'version'.*/'version'     => '$$(cat VERSION)',/" $(EXT).php

.PHONY: new-version-patch
new-version-patch: new-version-patch-helper new-version-helper

.PHONY: new-version-minor
new-version-minor: new-version-minor-helper new-version-helper

.PHONY: new-version-major
new-version-major: new-version-major-helper new-version-helper

.PHONY: i18n
i18n:
	cd i18n; rm *.json; php php_to_json.php

.PHONY: release
release: i18n
	git add -A
	git commit --allow-empty --edit -m "Release v$$(cat VERSION)"
	git tag v$$(cat VERSION)

PgnViewerJS/node_modules:
	cd $(@D) && npm install

PgnViewerJS/modules/pgn-viewer/node_modules: PgnViewerJS/node_modules
	cd $(@D) && npm install

.PHONY: PgnViewerJS/modules/pgn-viewer/lib/pgnv.js
PgnViewerJS/modules/pgn-viewer/lib/pgnv.js: PgnViewerJS/modules/pgn-viewer/node_modules
	# An ugly patch. If this fails, it means chessground module changed
	[ -e $</chessground/render.js ] && egrep -q 'return `\$${piece.color}(` \+ " " \+ `| )\$${piece.role}`;' $</chessground/render.js
	sed -ri 's/return `\$$\{piece.color\} \$$\{piece.role\}`;/return `$${piece.color}` + " " + `$${piece.role}`;/' $</chessground/render.js
	# Set the default theme back to brown, to avoid upsetting the unique user of this extension
	[ -e PgnViewerJS/modules/pgn-viewer/src/pgnvjs.js ] && egrep -q '^ +theme: "(blue|brown)",$$' PgnViewerJS/modules/pgn-viewer/src/pgnvjs.js
	sed -ri 's/^( +theme:) "blue",$$/\1 "brown",/' PgnViewerJS/modules/pgn-viewer/src/pgnvjs.js
	cd $(@:/lib/pgnv.js=) && npm run build

modules/pgnv.js: PgnViewerJS/modules/pgn-viewer/lib/pgnv.js
	cp -r $(<D)/* $(@D)

pgnv: modules/pgnv.js

clean:
	git clean -dxf
