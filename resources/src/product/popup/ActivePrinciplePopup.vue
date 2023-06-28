<template>
  <div
    class="modal fade"
    id="laboratory"
    tabindex="-1"
    aria-labelledby="laboratoryLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="laboratoryLabel">Nuevo Laboratorio</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="recipient-laboratory" class="col-form-label"
                >Laboratorio:</label
              >
              <input type="text" class="form-control" id="recipient-laboratory" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
             @click="cerrarPopup"
            
          >
            Close
          </button>
          <button type="button" class="btn btn-primary" @click="agregarLaboratorio">Guardar</button>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      nombreLaboratorio: "",
    };
  },
  methods: {
    cerrarPopup() {
      alert("h");
      this.nombreLaboratorio = ""; // Reiniciar el campo de nombre del laboratorio al cerrar el pop-up
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    agregarLaboratorio() {
      // Realizar acciones para agregar el laboratorio con el nombre this.nombreLaboratorio
      // Puedes emitir un evento para enviar los datos del laboratorio agregado a tu componente padre
      this.$emit("laboratorioAgregado", this.nombreLaboratorio);
      this.nombreLaboratorio = ""; // Reiniciar el campo de nombre del laboratorio después de agregarlo
      this.$emit("close"); // Cerrar el pop-up después de agregar el laboratorio
    },
  },
};
</script>
