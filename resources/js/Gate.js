export default class Gate {
    constructor(user) {
        this.user = user;
    }

    baseURL() {
        // return 'http://127.0.0.1:8000/api';
        return "http://backend.programsmagic.com/api";
    }

    baseURLForImg() {
        // return 'http://127.0.0.1:8000/public';
        return "http://backend.programsmagic.com/public";
    }

    isAdmin() {
        return this.user.type === "admin";
    }

    isUser() {
        return this.user.type === "user";
    }

    isAuthor() {
        return this.user.type === "author";
    }

    isAuthorOrAdmin() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        } else {
            return false;
        }
    }

    isAuthorOrUser() {
        if (this.user.type === "user" || this.user.type === "author") {
            return true;
        } else {
            return false;
        }
    }
}
