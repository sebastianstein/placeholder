CKEDITOR.plugins.add('placeholder', {
    requires: 'dialog',
    lang: 'de',
    init: function(editor) {
        editor.addCommand('addPlaceholder', new CKEDITOR.dialogCommand('addPlaceholderDialog'));
        editor.addCommand('showPlaceholder', {
            exec: function(editor) {
                require([
                    'jquery',
                    'TYPO3/CMS/Placeholder/Backend/PlaceholderService'
                ], function($, PlaceholderService) {
                    let editorContent = editor.getData();
                    let placeholderContent = PlaceholderService.highlightPlaceholder(editorContent);
                    editor.setData(placeholderContent);
                });
            }
        });

        editor.ui.addButton('AddPlaceholder', {
            label: editor.lang.placeholder.button.addPlaceholder,
            command: 'addPlaceholder',
            toolbar: 'links',
            icon: this.path + 'icons/placeholder.svg'
        });
        editor.ui.addButton('Show Placeholder', {
            label: editor.lang.placeholder.button.showPlaceholder,
            command: 'showPlaceholder',
            toolbar: 'links',
            icon: this.path + 'icons/placeholder.svg'
        });
        CKEDITOR.dialog.add('placeholderDialog', this.path + 'dialogs/placeholder.js');


        editor.on('instanceReady', function() {
            let editorContent = editor.getData();
            require([
                'jquery',
                'TYPO3/CMS/Placeholder/Backend/PlaceholderService'
            ], function($, PlaceholderService) {
                let placeholderContent = PlaceholderService.highlightPlaceholder(editorContent);
                console.log(placeholderContent);
                editor.setData(placeholderContent);
            });
        });

        // @todo allow manual addition of placeholders
        // editor.on('key', function() {
        //
        // });
    }
});

