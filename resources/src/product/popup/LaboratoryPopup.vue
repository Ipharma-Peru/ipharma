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
                >Código:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-laboratory"
                v-model="codeLaboratory"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-laboratory" class="col-form-label"
                >Laboratorio:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-laboratory"
                v-model="nameLaboratory"
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
          <button type="button" class="btn btn-primary" @click="addLAboratory">
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
  methods: {
    cerrarPopup() {
      this.nameLaboratory = "";
      this.codeLaboratory = "";
      this.$emit("close");
    },
    addLAboratory() {
      axios
        .post("/api/addlaboratory", {
          nombre_laboratorio: this.nameLaboratory,
          codigo: this.codeLaboratory,
        })
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
          console.error("Error al agregar el laboratorio", error);
        });
    },
  },
};
</script>
