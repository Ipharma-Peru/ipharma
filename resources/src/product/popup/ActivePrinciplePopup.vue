<template>
  <div
    class="modal fade"
    id="activePrinciple"
    tabindex="-1"
    aria-labelledby="activePrincipleLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="activePrincipleLabel">
            Nuevo Principio Activo
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
              <label for="recipient-activePrinciple" class="col-form-label"
                >Principio Activo:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-activePrinciple"
                v-model="nameactivePrinciple"
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
            @click="addactivePrinciple"
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
      nameactivePrinciple: "",
    };
  },
  methods: {
    cerrarPopup() {
      this.nameactivePrinciple = ""; // Reiniciar el campo de nombre del activePrinciple al cerrar el pop-up
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addactivePrinciple() {
      // Realizar la solicitud POST con axios
      console.log(this.nameactivePrinciple)
       debugger
      axios
        .post("/api/agregaractivePrinciple", { nombre_activePrinciple: this.nameactivePrinciple })
        .then((response) => {
          // Aquí puedes manejar la respuesta de la solicitud
          // Puedes emitir un evento para enviar los datos del activePrinciple agregado a tu componente padre
          this.$emit("activePrincipleAgregado", response.data);
          this.nameactivePrinciple = ""; // Reiniciar el campo de nombre del activePrinciple después de agregarlo
          this.$emit("close"); // Cerrar el pop-up después de agregar el activePrinciple
        })
        .catch((error) => {
          console.error("Error al agregar el activePrinciple", error);
        });
    },
  },
};
</script>
