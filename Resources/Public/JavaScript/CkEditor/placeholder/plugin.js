CKEDITOR.plugins.add('placeholder', {
	requires: 'dialog',
	lang: 'de',
	icons: 'placeholder',
	init: function (editor) {
		editor.addCommand('placeholder', new CKEDITOR.dialogCommand('placeholderDialog'));
		editor.ui.addButton('Placeholder', {
			label: editor.lang.placeholder.title,
			command: 'placeholder',
			toolbar: 'links',
			icon: this.path + 'icons/placeholder.svg'
		});
		CKEDITOR.dialog.add('placeholderDialog', this.path + 'dialogs/placeholder.js');
	}
});
