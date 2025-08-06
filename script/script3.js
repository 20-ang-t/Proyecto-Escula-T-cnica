// Script para el FAQ
document.querySelectorAll(".faq-pregunta").forEach((button) => {
  button.addEventListener("click", () => {
    button.classList.toggle("active");
    const icon = button.querySelector(".faq-icon");
    icon.textContent = button.classList.contains("active") ? "-" : "+";
  });
});
