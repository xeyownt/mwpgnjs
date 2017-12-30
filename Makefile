EXT := PgnJS

.PHONY: all
all:
	@echo "PgnJS Mediawiki Extension makefile"
	@echo
	@echo "Available targets:"
	@echo "    make install           : Install as Mediawiki extension (since MW 1.25)."
	@echo "    make install-1.24      : Install as Mediawiki extension (MW 1.24 or earlier)."
	@echo
	@echo "        !!! install AND install-1.24 WILL DELETE ALL NON-NECESSARY FILES !!!"
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

.PHONY: install-1.24
install-1.24: install-clean
	# echo append in case LocalSettings is a symlink, to preserve symlink
	egrep -q '^[[:blank:]]*require_once[[:blank:]]+"\$$IP/extensions/$(EXT)/$(EXT).php"[[:blank:]]*;[[:blank:]]*$$' ../../LocalSettings.php || echo 'require_once "$$IP/extensions/$(EXT)/$(EXT).php";' >> ../../LocalSettings.php

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

