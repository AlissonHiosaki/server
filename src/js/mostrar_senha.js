function mostrarSenha() {
    var password = document.getElementById('senha');
    if (password.type === "password") {
    password.type = "text";
      } else {
     password.type = "password";
   }
 }