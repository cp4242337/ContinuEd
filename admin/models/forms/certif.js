window.addEvent('domready', function() {
	document.formvalidator.setHandler('certif',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});

