let passwordLength = 12;
let includeLowercase = true;
let includeUppercase = true;
let includeNumbers = true;
let includeSymbols = true;

let passwordInput = document.getElementById('password');
let generatorBtn = document.getElementById('generate-password');

function passwordGenerator(length, includeLowercase, includeUppercase, includeNumbers, includeSymbols){
    let lowercase = "abcdefghijklmnopqrstuvwxyz";
    let uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    let numbers = "0123456789";
    let symbols = "@#_";

    let allowedPassword = "";
    let password = "";

    allowedPassword += includeLowercase ? lowercase : "";
    allowedPassword += includeUppercase ? uppercase : "";
    allowedPassword += includeNumbers ? numbers : "";
    allowedPassword += includeSymbols ? symbols : "";

    if(length <= 0){
        return `(Password length must be at least 1)`;
    }
    if(allowedPassword.length === 0){
        return `(At least 1 set of characters needs to be selected)`;
    }

    for(let i = 0; i < length; i++){
        let randomIndex = Math.floor(Math.random() * allowedPassword.length);
        password += allowedPassword[randomIndex];
    }

    return password;
}

generatorBtn.addEventListener('click', () => {
    passwordInput.value = passwordGenerator(passwordLength, includeLowercase, includeUppercase, includeNumbers, includeSymbols);
});