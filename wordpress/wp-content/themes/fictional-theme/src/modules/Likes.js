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
	clickHandler(event) {
		const currentLikeBox = event.target.closest(".like-box");

		if (this.isLiked == "yes") {
			this.removeLike(currentLikeBox);
		} else {
			this.addLike(currentLikeBox);
		}
	}

	async addLike(currentLikeBox) {
		const url = `${universityData.root_url}/wp-json/university/v1/manageLike`;
		const professorID = currentLikeBox.getAttribute("data-id");
		try {
			const response = await fetch(url, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"X-WP-Nonce": universityData.nonce,
				},
				body: JSON.stringify({
					professor_id: `${professorID}`,
				}),
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
				body: JSON.stringify({
					professor_id: "789",
				}),
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
