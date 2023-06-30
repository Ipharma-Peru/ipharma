<template>
  <div
    class="modal fade"
    id="clientePopup"
    tabindex="-1"
    aria-labelledby="clienteLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="clienteLabel">Nuevo cliente</h1>
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
              <label for="recipient-numero-documento" class="col-form-label"
                >Número de documento:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-numero-documento"
                v-model="numero_documento"
                @input="validateNumeroDocumento"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-nombres" class="col-form-label"
                >Nombres:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-nombres"
                v-model="nombres"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-apellido-paterno" class="col-form-label"
                >Apellido Paterno:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-apellido-paterno"
                v-model="apellido_paterno"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-apellido-materno" class="col-form-label"
                >Apellido Materno:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-apellido-materno"
                v-model="apellido_materno"
              />
            </div>
            <div class="mb-3">
              <label for="recipient-fecha-nacimiento" class="col-form-label"
                >Fecha de Nacimiento:</label
              >
              <input
                type="date"
                class="form-control"
                id="recipient-fecha-nacimiento"
                v-model="fecha_nacimiento"
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
            Cerrar
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click="addCliente"
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
      numero_documento: "",
      nombres: "",
      apellido_paterno: "",
      apellido_materno: "",
      fecha_nacimiento: "",
    };
  },
  computed: {
    isButtonDisabled() {
      return (
        this.numero_documento.length !== 8 ||
        this.nombres.length === 0 ||
        this.apellido_paterno.length === 0 ||
        this.apellido_materno.length === 0 
      );
    },
  },
  methods: {
    cerrarPopup() {
      this.numero_documento = "";
      this.nombres = "";
      this.apellido_paterno = "";
      this.apellido_materno = "";
      this.fecha_nacimiento = "";
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addCliente() {
      axios
        .post("/api/clientes/registrar", {
          numero_documento: this.numero_documento,
          nombres: this.nombres,
          apellido_paterno: this.apellido_paterno,
          apellido_materno: this.apellido_materno,
          fecha_nacimiento: this.fecha_nacimiento,
        })
        .then((response) => {
          if (response.data.status) {
            Toastify({
              text: "¡Guardado!",
              duration: 3000,
              close: true,
              gravity: "bottom",
              position: "left",
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
            this.numero_documento = "";
            this.nombres = "";
            this.apellido_paterno = "";
            this.apellido_materno = "";
            this.fecha_nacimiento = "";
          }
          console.log(response)
        })
        .catch((error) => {
          console.error("Error al agregar el cliente", error);
        });
    },
    validateNumeroDocumento() {
      if (this.numero_documento.length > 8) {
        this.numero_documento = this.numero_documento.slice(0, 8);
      }
    },
  },
};
</script>

