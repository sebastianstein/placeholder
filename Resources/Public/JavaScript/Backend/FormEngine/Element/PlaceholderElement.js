define(['jquery', 'ckeditor', 'TYPO3/CMS/Placeholder/Backend/PlaceholderService'], function($, CKEDITOR, PlaceholderService) {

    let PlaceholderElement = {};

    PlaceholderElement.updateInputField = function(placeholderContainer) {
        let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
        let currentInputString = editableContainer.innerHTML;

        currentInputString = PlaceholderService.highlightPlaceholder(currentInputString);

        // Update input
        placeholderContainer.querySelector('[data-placeholder-input]').value = editableContainer.innerHTML.replaceAll('&nbsp;', ' ');
        // update container
        placeholderContainer.querySelector('.placeholder-overlay').innerHTML = currentInputString;
    }

    PlaceholderElement.initEventListener = function(placeholderContainer) {
        let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
        let input = placeholderContainer.querySelector('[data-placeholder-input]');
        let hide = placeholderContainer.querySelector('[data-placeholder-hide]');
        let show = placeholderContainer.querySelector('[data-placeholder-show]');

        // Paste Event
        editableContainer.addEventListener('paste', function(event) {
            event.preventDefault();
            let text = event.clipboardData.getData('text/plain');
            document.execCommand('insertHTML', false, text);

            PlaceholderElement.updateInputField(placeholderContainer);
        });

        $(editableContainer).prop('contentEditable', true).keyup(function() {
            PlaceholderElement.updateInputField(placeholderContainer);
            $(input).triggerHandler('keyup');
        });

        // max chars for editable content
        if (input.getAttribute('maxlength') !== null) {
            $(editableContainer).on('keydown paste', function(event) {
                let allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight'];
                if (input.value.length >= input.getAttribute('maxlength') && !allowedKeys.includes(event.key)) {
                    event.preventDefault();
                }
            });
        }

        $(editableContainer).blur(function() {
            $(input).triggerHandler('blur');
        });

        $(editableContainer).focus(function() {
            $(input).triggerHandler('focus');
        });

        show.addEventListener('click', function() {
            hide.classList.toggle('hide');
            show.classList.toggle('hide');

            PlaceholderService.showPlaceholder(placeholderContainer);
        });

        hide.addEventListener('click', function() {
            show.classList.toggle('hide');
            hide.classList.toggle('hide');

            PlaceholderService.hidePlaceholder(placeholderContainer);
        });
    }

    PlaceholderElement.init = function() {
        // @todo this should be configurable. Sadly this option can not be set via the default ckeditor configuration.
        // Because then the inline initialization is done anyway.
        // @see https://stackoverflow.com/questions/20073472/why-is-ckeditor-adding-itself-to-divs-where-it-is-not-supposed-to#comment66518085_20073624
        CKEDITOR.config.disableAutoInline = true;

        PlaceholderService.init().then(function() {
            // foreach über alle input felder
            $.each(document.querySelectorAll('[data-placeholder-field-id]'), function(index, placeholderContainer) {
                let editableContainer = placeholderContainer.querySelector('.placeholder-editable');
                PlaceholderElement.initEventListener(placeholderContainer);

                // Set current value
                editableContainer.innerHTML = placeholderContainer.querySelector('[data-placeholder-input]').value;
                placeholderContainer.querySelector('.placeholder-overlay').innerHTML = placeholderContainer.querySelector('[data-placeholder-input]').value;

                PlaceholderElement.updateInputField(placeholderContainer);
            });
        });
    };

    PlaceholderElement.init();
    return PlaceholderElement;
});

