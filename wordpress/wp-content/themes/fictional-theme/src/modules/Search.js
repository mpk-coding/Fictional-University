class Search {
	// INIT
	constructor() {
		this.overlay = document.querySelector(".search-overlay");
		this.openButtons = document.querySelectorAll(".js-search-trigger");
		this.closeButton = document.querySelector(
			".fa.fa-window-close.search-overlay__close"
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
	}

	// METHODS
	openOverlay() {
		this.overlay.classList.add("search-overlay--active");
	}

	closeOverlay() {
		this.overlay.classList.remove("search-overlay--active");
	}
}

export default Search;
