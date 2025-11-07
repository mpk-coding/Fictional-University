class Note {
	constructor() {
		this.deleteButtons = document.querySelectorAll(".delete-note");
		this.editButtons = document.querySelectorAll(".edit-note");

		this.events();
	}

	events() {
		// deletion handlers
		this.deleteButtons.forEach((button) => {
			button.addEventListener("click", this.deleteNote.bind(this));
		});

		// edit handlers
		this.editButtons.forEach((button) => {
			button.addEventListener("click", this.editNote.bind(this));
		});
	}

	// Methods
	async deleteNote(event) {
		const noteID = event.target.closest("[data-id]").getAttribute("data-id");
		try {
			const res = await fetch(
				`${universityData.root_url}/wp-json/wp/v2/note/${noteID}`,
				{
					method: "DELETE",
					credentials: "include",
					headers: {
						"X-WP-Nonce": universityData.nonce,
					},
				}
			);

			if (!res.ok) {
				throw new Error(`HTTP ${res.status}`);
			} else {
				const data = await res.json();
				location.reload(true);
			}
		} catch (err) {
			console.error("Failed:", err);
		}
	}

	editNote(event) {
		const noteID = event.target.closest("[data-id]").getAttribute("data-id");
		const parentContainer = event.target.closest("[data-id");
		const editable = parentContainer.querySelectorAll(
			".note-title-field, .note-body-field"
		);
		const saveButton = parentContainer.querySelector(".update-note");

		editable.forEach((element) => {
			element.removeAttribute("readonly");
			element.classList.add("note-active-field");
		});
		saveButton.classList.add("update-note--visible");
	}
}

export default Note;
