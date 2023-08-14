<template>
  <div
    class="modal fade"
    id="activePrinciple"
    tabindex="-1"
    aria-labelledby="activePrincipleLabel"
    aria-hidden="true"
    ref="activePrincipleModal"
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
          <form @submit.prevent>
            <div class="mb-3">
              <label for="recipient-activePrinciple" class="col-form-label"
                >Principio Activo:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-activePrinciple"
                v-model="nameactivePrinciple"
                required
                @input="validateActivePrinciple"
                @blur="validateActivePrinciple"
                @keydown.enter="handleEnterKey"
              />
              <div class="invalid-feedback">
                Por favor, ingresa un principio activo.
              </div>
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
            :disabled="nameactivePrinciple === ''"
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
      nameactivePrinciple: "",
    };
  },
  mounted() {
    this.$emit("registerModal", this.openModal);
    this.$nextTick(() => {
      const modalElement = new bootstrap.Modal(this.$refs.activePrincipleModal);
      modalElement._element.addEventListener("shown.bs.modal", () => {
        this.setFocus();
      });
    });
  },
  beforeUnmount() {
    this.$emit("unregisterModal");
  },
  methods: {
    handleEnterKey() {
      if (this.nameactivePrinciple.length > 1) {
        this.addactivePrinciple();
      }
    },
    setFocus() {
      this.$nextTick(() => {
        this.$refs.activePrincipleModal
          .querySelector("#recipient-activePrinciple")
          .focus();
      });
    },
    validateActivePrinciple() {
      if (this.nameactivePrinciple === "") {
        // El campo está vacío, se muestra el mensaje de validación
        document
          .getElementById("recipient-activePrinciple")
          .classList.add("is-invalid");
      } else {
        // El campo tiene un valor, se quita el mensaje de validación
        document
          .getElementById("recipient-activePrinciple")
          .classList.remove("is-invalid");
      }
    },
    cerrarPopup() {
      this.nameactivePrinciple = ""; // Reiniciar el campo de nombre del activePrinciple al cerrar el pop-up
      this.$emit("close", "activePrinciple"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addactivePrinciple() {
      axios
        .post("/api/addsubstanceactive", { nombre: this.nameactivePrinciple })
        .then((response) => {
          this.cerrarPopup();
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
          console.error("Error al agregar el activePrinciple", error);
        });
    },
    openModal() {
      // Abrir el modal utilizando el ID del modal
      const modal = new bootstrap.Modal(
        document.getElementById("activePrinciple")
      );
      modal.show();
    },
  },
};
</script>
