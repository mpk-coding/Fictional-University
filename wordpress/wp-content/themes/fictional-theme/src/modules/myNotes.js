class Note {
	constructor() {
		console.log("myNotes imported");
		this.deleteButtons = document.querySelectorAll(".delete-note");

		this.events();
	}

	events() {
		this.deleteButtons.forEach((button) => {
			const id = button.getAttribute("noteID");
			button.addEventListener("click", this.deleteNote);
		});
	}

	// Methods
	async deleteNote() {
		console.log("deleteNote call");
		try {
			const res = await fetch(
				`${universityData.root_url}/wp-json/wp/v2/note/157`,
				{
					method: "DELETE",
					credentials: "include",
					headers: {
						"X-WP-Nonce": universityData.nonce,
					},
				}
			);

			if (!res.ok) throw new Error(`HTTP ${res.status}`);
			const data = await res.json();
			console.log("Deleted:", data);
		} catch (err) {
			console.error("Failed:", err);
		}
	}
}

export default Note;
