define(['jquery',], function($) {

    var PlaceholderElement = {
        placeholder: [],
        currentLanguage: 0
    };

    PlaceholderElement.loadExistingPlaceholder = function() {
        $.ajax({
            url: TYPO3.settings.ajaxUrls['placeholder_get_all_placeholder'],
            method: 'POST',
            dataType: 'html',
            data: {language: PlaceholderElement.currentLanguage},
            success: function(response) {
                PlaceholderElement.placeholder = JSON.parse(response);
                $.each(document.querySelectorAll('[data-placeholder-field-id]'), function(index, placeholderContainer) {
                    PlaceholderElement.updateValue(placeholderContainer);
                });
            }
        });
    };

    /**
     * marker e.g. ###ABC###
     * the index of the PlaceholderElement.placeholder object is the database marker_identifier (e.g. ###ABC###)
     *
     * @param marker
     * @returns {boolean}
     */
    PlaceholderElement.existPlaceholder = function(marker) {
        for (let i = 0; i < Object.keys(PlaceholderElement.placeholder).length; i++) {
            if (Object.keys(PlaceholderElement.placeholder)[i] === marker) {
                return true;
            }
        }
        return false;
    };

    PlaceholderElement.showPlaceholder = function(placeholderGroup) {
        let placeholder = placeholderGroup.querySelectorAll('[data-placeholder]');

        for (let i = 0; i < placeholder.length; i++) {
            if (this.placeholder[placeholder[i].getAttribute('data-placeholder')]) {
                placeholder[i].innerHTML = this.placeholder[placeholder[i].getAttribute('data-placeholder')].value;
            }
        }
    }

    PlaceholderElement.hidePlaceholder = function(placeholderGroup) {
        let placeholder = placeholderGroup.querySelectorAll('[data-placeholder]');

        for (let i = 0; i < placeholder.length; i++) {
            placeholder[i].innerHTML = placeholder[i].getAttribute('data-placeholder');
        }
    }

    PlaceholderElement.updateValue = function(placeholderContainer) {
        let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
        let currentInputString = editableContainer.innerHTML;
        //let expr = new RegExp('([#]{3}[A-Z0-9]*[#]{3})([^<])', 'gm');
        let expr = new RegExp('[#]{3}[A-Z0-9]*[#]{3}', 'gm');

        if (currentInputString.match(expr)) {
            $.each(currentInputString.match(expr), function(index, element) {
                let currentPlaceholderExpr = new RegExp('(?<!>|data-placeholder=")' + element + '', 'gm');
                let color = 'red';
                if (PlaceholderElement.existPlaceholder(element)) {
                    color = 'green';
                }

                currentInputString = currentInputString
                    .replace(currentPlaceholderExpr, '<span data-placeholder="' + element + '" style="color: ' + color + ';">' + element + '</span>');
            });
        }

        // Update input
        placeholderContainer.querySelector('[data-placeholder-input]').value = editableContainer.innerHTML;
        // update container
        placeholderContainer.querySelector('.placeholder-overlay').innerHTML = currentInputString;
    }

    PlaceholderElement.setCurrentLanguage = function() {
        let fieldContainer = document.querySelector('.placeholder-group');
        this.currentLanguage = parseInt(fieldContainer.getAttribute('data-placeholder-record-language'));
    };

    PlaceholderElement.initEventListener = function(placeholderContainer) {
        let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
        let hide = placeholderContainer.querySelector('[data-placeholder-hide]');
        let show = placeholderContainer.querySelector('[data-placeholder-show]');

        // Paste Event
        editableContainer.addEventListener('paste', function(e) {
            e.preventDefault();
            let text = e.clipboardData.getData('text/plain');
            document.execCommand('insertHTML', false, text);

            PlaceholderElement.updateValue(placeholderContainer);
        });

        $(editableContainer).prop('contentEditable', true).keyup(function() {
            PlaceholderElement.updateValue(placeholderContainer);
        });

        show.addEventListener('click', function() {
            hide.classList.toggle('hide');
            show.classList.toggle('hide');

            let placeholderGroup = placeholderContainer.querySelector('.placeholder-group');
            PlaceholderElement.showPlaceholder(placeholderGroup);
        });

        hide.addEventListener('click', function() {
            show.classList.toggle('hide');
            hide.classList.toggle('hide');

            let placeholderGroup = placeholderContainer.querySelector('.placeholder-group');
            PlaceholderElement.hidePlaceholder(placeholderGroup);
        });
    }

    PlaceholderElement.init = function() {
        this.loadExistingPlaceholder();
        this.setCurrentLanguage();

        // foreach über alle input felder
        $.each(document.querySelectorAll('[data-placeholder-field-id]'), function(index, placeholderContainer) {
            let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
            PlaceholderElement.initEventListener(placeholderContainer);

            // Set current value
            editableContainer.innerHTML = placeholderContainer.querySelector('[data-placeholder-input]').value;
            placeholderContainer.querySelector('.placeholder-overlay').innerHTML = placeholderContainer.querySelector('[data-placeholder-input]').value;

        });

    };

    PlaceholderElement.init();
    return PlaceholderElement;
});

