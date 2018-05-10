Class.Define('Front', {
	Extend: Module,
	Static: {
		PIGLATIN_FORM_TEXT_ID: 'pig-latin-translator',
		PIGLATIN_FORM_ORIG_TEXT_ID: 'plt-original-text',
		PIGLATIN_FORM_TRANSL_TEXT_ID: 'plt-translated-text'
	},
	pigLatinForm: {
		form: null,
		origText: null,
		translText: null,
		semaphore: false,
		action: ''
	},
	Constructor: function () {
		this.parent();
		this.pigLatinFormInit();
	},
	pigLatinFormInit: function () {
		var form = document.getElementById(this.self.PIGLATIN_FORM_TEXT_ID);
		if (!form) return false;
		
		var dataAttr = form.getAttribute('data-js');
		var dataAttrRes = Helpers.getJsonAttr(dataAttr);
		this.pigLatinForm.action = (dataAttrRes.success) ? dataAttrRes.data.jsSubmit.replace(/\&amp;/gi, '&') : form.getAttribute('action');
		
		this.pigLatinForm.origText = document.getElementById(this.self.PIGLATIN_FORM_ORIG_TEXT_ID);
		this.pigLatinForm.translText = document.getElementById(this.self.PIGLATIN_FORM_TRANSL_TEXT_ID);
		
		this.pigLatinForm.origText.onkeyup = this.pigLatinformKeyUpHandler.bind(this);
		this.pigLatinForm.origText.onblur = this.pigLatinformKeyUpHandler.bind(this);
	},
	pigLatinformKeyUpHandler: function (e) {
		e = e || window.event;
		var value = String(this.pigLatinForm.origText.value);
		var lastChar = value.substr(value.length - 1);
		this.pigLatinFormSubmit(value);
		if (e.preventDefault) e.preventDefault();
		return false;
	},
	pigLatinFormSubmit: function (originalText) {
		if (this.pigLatinForm.semaphore) return false;
		this.pigLatinForm.semaphore = true;
		Ajax.load({
			url: this.pigLatinForm.action,
			method: 'post',
			data: {
				'original-text': originalText
			},
			success: function (data, statusCode, xhr) {
				if (data.success) {
					this.pigLatinForm.translText.value = data.translatedText;
				} else {
					this.pigLatinForm.translText.value = data.message;
				}
				this.pigLatinForm.semaphore = false;
			}.bind(this),
			type: 'json'
		});
		return true;
	}
});

// run all declared javascripts after <body>, after all elements are declared
window.front = new Front();
