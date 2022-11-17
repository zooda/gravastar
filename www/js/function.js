function copyText(val) {
	// clipboard API 사용
	if (navigator.clipboard !== undefined) {
		navigator.clipboard
		.writeText(val)
		.then(() => {
			alert('복사되었습니다.');
		});
	} else {
		// execCommand 사용
		const textArea = document.createElement('textarea');
		textArea.value = val;
		document.body.appendChild(textArea);
		textArea.select();
		textArea.setSelectionRange(0, 99999);
		try {
			document.execCommand('copy');
		} catch (err) {
			console.error('복사 실패', err);
		}
		textArea.setSelectionRange(0, 0);
		document.body.removeChild(textArea);
		alert('복사되었습니다.');
	}
};

function hexToMartic(hex) {
	var dec = parseInt(hex, 16);
	var fl = parseFloat(dec / (1000000000*1000000000));
	return fl.toFixed(4);
}