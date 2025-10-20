class Search {
	// INIT
	constructor() {
		this.overlay = document.querySelector(".search-overlay");
		this.input = document.querySelector("#search-term");
		this.results = document.querySelector("#search-overlay__results");
		this.openButtons = document.querySelectorAll(".js-search-trigger");
		this.closeButton = document.querySelector(
			".fa.fa-window-close.search-overlay__close"
		);
		this.body = document.querySelector("body");
		this.isOverlayOpen = this.overlay.classList.contains(
			"search-overlay--active"
		);
		this.isSpinner = false;
		this.inputValue;

		this.events();
	}

	// EVENTS
	events() {
		// open
		this.openButtons.forEach((button) => {
			button.addEventListener("click", (event) => {
				this.openOverlay();
			});
		});

		//close
		this.closeButton.addEventListener("click", (event) => {
			this.closeOverlay();
		});

		// keyboard support
		document.addEventListener("keyup", (event) => {
			this.keypressDispatcher(event);
		});

		this.input.addEventListener("keyup", () => {
			this.typingLogic(this.getResults.bind(this), 2000);
		});
	}

	// METHODS
	openOverlay() {
		this.overlay.classList.add("search-overlay--active");
		this.body.classList.add("body-no-scroll");
		this.isOverlayOpen = true;

		// focus the input
		this.overlay.addEventListener(
			"transitionend",
			() => {
				this.input.focus();
			},
			{ once: true }
		);
	}

	closeOverlay() {
		this.overlay.classList.remove("search-overlay--active");
		this.body.classList.remove("body-no-scroll");
		this.isOverlayOpen = false;

		// clear the input value
		if (this.input) {
			this.input.value = ""; // clear the field
			this.getResults();
		}
	}

	typingLogic(fn, timeout = 200) {
		// prevent firing logic on cursor movements with arrow keys and so on
		// if new value differs from the old
		if (this.input.value != this.inputValue) {
			// clear any previous timer
			clearTimeout(this.typingTimer);
			// change old value
			this.inputValue = this.input.value;

			// if there is value
			if (this.input.value) {
				// are we loading
				if (!this.isSpinner) {
					this.results.innerHTML = "<div class='spinner-loader'></div>";
					this.isSpinner = true;
				}
				// start a new timer
				this.typingTimer = setTimeout(() => {
					fn();
					this.isSpinner = false;
				}, timeout);
			} else {
				// clear the results
				this.results.innerHTML = "";
			}
		}
	}

	keypressDispatcher(event) {
		// open on 's' press
		const activeEl = document.activeElement;
		const isTyping =
			activeEl.tagName === "INPUT" ||
			activeEl.tagName === "TEXTAREA" ||
			activeEl.isContentEditable;

		if (!this.isOverlayOpen && !isTyping) {
			if (event.keyCode === 83) {
				this.openOverlay();
				this.input.focus();
			}
		} else {
			// close overlay on 'Escape' key press
			if (event.keyCode === 27) {
				this.closeOverlay();
			}
		}
	}

	getResults() {
		this.results.innerHTML = this.input.value;
	}
}

export default Search;
