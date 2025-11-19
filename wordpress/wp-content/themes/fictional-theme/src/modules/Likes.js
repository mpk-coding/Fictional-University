class Likes {
	constructor() {
		this.likeBox = document.querySelector(".like-box");
		this.isLiked = this.likeBox.getAttribute("data-exists");

		console.log("like js");
		this.events();
	}

	events() {
		if (this.likeBox) {
			this.likeBox.addEventListener("click", this.clickHandler.bind(this));
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

	async addLike() {
		const url = `${universityData.root_url}/wp-json/university/v1/manageLike`;
		try {
			const response = await fetch(url, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": universityData.nonce,
				},
			});
			if (!response.ok) {
				throw new Error(`Response status: ${response.status}`);
			}

			const result = await response.json();
			// success
			console.log(result);
		} catch (error) {
			// error
			console.error(error.message);
		}
	}

	async removeLike() {
		const url = `${universityData.root_url}/wp-json/university/v1/manageLike`;
		try {
			const response = await fetch(url, {
				method: "DELETE",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": universityData.nonce,
				},
			});
			if (!response.ok) {
				throw new Error(`Response status: ${response.status}`);
			}

			const result = await response.json();
			// success
			console.log(result);
		} catch (error) {
			// error
			console.error(error.message);
		}
	}
}

export default Likes;
