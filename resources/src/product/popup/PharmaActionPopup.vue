<template>
  <div
    class="modal fade"
    id="pharmaAction"
    tabindex="-1"
    aria-labelledby="pharmaActionLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="pharmaActionLabel">
            Nuevo Acción Farmacológica
          </h1>
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
              <label for="recipient-pharmaAction" class="col-form-label"
                >Acción Farmacológica:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-pharmaAction"
                v-model="namepharmaAction"
              />
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
          <button
            type="button"
            class="btn btn-primary"
            @click="addpharmaAction"
          >
            Guardar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      namepharmaAction: "",
    };
  },
  methods: {
    cerrarPopup() {
      this.namepharmaAction = ""; // Reiniciar el campo de nombre del pharmaAction al cerrar el pop-up
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addpharmaAction() {
      // Realizar la solicitud POST con axios
      console.log(this.namepharmaAction)
       debugger
      axios
        .post("/api/agregarpharmaAction", { nombre_pharmaAction: this.namepharmaAction })
        .then((response) => {
          // Aquí puedes manejar la respuesta de la solicitud
          // Puedes emitir un evento para enviar los datos del pharmaAction agregado a tu componente padre
          this.$emit("pharmaActionAgregado", response.data);
          this.namepharmaAction = ""; // Reiniciar el campo de nombre del pharmaAction después de agregarlo
          this.$emit("close"); // Cerrar el pop-up después de agregar el pharmaAction
        })
        .catch((error) => {
          console.error("Error al agregar el pharmaAction", error);
        });
    },
  },
};
</script>
