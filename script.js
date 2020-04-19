;(() => {
	function success(response){
		document.getElementById('userContainer').innerHTML = response;
	}
	
	document.getElementById('addAjaxBtn').addEventListener('click', () => {
		fetch('index.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			body: 'action=newAJAX'
		})
		.then(response => response.text())
		.then(response => success(response));
	});
})();
