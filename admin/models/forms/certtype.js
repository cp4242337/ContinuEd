window.addEvent('domready', function() {
	document.formvalidator.setHandler('certtype',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});

