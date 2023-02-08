/**
 * Filter Form
 */

document.addEventListener("DOMContentLoaded", function () {

	// Bail if no form is present.
	let wpshortlistForm = document.getElementById("wpshortlist-form");
	if (!wpshortlistForm) {
		return false;
	}

	// ---------
	// Utilities
	// ---------

	// Serialize the form data.
	function serializeForm(form) {
		let obj = {};
		let formData = new FormData(form);
		for (const key of formData.keys()) {
			obj[key] = formData.getAll(key);
		}
		return obj;
	};

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

	// Set a timer for the Back-button hack.
	function delayTimer() {
		setTimeout(updateAnOutdatedForm, 50);
	}

	// --------------
	// Event Handlers
	// --------------

	// Action router.
	function formClickHandler(event) {
		showOverlay();

		const action = event.target.dataset.action;
		switch (action) {

			// form reset
			case 'reset-form':
				resetForm(event);
				break;

			// filter reset
			case 'reset':
				toggleAllInputs(event, false);
				break;

			// check all
			case 'check-all':
				toggleAllInputs(event, true);
				break;

			default:
				hideOverlay();
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
		if (formData) {
			allData.formData = formData;
		}

		// Using .post because I cannot get .ajax to work.
		// @todo Convert to pure XHR.
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
			// .done(function (msg) { console.log(msg) })
			.fail(function (xhr, status, error) {
				// error handling
			});
	}

	// ------------
	// Form Actions
	// ------------

	// Reset all filters.
	function resetForm(event) {
		event.preventDefault();

		// Simply return to base URL.
		const currentUrl = new URL(window.location.href);
		location.assign(`${currentUrl.origin}${currentUrl.pathname}`);
	}

	// Toggle action links.
	function updateActions() {
		wpshortlistForm.querySelectorAll('.form-actions')
			.forEach((el) => {
				toggleResetAll(el);
			});

		wpshortlistForm.querySelectorAll('.wpshortlist-filter')
			.forEach((el) => {
				toggleReset(el);
				toggleCheckAll(el);
			});
	}

	// Get the filter's name.
	function getFilterName(event) {
		return event.target.closest('.wpshortlist-filter').dataset.filter_name;
	}

	// Check all inputs in a single filter.
	function toggleAllInputs(event, state = false) {
		event.preventDefault();

		let name = getFilterName(event);
		document.getElementsByName(`${name}`).forEach((input) => {
			input.checked = state;
		});

		triggerChange();
	}

	// Toggle the reset link in a single filter.
	function toggleReset(el) {
		const name = el.dataset.filter_name;

		if (isAnyChecked(name)) {
			showAction(el.querySelector('.action-reset .action-enabled'));
		} else {
			showAction(el.querySelector('.action-reset .action-disabled'));
		}
	}

	// Toggle the form's reset link.
	function toggleResetAll(el) {
		if (isAnyCheckedOnForm()) {
			showAction(el.querySelector('.action-reset .action-enabled'));
		} else {
			showAction(el.querySelector('.action-reset .action-disabled'));
		}
	}

	// Toggle the check-all link in a single filter.
	function toggleCheckAll(el) {
		const name = el.dataset.filter_name;
		const inputType = el.dataset.filter_type;

		if ('checkbox' === inputType) {
			if (isAllChecked(name)) {
				showAction(el.querySelector('.action-check-all .action-disabled'));
			} else {
				showAction(el.querySelector('.action-check-all .action-enabled'));
			}
		}
	}

	// Show (un-hide) an element.
	function showAction(el) {
		el.style.display = 'inline-block';
	}

	// Determine if all checkboxes are checked for a single filter.
	function isAllChecked(name) {
		let i = wpshortlistForm.querySelectorAll(`input[name=${name}]`).length;
		let n = wpshortlistForm.querySelectorAll(`input[name=${name}]:checked`).length;
		return n === i;
	}

	// Return the number of checked inputs for a single filter.
	function isAnyChecked(name) {
		return wpshortlistForm.querySelectorAll(`input[name=${name}]:checked`).length;
	}

	// Return the number of checked inputs on the entire form.
	function isAnyCheckedOnForm() {
		return wpshortlistForm.querySelectorAll('input:checked').length;
	}

	// Trigger the change event.
	function triggerChange() {
		const formChange = new Event('change');
		wpshortlistForm.dispatchEvent(formChange);
	}

	// Insert an overlay.
	function showOverlay() {
		const overlay = document.createElement('div');
		overlay.id = 'wpshortlist-overlay';
		document.body.append(overlay);
	}

	// Remove the overlay.
	function hideOverlay() {
		document.getElementById('wpshortlist-overlay').remove();
	}

	// Initialize listeners.
	function init() {
		wpshortlistForm.addEventListener('click', formClickHandler, false);
		wpshortlistForm.addEventListener('change', formChangeHandler, false);
	}

	// ---------
	// Let's go!
	// ---------

	init();

	delayTimer();

	updateActions();

});
