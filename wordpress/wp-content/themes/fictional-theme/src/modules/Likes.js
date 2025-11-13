class Likes {
	constructor() {
		this.likeBox = document.querySelector(".like-box");
		this.isLiked = this.likeBox.getAttribute("data-exists");

		this.events();
	}

	events() {
		if (this.likeBox) {
			this.likeBox.addEventListener("click", (event) => {});
		}
	}

	// methods
	clickHandler() {
		if (this.isLiked == "yes") {
			this.removeLike();
		} else {
			this.addLike();
		}
	}

	addLike() {}

	removeLike() {}
}

export default Likes;
