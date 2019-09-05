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

function dispUserAddress() {

    let postalCode = document.signupForm.user_postal_code.value;
    if (postalCode.length == 7) {
        let request = new XMLHttpRequest();
        let url = "http://os3-385-25562.vs.sakura.ne.jp/search_zipcode/" + encodeURIComponent(postalCode);

        request.onload = function (e) {
            console.log("送信成功");
            let response = JSON.parse(request.responseText);
            console.log(response);
            if (request.readyState === 4) {
                if (request.status === 200) {
                    document.signupForm.user_address.value = response['address'];
                    console.log(request.message);
                } else {
                    console.error(request.status);
                }
            }
        };
        request.open('GET', url, true);
        request.send();

        request.onerror = function(e){
                console.log("request.readyState:" + request.readyState);
                console.log("送信に失敗");
                alert("NG");
        };
    }
}

document.signupForm.birthday.addEventListener("input", slashBirthdayField);

document.signupForm.user_postal_code.addEventListener("input", dispUserAddress);
