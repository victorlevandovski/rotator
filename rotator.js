(function() {
	if (typeof _mnd != 'object' || ! _mnd instanceof Array || ! _mnd.length) {
		return;
	}

	var data = _mnd[_mnd.length - 1];
	if (data.length != 3) {
		return;
	}

	var src = 'http://www.example.com/?rid=' + data[0];
	var iframe = '<iframe border="0" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" allowtransparency="true" hspace="0" vspace="0" width="' + data[1] + '" height="' + data[2] + '" src="' + src + '"></iframe>';
	document.write(iframe);
})();
