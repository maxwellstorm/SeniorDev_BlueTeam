class Professor {
	constructor(facultyId, first, last, title, email, room, phone, departmentId, isActive, isFaculty, about, education, highlights, thumb) {
		this.facultyId = facultyId;
		this.first = first;
		this.last = last;
		this.specialTitle = title;
		this.email = email;
		this.room = room.toUpperCase();
		this.phone = phone;
		this.departmentId = departmentId;
		this.isActive = isActive;
		this.isFaculty = isFaculty;
		this.about = about;
		this.education = education;
		this.highlights = highlights;
		this.thumb = thumb;
	}

	getFacultyId() {
		return this.facultyId;
	}

	getFirst() {
		return this.first;
	}

	getLast() {
		return this.last;
	}

	getLastInitial() {
		return this.last.substring(0, 1).toUpperCase();
	}

	getFullName() {
		return this.first + " " + this.last;
	}

	getTitle() {
		return this.specialTitle;
	}

	getEmail() {
		return this.email;
	}

	getRoom() {
		return this.room;
	}

	getPhone() {
		return this.phone;
	}
	
	getDepartmentId() {
		return this.departmentId;
	}

	checkActive() {
		if (this.isActive == "0") {
			return false;
		} else {
			return true;
		}
	}

	checkFaculty() {
		if (this.isFaculty == "0") {
			return false;
		} else {
			return true;
		}
	}

	getAbout() {
		return this.about;
	}

	getEducation() {
		return this.education;
	}

	getHighlights() {
		return this.highlights;
	}

	getThumb() {
		return this.thumb;
	}
}

class Department {
	constructor(deptId, deptName, deptAbbr) {
		this.deptId = deptId;
		this.deptName = deptName;
		this.deptAbbr = deptAbbr;
	}

	getDeptId() {
		return this.deptId;
	}

	getDeptName() {
		return this.deptName;
	}

	getDeptAbbr() {
		return this.debtAbbr;
	}
}