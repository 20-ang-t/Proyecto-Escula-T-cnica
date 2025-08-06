const selectBtn = document.getElementById("select-btn");
const text = document.getElementById("text");
const option = document.getElementsByClassName("option");

selectBtn.addEventListener("click", function () {
  selectBtn.classList.toggle("active");
});

for (options of option) {
  options.onclick = function () {
    text.innerHTML = this.textContent;
    selectBtn.classList.remove("active");
  };
}

// Obtener elementos del DOM
const modificarBtn = document.querySelector(".modificar-btn");
const modal = document.querySelector(".modal");
const closeBtn = document.querySelector(".close-btn");

// Función para abrir el modal
function openModal() {
  modal.style.display = "block";
  document.body.style.overflow = "hidden"; // Evita el scroll del fondo
}

// Función para cerrar el modal
function closeModal() {
  modal.style.display = "none";
  document.body.style.overflow = "auto"; // Restaura el scroll
}

// Event listeners
modificarBtn.addEventListener("click", openModal);
closeBtn.addEventListener("click", closeModal);

// Cerrar al hacer clic fuera del modal
window.addEventListener("click", (e) => {
  if (e.target === modal) {
    closeModal();
  }
});
