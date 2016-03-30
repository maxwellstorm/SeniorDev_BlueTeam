class Professor {
	constructor(first, last, email, room, thumb) {
		this.first = first;
		this.last = last;
		this.email = email;
		this.room = room;
		this.thumb = thumb;
	}

	getFullName() {
		return this.first + " " + this.last;
	}

	getEmail() {
		return this.email;
	}

	getRoom() {
		return this.room;
	}

	getThumb() {
		return this.thumb;
	}
}