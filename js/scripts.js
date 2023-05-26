function controllaCredenziali() {

    var email = document.getElementById("email").value;
    // controllo se l'email è vuota
    if (email == "") {
        alert("Il campo E-Mail non può essere vuoto!");
        return false;
    }
    // variabile che serve per controllare se l'email è scritta nella forma corretta
    var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    // controllo se l'email è scritta nel modo corretto
    if (!email.match(emailRegex)) {
        alert("Inserisci un indirizzo E-Mail valido!");
        return false;
    }

    var password = document.getElementById("password").value;
    // controllo se la password è vuota
    if (password == "") {
        alert("Il campo Password non può essere vuoto!");
        return false;
    }
    // variabile che stabilisce alcuni parametri per la password
    var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
    // controllo se la password rispetta questi parametri
    if (!password.match(passwordRegex)) {
        alert("La Password deve essere lunga minimo 8 carattert; deve contenere una lettera minuscola, una lettera maiuscola ed un numero!")
        return false;
    }
    // controllo se la riconferma della password è vuota
    if (passwordConfirm == "") {
        alert("Password confirmation field must be filled out");
        return false;
    }
    // controllo se le password combaciano
    if (password != passwordConfirm) {
        alert("Passwords do not match");
        return false;
    }
    // se è tutto vero, si pass
    return true


    // invio una request per controllare le credenziali dell'utente
    // se le credenziali sono corrette, ok
    // se le credenziali non sono corrette, allora si submitta un false per prevenire l'invio della request
}