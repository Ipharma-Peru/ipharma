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
          <h1 class="modal-title fs-5" id="proveedorLabel">
            Nuevo proveedor
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
              <label for="recipient-proveedor" class="col-form-label"
                >Razon Social:</label
              >
              <input
                type="text"
                class="form-control"
                id="recipient-proveedor"
                v-model="rsProveedor"
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
                v-model="rucProveedor"
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
          <button type="button" class="btn btn-primary" @click="addproveedor">
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
      rsProveedor: "",
      rucProveedor: "",
    };
  },
  methods: {
    cerrarPopup() {
      this.rsProveedor = ""; // Reiniciar el campo de nombre del proveedor al cerrar el pop-up
      this.$emit("close"); // Emitir evento para indicar que se ha cerrado el pop-up
    },
    addproveedor() {
      // Realizar la solicitud POST con axios
      console.log(this.rucProveedor,this.rsProveedor);
      debugger;
      axios
        .post("/api/addprovider", {
          razon_social: this.rsProveedor,
          ruc: this.rucProveedor,
        })
        .then((response) => {
          // Aquí puedes manejar la respuesta de la solicitud
          // Puedes emitir un evento para enviar los datos del proveedor agregado a tu componente padre
          this.$emit("proveedorAgregado", response.data);
          this.rsProveedor = ""; // Reiniciar el campo de nombre del proveedor después de agregarlo
          this.$emit("close"); // Cerrar el pop-up después de agregar el proveedor
        })
        .catch((error) => {
          console.error("Error al agregar el proveedor", error);
        });
    },
  },
};
</script>
