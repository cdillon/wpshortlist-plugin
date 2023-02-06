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

	/*
	 * Ensure the form matches the query string. **EXPERIMENTAL**
	 *
	 * The browser may not update the form if user hits the Back button.
	 *
	 * For example, if current page has 'tags' selected and user clicks back,
	 * the form will still show 'tags' as selected.
	 *
	 * I plan to build a robust breadcrumbs approach that hopefully will be
	 * used instead, making this a corner case solution.
	 */
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

		// Uncheck all inputs for this filter.
		let name = event.target.dataset.filter_name;
		document.getElementsByName(`${name}`).forEach((input) => {
			input.checked = false;
		});

		// Trigger the change event.
		const formChange = new Event('change');
		wpshortlistForm.dispatchEvent(formChange);
	}

	// Reset all filters.
	function resetForm(event) {
		event.preventDefault();

		// Simply return to base URL.
		const currentUrl = new URL(window.location.href);
		location.assign(`${currentUrl.origin}${currentUrl.pathname}`);
	}

	// Check all checkboxes in a filter.
	function checkAll(event) {
		event.preventDefault();

		// Check all inputs for this filter.
		let name = event.target.dataset.filter_name;
		document.getElementsByName(`${name}`).forEach((input) => {
			input.checked = true;
		});

		// Trigger the change event.
		const formChange = new Event('change');
		wpshortlistForm.dispatchEvent(formChange);
	}

	// Toggle "check all" links.
	function updateCheckAll() {
		wpshortlistForm.querySelectorAll('a.wpshortlist-filter-check-all-link')
			.forEach((el) => {
				let name = el.dataset.filter_name;
				let action = el.closest('.wpshortlist-filter-dependent-action');
				if (isAllChecked(name)) {
					action.style.display = 'none';
				} else {
					action.style.display = 'block';
				}
			});
	}

	// Determine if all checkboxes are checked for a single filter.
	function isAllChecked(name) {
		let numInputs = wpshortlistForm.querySelectorAll(`input[name=${name}]`).length;
		let numChecked = wpshortlistForm.querySelectorAll(`input[name=${name}]:checked`).length;
		return numInputs === numChecked;
	}

	// Initialize.
	function init() {
		// Listen for changes on the form.
		wpshortlistForm.addEventListener('change', formChangeHandler, false);

		// Listen for clicks to reset a filter.
		wpshortlistForm.querySelectorAll('a.wpshortlist-reset-filter-link')
			.forEach((el) => {
				el.addEventListener('click', resetFilter, false);
			});

		// Listen for clicks to select all checkboxes.
		wpshortlistForm.querySelectorAll('a.wpshortlist-filter-check-all-link')
			.forEach((el) => {
				el.addEventListener('click', checkAll, false);
			});

		// Listen for clicks to reset all filters.
		wpshortlistForm.querySelectorAll('a.wpshortlist-reset-form-link')
			.forEach((el) => {
				el.addEventListener('click', resetForm, false);
			});
	}

	// Let's go!
	init();

	// Initiate the Back-button hack.
	delayTimer();

	updateCheckAll();
});
