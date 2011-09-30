window.addEvent('domready', function() {
	document.formvalidator.setHandler('groupcert',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});

