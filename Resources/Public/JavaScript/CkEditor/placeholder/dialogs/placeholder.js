CKEDITOR.dialog.add('placeholderDialog', function(editor) {

	/**
	 * On element click
	 */
	function clickEvent(type, event) {
		var target = event.target;
		var fileName = target.getAttribute('data-file');
		var attributes = {
			attributes: {
				src: fileName,
				'data-cke-saved-src': fileName,
				width: target.width,
				height: target.height,
				class: ''
			}
		};
		if (type === 'disturber') {
			attributes.attributes.height = 128;
			attributes.attributes.width = 128;
			attributes.attributes.class = 'u-table-th-disturber';
		}

		if (type === 'signet') {
			delete attributes.attributes.width;
			delete attributes.attributes.height;
			attributes.attributes.class = 'signet';
		}
		var img = editor.document.createElement('img', attributes);
		editor.insertElement(img);
		CKEDITOR.dialog.getCurrent().hide();
	}

	/**
	 * @param {string} pathAndFilename
	 * @param {string} type
	 * @returns {Array}
	 */
	function createIcon(pathAndFilename, type) {
		var img = document.createElement('img');
		img.setAttribute('src', pathAndFilename);
		img.style.width = '32px';
		img.style.padding = '8px';
		img.setAttribute('class', 'cke_hand');
		img.setAttribute('data-file', pathAndFilename);
		document.getElementById('in2iconinsert_imagecontainer').appendChild(img);
		img.addEventListener('click', clickEvent.bind(this, type));
	}

	/**
	 * @param {Array} list
	 * @param {string} type
	 */
	function buildImagesFromList(list, type) {

		addHeadline(type);

		for (var i = 0; i < list.length; i++) {
			createIcon(list[i], type);
		}

		var hr = document.createElement('hr');
		hr.setAttribute('class', 'in2iconinsert_gap');
		document.getElementById('in2iconinsert_imagecontainer').appendChild(hr);
	}

	/**
	 * There is currently no Translations for the CK Editor Plugins. So the headlines are hardcoded here.
	 * If new types are added in the future, we should possibly create language files here
	 *
	 * @param {string} type
	 */
	function addHeadline(type) {
		var headline = document.createElement('h2');
		var label = '';

		switch (type) {
			case 'icon':
				label = 'Icons:';
				break;
			case 'disturber':
				label = 'Störer:';
				break;
			case 'signet':
				label = 'Signets:';
				break;
		}

		headline.innerHTML = label;
		document.getElementById('in2iconinsert_imagecontainer').appendChild(headline);
	}

	/**
	 * @param {string} path
	 * @param {string} type
	 */
	function ajax(path, type) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState === 4 && this.status === 200) {
				buildImagesFromList(JSON.parse(this.responseText), type);
			}
		};
		xhttp.open('POST', path, false);
		xhttp.send();
	}

	/**
	 * Build images
	 */
	function buildImages() {
		document.getElementById('in2iconinsert_imagecontainer').innerHTML = '';
		var paths = editor.config.iconinsert.path;
		for (var type in paths) {
			if (paths.hasOwnProperty(type)) {
				ajax(paths[type], type);
			}
		}
	}

	/**
	 * Return object
	 */
	return {
		title: 'Grafik auswählen',
		minWidth: 200,
		minHeight: 200,

		contents: [
			{
				id: 'tab-basic',
				label: 'Basic Settings',
				elements: [
					{
						type: 'html',
						html: '<div id="in2iconinsert_imagecontainer">Images loading...</div>'
					}
				]
			}
		],

		buttons: [CKEDITOR.dialog.cancelButton],
		onShow: function() {
			buildImages();
		}
	};
});
