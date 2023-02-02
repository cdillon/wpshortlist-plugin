document.addEventListener("DOMContentLoaded", function() {

	// Bail if no form is present.
	let wpshortlistForm = document.getElementById("wpshortlist-form");
	if (!wpshortlistForm) {
		return false;
	}

	// Serialize the form data.
	function serializeForm(form) {
		let obj = {};
		let formData = new FormData(form);
		for (const key of formData.keys()) {
			obj[key] = formData.get(key);
		}
		return obj;
	};

	// Set a timer for the Back-button hack.
	function delayTimer() {
		let timeoutID = setTimeout(updateAnOutdatedForm, 50);
	}

	// Ensure the form matches the query string in case use hits the Back button. **EXPERIMENTAL**
	function updateAnOutdatedForm() {
		// Get the query string data.
		const urlParams = new URLSearchParams(location.search);

		// uncheck all inputs
		wpshortlistForm.querySelectorAll('input').forEach( (input) => {
			input.checked = false;
		});

		// recheck inputs that match query string
		for (const [key, value] of urlParams.entries()) {
			wpshortlistForm.querySelector(`#${key}-${value}`).checked = true;
		}
	}

	// Submit the form via Ajax and redirect.
	function formChangeHandler(event) {
		event.preventDefault();
		
		// Get URL path parts.
		const currentUrl = new URL(window.location.href);
		const urlPath = currentUrl.pathname.split('/')
			.filter( function (el) { return el != '' });
	
		let formData = serializeForm(wpshortlistForm);

		let allData = {
			'action': wpshortlistSettings.action,
			'nonce': wpshortlistSettings.nonce,
			'formData': formData,
			'taxonomy': urlPath[0],
			'term': urlPath[1],
		}

		// Why are we still using jQuery in WordPress in 2023?!
		// Using .post because I cannot get .ajax to work.
		jQuery.post(
			wpshortlistSettings.ajaxUrl,
			allData,
			function(response) {
				if (response.success) {
					console.log(response.data);
					location.assign(response.data);
				}
			},
		)
		.done(function(msg) { console.log(msg) })
		.fail(function(xhr, status, error) {
			// error handling
		});
	}	

	// Let's go!

	// Listen for changes.
	wpshortlistForm.addEventListener( 'change', formChangeHandler, false );
	
	// Initiate the Back-button hack.
	// The browser may not update the form upon going back.
	// For example, if current page has 'tags' selected and user clicks back, the form will still show 'tags' as selected.
	// I plan to build a robust breadcrumbs approach that hopefully users will use instead. 
	delayTimer();

});
