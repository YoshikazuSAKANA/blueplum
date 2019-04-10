function slashBirthdayField () {

    let birthday = document.signupForm.birthday.value;

    if (birthday.length == 5 && birthday.slice(-1) !== "/") {
        replaceBirthday = birthday.slice(0,4) + "/" + birthday.slice(4);
        document.signupForm.birthday.value = replaceBirthday;
    } else if(birthday.length == 8 && birthday.slice(-1) !== "/") {
        replaceBirthday = birthday.slice(0,7) + "/" + birthday.slice(8);
        document.signupForm.birthday.value = replaceBirthday;
    }
}

document.signupForm.birthday.addEventListener("input", slashBirthdayField);
