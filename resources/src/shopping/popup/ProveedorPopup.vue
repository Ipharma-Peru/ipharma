<template>
  <div
    class="modal fade"
    id="proveedorPopup"
    tabindex="-1"
    aria-labelledby="proveedorLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="proveedorLabel">Nuevo proveedor</h1>
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
              <label for="recipient-proveedor" class="col-form-label"
                >Razon Social:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-proveedor"
                v-model="razon_social"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-proveedor" class="col-form-label"
                >Ruc:</label
              >
              <input
                type="number"
                class="form-control"
                id="recipient-proveedor"
                v-model="ruc"
                @input="validateRUC"
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
            @click="addproveedor"
            :disabled="isButtonDisabled"
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
      razon_social: "",
      ruc: "",
    };
  },
  computed: {
    isButtonDisabled() {
      return (
        this.razon_social.length === 0 || this.ruc.toString().length !== 11
      );
    },
  },
  methods: {
    cerrarPopup() {
      this.razon_social = "";
      this.ruc = "";
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addproveedor() {
      axios
        .post("/api/addprovider", {
          razon_social: this.razon_social,
          ruc: this.ruc,
        })
        .then((response) => {
          console.log(response.data.status);
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
            this.razon_social = "";
            this.ruc = "";
          }
        })
        .catch((error) => {
          console.error("Error al agregar el proveedor", error);
        });
    },
    validateRUC() {
      if (this.ruc.toString().length > 11) {
        this.ruc = this.ruc.toString().slice(0, 11);
      }
    },
  },
};
</script>
