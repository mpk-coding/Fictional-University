class Note {
	constructor() {
		console.log("myNotes imported");
		this.deleteButtons = document.querySelectorAll(".delete-note");

		this.events();
	}

	events() {
		this.deleteButtons.forEach((button) => {
			button.addEventListener("click", this.deleteNote);
		});
	}

	// Methods
	deleteNote() {
		console.log("deleteNote call");
	}
}

export default Note;
