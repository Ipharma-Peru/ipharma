<template>
  <div
    class="modal fade"
    id="pharmaAction"
    tabindex="-1"
    aria-labelledby="pharmaActionLabel"
    aria-hidden="true"
    ref="pharmaActionModal"

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
          <form @submit.prevent>
            <div class="mb-3">
              <label for="recipient-pharmaAction" class="col-form-label"
                >Acción Farmacológica:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-pharmaAction"
                v-model="namepharmaAction"
                @keydown.enter="handleEnterKey"
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
            :disabled="namepharmaAction === ''"
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
      namepharmaAction: "",
    };
  },
  mounted() {
    this.$nextTick(() => {
      const modalElement = new bootstrap.Modal(this.$refs.pharmaActionModal);
      modalElement._element.addEventListener("shown.bs.modal", () => {
        this.setFocus();
      });
    });
  },
  methods: {
    setFocus() {
      this.$nextTick(() => {
        this.$refs.pharmaActionModal
          .querySelector("#recipient-pharmaAction")
          .focus();
      });
    },
    handleEnterKey() {
      if (this.namepharmaAction.length > 1) {
        this.addpharmaAction();
      }
    },
    cerrarPopup() {
      this.namepharmaAction = ""; // Reiniciar el campo de nombre del pharmaAction al cerrar el pop-up
      this.$emit("close", "pharmaAction"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addpharmaAction() {
      axios
        .post("/api/addpharmaaction", { nombre: this.namepharmaAction })
        .then((response) => {
          if (response.data.status) {
            this.cerrarPopup();
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
          console.error("Error al agregar el pharmaAction", error);
        });
    },
  },
};
</script>
