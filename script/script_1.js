document.addEventListener("DOMContentLoaded", function () {
  // Selección de elementos
  const wrapper = document.querySelector(".wrapper");
  const loginLink = document.querySelector(".login-link");
  const registerLink = document.querySelector(".register-link");
  const btnPopup = document.querySelector(".btnLogin-popup");
  const iconClose = document.querySelector(".icon-close");

  // Selección de formularios
  const loginForm = document.querySelector(".form-box.login");
  const registerForm = document.querySelector(".form-box.register");
  const forgotForm = document.querySelector(".form-box.forgot");

  // Mostrar formulario de Login al hacer clic en "Acceso"
  btnPopup.addEventListener("click", function (e) {
    e.preventDefault();
    wrapper.classList.add("active-popup");
    showForm(loginForm);
  });

  // Cerrar todos los formularios
  iconClose.addEventListener("click", function () {
    wrapper.classList.remove("active-popup", "active");
    showForm(loginForm);
  });

  // Mostrar formulario de Registro
  registerLink.addEventListener("click", function (e) {
    e.preventDefault();
    wrapper.classList.add("active");
    showForm(registerForm);
  });

  // Volver a Login desde Registro
  document.querySelectorAll(".login-link").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      wrapper.classList.remove("active");
      showForm(loginForm);
    });
  });

  // Mostrar formulario de Recuperación de Contraseña
  document.querySelectorAll(".forgot-link").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      wrapper.classList.add("active");
      showForm(forgotForm);
    });
  });

  // Función auxiliar para mostrar el formulario correcto
  function showForm(formToShow) {
    [loginForm, registerForm, forgotForm].forEach((form) => {
      if (form === formToShow) {
        form.style.transform = "translateX(0)";
      } else {
        form.style.transform =
          formToShow === loginForm ? "translateX(400px)" : "translateX(-400px)";
      }
    });
  }
});

$(document).ready(function () {
  $("#registerForm").on("submit", function (e) {
    e.preventDefault(); // Previene que el formulario recargue la página

    var formData = $(this).serialize(); // Serializa los datos del formulario

    // Realiza la solicitud AJAX
    $.ajax({
      url: "Login.php", // El archivo PHP que procesará el formulario
      method: "POST",
      data: formData, // Los datos del formulario
      success: function (response) {
        // Muestra el mensaje que devuelve el servidor
        $("#registerMessage").html(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        $("#registerMessage").html(
          "<p style='color:red;'>Error al procesar la solicitud.</p>"
        );
      },
    });
  });
});
