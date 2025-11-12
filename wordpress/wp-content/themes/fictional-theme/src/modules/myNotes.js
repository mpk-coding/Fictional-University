class Note {
	constructor() {
		this.deleteButtons = document.querySelectorAll(".delete-note");
		this.editButtons = document.querySelectorAll(".edit-note");
		this.saveButtons = document.querySelectorAll(".update-note");
		this.createButton = document.querySelector(".submit-note");

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

		// save handlers
		this.saveButtons.forEach((button) => {
			button.addEventListener("click", this.saveNote.bind(this));
		});

		// create handlers
		this.createButton.addEventListener("click", this.createNote.bind(this));
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
		// the edit button
		const editButtonText = event.target
			.closest("[data-id]")
			.querySelector(`.edit-note i`);
		// shared parent
		const parentContainer = event.target.closest("[data-id]");
		// input fields
		const editable = parentContainer.querySelectorAll(
			".note-title-field, .note-body-field"
		);
		// button for saving
		const saveButton = parentContainer.querySelector(".update-note");
		// initial state

		if (parentContainer.getAttribute("state") == "readonly") {
			this.makeNoteEditable(
				editButtonText,
				parentContainer,
				editable,
				saveButton
			);
		} else {
			this.makeNoteReadonly(
				editButtonText,
				parentContainer,
				editable,
				saveButton
			);

			this.getNote(event);
		}
	}

	async saveNote(event) {
		const parentContainer = event.target.closest("[data-id]");
		const noteID = event.target.closest("[data-id]").getAttribute("data-id");
		const title = parentContainer.querySelector(".note-title-field").value;
		const content = parentContainer.querySelector(".note-body-field").value;
		// WordPress REST API endpoint for updating a note
		fetch(`${universityData.root_url}/wp-json/wp/v2/note/${noteID}`, {
			method: "POST", // WordPress accepts POST for updates
			headers: {
				"Content-Type": "application/json",
				"X-WP-Nonce": universityData.nonce, // from wp_localize_script
			},
			body: JSON.stringify({
				title,
				content,
			}),
		})
			.then((res) => {
				if (!res.ok) {
					throw new Error(`Failed to update note: ${res.status}`);
				}
				return res.json();
			})
			.then((data) => {
				this.editNote(event);
			})
			.catch((err) => {
				console.error("❌ Error updating note:", err);
			});
	}

	async createNote(event) {
		const parentContainer = event.target.closest(".create-note");
		const title = parentContainer.querySelector(".new-note-title").value;
		const content = parentContainer.querySelector(".new-note-body").value;

		fetch(`${universityData.root_url}/wp-json/wp/v2/note`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
				"X-WP-Nonce": universityData.nonce,
			},
			body: JSON.stringify({
				title: title,
				content: content,
				status: "publish",
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				console.log("✅ Note created:", data);
				window.location.reload(true);
			})
			.catch((error) => {
				console.error("❌ Error creating note:", error);
			});
	}

	addNote() {}

	makeNoteEditable(editButtonText, parentContainer, editable, saveButton) {
		parentContainer.setAttribute("state", "editable");
		editButtonText.innerHTML = ` Cancel`;
		editButtonText.classList.remove("fa-pencil");
		editButtonText.classList.add("fa-times");

		// make inputs interactive / editable
		editable.forEach((element) => {
			element.removeAttribute("readonly");
			element.classList.add("note-active-field");
		});

		saveButton.classList.add("update-note--visible");
		// revert changes on edit button clicks
	}

	makeNoteReadonly(editButtonText, parentContainer, editable, saveButton) {
		parentContainer.setAttribute("state", "readonly");
		editButtonText.innerHTML = ` Edit`;
		editButtonText.classList.add("fa-pencil");
		editButtonText.classList.remove("fa-times");

		editable.forEach((element) => {
			element.setAttribute("readonly", "true");
			element.classList.remove("note-active-field");
		});

		saveButton.classList.remove("update-note--visible");
	}

	async getNote(event) {
		const noteID = event.target.closest("[data-id]").getAttribute("data-id");
		const titleField = event.target
			.closest("[data-id]")
			.querySelector(".note-title-field");
		const contentField = event.target
			.closest("[data-id]")
			.querySelector(".note-body-field");
		try {
			const res = await fetch(
				`${universityData.root_url}/wp-json/wp/v2/note/${noteID}`
			);

			if (!res.ok) {
				throw new Error(`HTTP ${res.status}`);
			} else {
				const data = await res.json();
				titleField.value = data.title.rendered;
				contentField.value = this.stripHTML(data.content.rendered);
			}
		} catch (err) {
			console.error("Failed:", err);
		}
	}

	stripHTML(html) {
		const temp = document.createElement("div");
		temp.innerHTML = html;
		return temp.textContent || temp.innerText || "";
	}
}

export default Note;
