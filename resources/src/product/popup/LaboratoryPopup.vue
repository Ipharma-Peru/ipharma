<template>
  <div
    class="modal fade"
    id="laboratory"
    tabindex="-1"
    aria-labelledby="laboratoryLabel"
    aria-hidden="true"
    ref="laboratoryModal"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="laboratoryLabel">
            Nuevo Laboratorio
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
              <label for="recipient-laboratory" class="col-form-label"
                >Laboratorio:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-laboratory"
                @keydown.enter="handleEnterKey"
                v-model="nameLaboratory"
                @input="handleInput"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-code" class="col-form-label">Código:</label>
              <input
                type="text"
                class="form-control"
                id="recipient-code"
                v-model="codeLaboratory"
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
            @click="addLAboratory"
            :disabled="codeLaboratory === '' || nameLaboratory === ''"
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
      nameLaboratory: "",
      codeLaboratory: "",
    };
  },
  mounted() {
    this.$nextTick(() => {
      const modalElement = new bootstrap.Modal(this.$refs.laboratoryModal);
      modalElement._element.addEventListener("shown.bs.modal", () => {
        this.setFocus();
      });
    });
  },
  methods: {
    handleEnterKey() {
      if (this.nameLaboratory.length > 1 && this.codeLaboratory.length > 1) {
        this.addLAboratory();
      }
    },
    handleInput() {
      const inputDigits = this.nameLaboratory.slice(0, 8);
      this.codeLaboratory = inputDigits;
    },
    setFocus() {
      this.$nextTick(() => {
        this.$refs.laboratoryModal
          .querySelector("#recipient-laboratory")
          .focus();
      });
    },
    cerrarPopup() {
      this.nameLaboratory = "";
      this.codeLaboratory = "";
      this.$emit("close", "laboratory");
    },
    addLAboratory() {
      axios
        .post("/api/addlaboratory", {
          nombre_laboratorio: this.nameLaboratory,
          codigo: this.codeLaboratory,
        })
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
          console.error("Error al agregar el laboratorio", error);
        });
    },
  },
};
</script>
