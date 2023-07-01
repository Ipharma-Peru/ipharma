<template>
  <div
    class="modal fade"
    id="presentation"
    tabindex="-1"
    aria-labelledby="presentationLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="presentationLabel">
            Nueva Presentación
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
              <label for="recipient-presentation" class="col-form-label"
                >Presentación:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-presentation"
                v-model="namePresentation"
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
            @click="addPresentation"
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
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

export default {
  data() {
    return {
      namePresentation: "",
    };
  },
  methods: {
    cerrarPopup() {
      this.namePresentation = ""; // Reiniciar el campo de nombre del laboratorio al cerrar el pop-up
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addPresentation() {
      // Realizar la solicitud POST con axios
      console.log(this.namePresentation);
      axios
        .post("/api/addpresentation", { presentacion: this.namePresentation })
        .then((response) => {
          if (response.data.status) {
            Toastify({
              text: "¡Guardado!",
              duration: 3000,
              close: true,
              gravity: "bottom", // `top` or `bottom`
              position: "left", // `left`, `center` or `right`
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
          }
        })
        .catch((error) => {
          console.error("Error al agregar el Presentation", error);
        });
    },
  },
};
</script>
