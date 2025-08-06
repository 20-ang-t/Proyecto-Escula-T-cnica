// Función para obtener parámetros de la URL
function getParametro(nombre) {
  const params = new URLSearchParams(window.location.search);
  return params.get(nombre);
}

// Seleccionar el programa si está en la URL
const programaSeleccionado = getParametro("programa");
if (programaSeleccionado) {
  const select = document.querySelector('select[name="programa"]');
  const opciones = Array.from(select.options);
  let existe = opciones.some((option) => option.value === programaSeleccionado);

  // Si el programa ya existe en el select, lo selecciona directamente
  if (existe) {
    select.value = programaSeleccionado;
  } else {
    // Si no existe, lo agrega y selecciona
    const nuevaOpcion = new Option(programaSeleccionado, programaSeleccionado);
    nuevaOpcion.selected = true;
    select.add(nuevaOpcion);
  }
}
