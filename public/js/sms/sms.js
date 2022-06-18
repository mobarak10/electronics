const message = document.getElementById("message");
const totalCharacters = document.getElementById("total_characters");
let totalMessage = document.getElementById("total_messages");

message.addEventListener("input", function() {
    const count = message.value.length + 21;
    totalCharacters.value = count;

    let english = /^[~!@#$%^&*(){},.:/-_=+A-Za-z0-9 ]*$/;
    let messageLength;
    if (english.test(message)) {
        if (count <= 160) {
            messageLength = 1;
        } else if (count <= 306) {
            messageLength = 2;
        } else if (count <= 459) {
            messageLength = 3;
        } else if (count <= 612) {
            messageLength = 4;
        } else if (count <= 765) {
            messageLength = 5;
        } else if (count <= 918) {
            messageLength = 6;
        } else if (count <= 1071) {
            messageLength = 7;
        } else if (count <= 1080) {
            messageLength = 8;
        } else {
            messageLength = "MMS";
        }
    } else {
        if (count <= 63) {
            messageLength = 1;
        } else if (count <= 126) {
            messageLength = 2;
        } else if (count <= 189) {
            messageLength = 3;
        } else if (count <= 252) {
            messageLength = 4;
        } else if (count <= 315) {
            messageLength = 5;
        } else if (count <= 378) {
            messageLength = 6;
        } else if (count <= 441) {
            messageLength = 7;
        } else if (count <= 504) {
            messageLength = 8;
        } else {
            messageLength = "MMS";
        }
    }

    totalMessage.value = messageLength;
});
