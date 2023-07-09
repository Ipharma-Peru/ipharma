<template>
  <section class="section">
    <div class="row">
      <div class="col-12" id="ventasDetail">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Ventas</h4>
          </div>
          <div class="card-body">
            <div>
              <div class="row mb-3">
                <div class="col-6">
                  <label for="fechaInicio" class="form-label"
                    >Fecha de Inicio</label
                  >
                  <input
                    type="date"
                    class="form-control"
                    id="fechaInicio"
                    v-model="fechaInicio"
                    @change="obtenerVentas"
                  />
                </div>
                <div class="col-6">
                  <label for="fechaFin" class="form-label">Fecha de Fin</label>
                  <input
                    type="date"
                    class="form-control"
                    id="fechaFin"
                    v-model="fechaFin"
                    @change="obtenerVentas"
                  />
                </div>
              </div>
              <table class="table mt-3">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Fecha de Emisión</th>
                    <th>Serie</th>
                    <th>Correlativo</th>
                    <th>Estado de Facturación</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="venta in ventas" :key="venta.id">
                    <td>{{ venta.id }}</td>
                    <td>{{ venta.fecha_emision }}</td>
                    <td>{{ venta.serie }}</td>
                    <td>{{ venta.correlativo }}</td>
                    <td>
                      <span v-if="venta.estado_facturacion === 1">
                        <button class="btn icon btn-success btn-sm">
                          <i class="bi bi-check"></i>
                        </button>
                      </span>
                      <span
                        v-else-if="venta.estado_facturacion === 2 ||venta.estado_facturacion === null"
                        @click="volverEnviar(venta.id)"
                      >
                        <button class="btn icon btn-warning btn-sm">
                          <i class="bi bi-arrow-clockwise"></i>
                        </button>
                      </span>
                    </td>
                    <td>S/. {{ venta.total }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <Print :datos="datos" @pagoRealizado="limpiarAtributos"></Print> -->
  </section>
</template>

<script>
import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

export default {
  data() {
    return {
      ventas: [],
      fechaInicio: "",
      fechaFin: "",
    };
  },
  methods: {
    obtenerVentas() {
      const data = {
        fecha_inicio: this.fechaInicio,
        fecha_fin: this.fechaFin,
      };

      axios
        .post("/api/ventas/listar", data)
        .then((response) => {
          this.ventas = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    volverEnviar(id) {
      axios
        .post("/ruta_de_tu_api", { ventaId: id })
        .then((response) => {
          // Aquí puedes realizar acciones adicionales después de enviar la factura nuevamente
          console.log("Factura enviada nuevamente");
        })
        .catch((error) => {
          // Manejo de errores en caso de que la solicitud falle
          console.error("Error al enviar la factura nuevamente", error);
        });
    },
  },
};
</script>
