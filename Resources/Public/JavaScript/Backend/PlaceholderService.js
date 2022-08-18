define(['jquery',], function($) {

    var PlaceholderService = {
        placeholder: [],
        isInitialized: false,
        currentLanguage: 0
    };

    /**
     * @returns {Promise}
     */
    PlaceholderService.init = function() {
        return new Promise(function(resolve) {
            if (!this.isInitialized) {
                PlaceholderService.setCurrentLanguage();
                PlaceholderService.loadExistingPlaceholder().then(function(response) {
                    PlaceholderService.placeholder = JSON.parse(response);
                    PlaceholderService.isInitialized = true;
                    resolve("done");
                });
            }
        });
    };

    PlaceholderService.setCurrentLanguage = function() {
        let elementWithRecordLanguage = document.querySelector('[data-placeholder-record-language]');
        if (elementWithRecordLanguage !== null) {
            this.currentLanguage = parseInt(elementWithRecordLanguage.getAttribute('data-placeholder-record-language'));
        }
    };

    /**
     * @returns {Promise}
     */
    PlaceholderService.loadExistingPlaceholder = function() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: TYPO3.settings.ajaxUrls['placeholder_get_all_placeholder'],
                method: 'POST',
                dataType: 'html',
                data: {language: PlaceholderService.currentLanguage},
                success: function(response) {
                    resolve(response);
                },
                error: function(error) {
                    reject(error);
                }
            });
        });
    };

    /**
     * marker e.g. ###ABC###
     * the index of the PlaceholderElement.placeholder object is the database marker_identifier (e.g. ###ABC###)
     *
     * @param marker
     * @returns {boolean}
     */
    PlaceholderService.existPlaceholder = function(marker) {
        for (let i = 0; i < Object.keys(this.placeholder).length; i++) {
            if (Object.keys(this.placeholder)[i] === marker) {
                return true;
            }
        }
        return false;
    };

    PlaceholderService.showPlaceholder = function(placeholderContainer) {
        let placeholder = placeholderContainer.querySelectorAll('[data-placeholder]');

        for (let i = 0; i < placeholder.length; i++) {
            if (this.placeholder[placeholder[i].getAttribute('data-placeholder')]) {
                placeholder[i].innerHTML = this.placeholder[placeholder[i].getAttribute('data-placeholder')].value;
            }
        }
    }

    PlaceholderService.hidePlaceholder = function(placeholderContainer) {
        let placeholder = placeholderContainer.querySelectorAll('[data-placeholder]');

        for (let i = 0; i < placeholder.length; i++) {
            placeholder[i].innerHTML = placeholder[i].getAttribute('data-placeholder');
        }
    }

    /**
     * @param {string} placeholderString
     * @return string
     */
    PlaceholderService.highlightPlaceholder = function(placeholderString) {
        //let expr = new RegExp('([#]{3}[A-Z0-9]*[#]{3})([^<])', 'gm');
        let expr = new RegExp('[#]{3}[A-Z0-9-+]*[#]{3}', 'gm');

        if (placeholderString.match(expr)) {
            $.each(placeholderString.match(expr), function(index, element) {
                let currentPlaceholderExpr = new RegExp('(?<!>|data-placeholder=")' + element + '', 'gm');
                let color = 'red';
                if (PlaceholderService.existPlaceholder(element)) {
                    color = 'green';
                }

                placeholderString = placeholderString.replace(currentPlaceholderExpr, '<span class="placeholder-' + color + '" data-placeholder="' + element + '">' + element + '</span>');
            });
        }

        return placeholderString;
    };

    PlaceholderService.autocomplete = function(query) {
        if (query === '') {
            return [];
        }

        query = '###' + query;

        let reg = new RegExp(query);
        let placeholderArray = Object.entries(PlaceholderService.placeholder);
        return placeholderArray.filter(function(placeholder) {
            if (placeholder[1].markerIdentifier.match(reg)) {
                return placeholder;
            }
        });
    };

    return PlaceholderService;
});

