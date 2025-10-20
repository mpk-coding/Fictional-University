class Search {
	// INIT
	constructor() {
		this.overlay = document.querySelector(".search-overlay");
		this.input = document.querySelector("#search-term");
		this.openButtons = document.querySelectorAll(".js-search-trigger");
		this.closeButton = document.querySelector(
			".fa.fa-window-close.search-overlay__close"
		);
		this.body = document.querySelector("body");
		this.isOverlayOpen = this.overlay.classList.contains(
			"search-overlay--active"
		);

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
		document.addEventListener("keyup", (event) =>
			this.keypressDispatcher(event)
		);

		this.input.addEventListener("input", (event) => {
			this.typingLogic(event, 2000);
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
		}
	}

	typingLogic(event, timeout = 200) {
		// clear any previous timer
		clearTimeout(this.typingTimer);

		// start a new timer
		this.typingTimer = setTimeout(() => {
			console.log(this.input.value);
		}, timeout);
	}

	keypressDispatcher(event) {
		// open on 's' press
		if (!this.isOverlayOpen) {
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
}

export default Search;
