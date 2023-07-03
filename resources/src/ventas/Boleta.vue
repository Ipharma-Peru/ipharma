<template>
  <div
    class="modal fade"
    id="pagoPopup"
    tabindex="-1"
    aria-labelledby="pagoLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="pagoLabel">Realizar Pago</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="">
            <h4>Pagar: <span class="badge bg-secondary">S/ {{ total }}</span></h4>
          </div>
          <div class="mb-3">
            <label for="monto-pago" class="col-form-label"
              >Monto de Pago:</label
            >
            <input
              type="text"
              class="form-control"
              id="monto-pago"
              v-model="montoPago"
              @input="calculateRest"
            />
          </div>
          <div class="mb-3">
            <h4>Vuelto: <span class="badge bg-success">S/ {{ restaMonto }}</span></h4>
          </div>
          <div class="mb-3 form-check">
            <input
              type="checkbox"
              class="form-check-input"
              id="pago-tarjeta"
              v-model="pagoConTarjeta"
            />
            <label class="form-check-label" for="pago-tarjeta"
              >Pago con Tarjeta</label
            >
          </div>
          <button type="button" class="btn btn-primary" @click="procesarPago">
            Procesar Pago
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
  props: {
    datos: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      montoPago: 0,
      restaMonto: 0,
      pagoConTarjeta: false,
      total: 0, // Agrega la propiedad total
    };
  },
  created() {
    this.calculateTotal(); // Calcula el total al crear el componente
  },
  watch: {
    datos: {
      handler() {
        this.calculateTotal(); // Calcula el total cuando se actualizan los datos
      },
      deep: true,
    },
  },
  methods: {
    calculateRest() {
      
      this.restaMonto = (this.montoPago - this.total).toFixed(2);
    },
    procesarPago() {
      console.log(this.datos);
      axios
        .post("/api/ventas/registrar", this.datos)
        .then((response) => {
          const toastParams = {
            duration: 3000,
            close: true,
            gravity: "bottom",
            position: "left",
          };
          if (response.data.status) {
            Toastify({
              text: "¡Guardado!",
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
          } else {
            Toastify({
              text: response.data.message,
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #ff5733, #ff8f33)",
                color: "#fff",
              },
            }).showToast();
          }
        })
        .catch((error) => {
          // Manejar el error si ocurre
          console.error(error);
        });
    },
    calculateTotal() {
      let total = 0;
      if (this.datos && this.datos.items) {
        this.datos.items.forEach((item) => {
          const cantidad = item.cantidad;
          const precio = item.precio;
          total += parseFloat(cantidad * precio);
        });
      }
      this.total = total.toFixed(2);
    },
  },
};
</script>
