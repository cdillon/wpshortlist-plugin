/**
 * Filter Form
 */

document.addEventListener("DOMContentLoaded", function () {

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
			obj[key] = formData.getAll(key);
		}
		return obj;
	};

	// Set a timer for the Back-button hack.
	function delayTimer() {
		let timeoutID = setTimeout(updateAnOutdatedForm, 50);
	}

	// Ensure the form matches the query string in case user hits
	// the Back button. **EXPERIMENTAL**
	function updateAnOutdatedForm() {
		// Get the query string data.
		const urlParams = new URLSearchParams(location.search);

		// uncheck all inputs
		wpshortlistForm.querySelectorAll('input').forEach((input) => {
			input.checked = false;
		});

		// recheck inputs that match query string
		for (const [key, value] of urlParams.entries()) {
			let multipleValues = value.split('|');
			for (singleValue of multipleValues) {
				wpshortlistForm.querySelector(`#${key}-${singleValue}`)
					.checked = true;
			}
		}
	}

	// Submit the form via Ajax and redirect.
	function formChangeHandler(event) {
		event.preventDefault();

		// Get URL path parts.
		const currentUrl = new URL(window.location.href);

		let allData = {
			'action': wpshortlistSettings.action,
			'nonce': wpshortlistSettings.nonce,
			'pathname': currentUrl.pathname
		}

		let formData = serializeForm(wpshortlistForm);
		if (FormData) {
			allData.formData = formData;
		}

		// Why are we still using jQuery in WordPress in 2023?!
		// Using .post because I cannot get .ajax to work.
		jQuery.post(
			wpshortlistSettings.ajaxUrl,
			allData,
			function (response) {
				if (response.success) {
					console.log(response.data);
					location.assign(response.data);
				}
			},
		)
			.done(function (msg) { console.log(msg) })
			.fail(function (xhr, status, error) {
				// error handling
			});
	}

	// Reset a single filter.
	function resetFilter(event) {
		event.preventDefault();

		// Uncheck this filter's inputs.
		let name = event.target.dataset.filter_name;
		document.getElementsByName(`${name}`).forEach((input) => {
			input.checked = false;
		});

		// Trigger the change event.
		const formChange = new Event('change');
		wpshortlistForm.dispatchEvent(formChange);
	}

	// Let's go!

	// Listen for changes.
	wpshortlistForm.addEventListener('change', formChangeHandler, false);

	wpshortlistForm.querySelectorAll('a.reset-filter-link')
		.forEach((el) => {
			el.addEventListener('click', resetFilter, false);
		});


	// Initiate the Back-button hack.
	// The browser may not update the form upon going back.
	// For example, if current page has 'tags' selected and user clicks back, the form will still show 'tags' as selected.
	// I plan to build a robust breadcrumbs approach that hopefully users will use instead.
	delayTimer();

});
