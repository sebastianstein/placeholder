CKEDITOR.dialog.add('addPlaceholderDialog', function(editor) {

    /**
     * Return object
     */
    return {
        title: 'Add Placeholder',
        minWidth: 500,
        minHeight: 250,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        type: 'text',
                        id: 'test',
                        className: 'placeholder-rte-input',
                        label: 'Placeholder Identifier (without the "###")'
                    },
                    {
                        type: 'html',
                        html: '<div class="placeholder-autocomplete-results" id="placeholder-autocomplete-results"><ul></ul></div>'
                    }
                ]
            }
        ],

        buttons: [CKEDITOR.dialog.cancelButton],
        onShow: function() {
            let container = document.querySelector('.placeholder-rte-input');
            let input = container.querySelector('input');
            let autocompleteContainer = document.getElementById('placeholder-autocomplete-results');
            let autocompleteList = autocompleteContainer.querySelector('ul');

            input.addEventListener('keyup', function(event) {
                require([
                    'jquery',
                    'TYPO3/CMS/Placeholder/Backend/PlaceholderService'
                ], function($, PlaceholderService) {
                    let value = event.target.value;
                    let autocomplete = PlaceholderService.autocomplete(value);

                    // remove old eventListener
                    if (autocompleteList.hasChildNodes()) {
                        for (let j = 0; j < autocompleteList.children.length; j++) {
                            autocompleteList.children[j].removeEventListener('click', function() {
                                console.log('remove listener from' + [autocompleteList[j]]);
                            });
                        }
                    }

                    autocompleteList.innerHTML = '';
                    let list = '';
                    let terms = autocomplete;
                    for (let i = 0; i < terms.length; i++) {
                        list += '<li>' + terms[i][0] + ' (' + terms[i][1].value + ')</li>';
                    }
                    autocompleteList.innerHTML = list;

                    // add eventListener
                    if (autocompleteList.hasChildNodes()) {
                        for (let a = 0; a < autocompleteList.children.length; a++) {
                            autocompleteList.children[a].addEventListener('click', function(event) {
                                alert('Ausgewählt!');
                            });
                        }
                    }
                });

            });


            //buildImages();
        }
    };
});
