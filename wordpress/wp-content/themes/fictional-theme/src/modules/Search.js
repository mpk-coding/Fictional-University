class Search {
	// INIT
	constructor() {
		this.addSearchHTML();
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
		this.previousValue;

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
			this.typingLogic(this.getResults.bind(this), 750);
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

		event.preventDefault();
	}

	closeOverlay() {
		this.overlay.classList.remove("search-overlay--active");
		this.body.classList.remove("body-no-scroll");
		this.isOverlayOpen = false;

		// clear the input value
		if (this.input.value || this.results.innerHTML.length) {
			this.input.value = ""; // clear the field
			this.results.innerHTML = "";
		}
	}

	typingLogic(fn, timeout = 200) {
		// prevent firing logic on cursor movements with arrow keys and so on
		// if new value differs from the old
		if (this.input.value != this.previousValue) {
			// clear any previous timer
			clearTimeout(this.typingTimer);
			// change old value
			this.previousValue = this.input.value;

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
			}
		}
	}

	keypressDispatcher(event) {
		// open on 's' press
		// prevent opening when other editable fields are focused
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

	async getResults() {
		if (!this.input.value) {
			return;
		}
		const resultsURL =
			universityData.root_url +
			`/wp-json/university/v1/search?term=${this.input.value}`;

		try {
			const resultsResponse = await fetch(resultsURL);

			if (!resultsResponse.ok) {
				throw new Error(`HTTP error! status: ${resultsResponse.status}`);
			}

			const resultsJson = await resultsResponse.json();
			this.renderSearch(resultsJson);
		} catch (error) {
			console.error(error.message);
		}
	}

	trimWords(str, numWords, suffix = "...") {
		if (!str) return "";
		// strip HTML tags and comments
		const clean = str.replace(/<!--[\s\S]*?-->/g, "").replace(/<[^>]*>/g, "");
		const words = clean.split(/\s+/);
		if (words.length <= numWords) return clean;
		return words.slice(0, numWords).join(" ") + suffix;
	}

	renderSearch(array) {
		let render;
		//
		console.log(array);
		const hasResults = Object.values(array).some((array) => array.length);

		if (hasResults) {
			render = `
			<div class='row'>
				<div class='one-third'>
					<h2 class='search-overlay__section-title'>General Information</h2>
					<ul class='link-list min-list'>
					${
						array.generalInfo.length
							? array.generalInfo
									.map((post) => {
										return `<li>
								<a href='${post.permalink}'>${post.title}</a>
								${post.type == "post" ? `<span>by ${post.author}</span>` : ""}
							</li>`;
									})
									.join("")
							: "<li>No results</li>"
					}
					</ul>
				</div>
				<div class='one-third'>
					<h2 class='search-overlay__section-title'>Programs</h2>
					<ul class='link-list min-list'>
					${
						array.programs.length
							? array.programs
									.map((post) => {
										return `<li><a href='${post.permalink}'>${post.title}</a></li>`;
									})
									.join("")
							: `<li>No programs found. <a href='${universityData.root_url}/programs'>See all programs</a></li>`
					}
					</ul>
					<h2 class='search-overlay__section-title'>Professors</h2>
					<ul class='professor-cards'>
					${
						array.professors.length
							? array.professors
									.map((post) => {
										return `<li class='professor-card__list-item'>
													<a class='professor-card' href='${post.permalink}'>
														<img class='professor-card__image' src='${post.thumbnail}'>
														<span class='professor-card__name'>${post.title}</span>
													</a>
												</li>`;
									})
									.join("")
							: "<li>No professors found.</li>"
					}
					</ul>
				</div>
				<div class='one-third'>
					<h2 class='search-overlay__section-title'>Campuses</h2>
					<ul class='link-list min-list'>
					${
						array.campuses.length
							? array.campuses
									.map((post) => {
										return `<li><a href='${post.permalink}'>${post.title}</a></li>`;
									})
									.join("")
							: `<li>No campuses match that search. <a href='${universityData.root_url}/campuses'>See all campuses</a></li>`
					}
					</ul>
					<h2 class='search-overlay__section-title'>Events</h2>
					${
						array.events.length
							? array.events
									.map((post) => {
										console.log(`post content_short:${post.content_short}`);
										console.log(`post excerpt:${post.excerpt}`);

										return `
										<div class="event-summary">
											<a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
												<span class="event-summary__month">
													${post.month}
												</span>
												<span class="event-summary__day">
													${post.day}
												</span>
											</a>
											<div class="event-summary__content">
												<h5 class="event-summary__title headline headline--tiny"><a href="${
													post.permalink
												}">${post.title}</a></h5>
												<p>${post.excerpt ? `${post.excerpt}` : `${post.contentShort}`}
													<a href='${post.permalink}'>Learn More</a>
													</p>
											</div>
										</div>`;
									})
									.join("")
							: "<li>No results</li>"
					}
				</div>`;
		} else {
			render = "No results for that phrase.";
		}

		this.results.innerHTML = render;
		this.isSpinner = false;
	}

	addSearchHTML() {
		document.body.insertAdjacentHTML(
			"beforeend",
			`<div class="search-overlay">
				<div class="search-overlay__top">
					<div class="container">
					<i class="fa fa-search search-overlay__icon" aria-hidden='true'></i>
					<input type="text" id='search-term' class="search-term" placeholder='What are you looking for?'>
					<i id='searchClose' class="fa fa-window-close search-overlay__close" aria-hidden='true'></i>
					</div>
				</div>
				<div class="container">
					<div id="search-overlay__results"></div>
				</div>
				</div>`
		);
	}
}

export default Search;
