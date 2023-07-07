<template>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <div class="mb-3">
                  <label for="tipoDocumento" class="form-label"
                    >Tipo de Documento</label
                  >
                  <select
                    class="form-select"
                    id="tipoDocumento"
                    v-model="cliente.selectedDocument"
                  >
                    <option disabled value="0">Seleccionar</option>
                    <option
                      v-for="doc in cliente.document"
                      :key="doc.id"
                      :value="doc.id"
                    >
                      {{ doc.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="mb-3">
                  <label for="dni" class="form-label">DNI</label>
                  <input
                    type="number"
                    class="form-control"
                    id="dni"
                    v-model="cliente.dni"
                    @keydown.enter="buscarCliente"
                  />
                </div>
              </div>
              <div class="col-3">
                <div class="mb-3">
                  <label for="domicilio" class="form-label">Domicilio</label>
                  <input
                    type="text"
                    class="form-control"
                    id="domicilio"
                    v-model="cliente.direccion"
                  />
                </div>
              </div>
              <div class="col-3">
                <div class="mb-3">
                  <label for="razonSocial" class="form-label"
                    >Razón Social</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="razonSocial"
                    v-model="cliente.razonSocial"
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <div class="mb-3">
                  <label for="tipoDocumentoAfectado" class="form-label"
                    >Tipo de Documento Afectado</label
                  >
                  <select
                    class="form-select"
                    id="tipoDocumentoAfectado"
                    v-model="afectado.selected"
                  >
                    <option disabled value="0">Seleccionar</option>
                    <option
                      v-for="doc in afectado.document"
                      :key="doc.id"
                      :value="doc.id"
                    >
                      {{ doc.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-4">
                <div class="mb-3">
                  <label for="fechaEmision" class="form-label"
                    >Fecha de Emisión</label
                  >
                  <input type="date" class="form-control" id="fechaEmision" />
                </div>
              </div>
              <div class="col-4">
                <div class="mb-3">
                  <label for="anulacionOperacion" class="form-label"
                    >Motivo</label
                  >
                  <select
                    class="form-select"
                    id="anulacionOperacion"
                    v-model="motivo.selected"
                  >
                    <option disabled value="0">Seleccionar</option>
                    <option
                      v-for="doc in motivo.document"
                      :key="doc.id"
                      :value="doc.id"
                    >
                      {{ doc.name }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="col-4"></div>
            </div>
            <div class="row">
              <div class="col-4"></div>
              <div class="col-4">
                <label for="serieCorrelativo" class="form-label"
                  >Serie y Correlativo</label
                >
                <div class="d-flex">
                  <input
                    type="text"
                    class="form-control me-3"
                    id="serie"
                    placeholder="Serie"
                    v-model="boleta.serie"
                  />
                  <input
                    type="number"
                    class="form-control me-3"
                    id="correlativo"
                    placeholder="Correlativo"
                    v-model="boleta.correlativo"
                  />
                  <button
                    class="btn btn-outline-secondary"
                    type="button"
                    @click="obtenerVenta"
                  >
                    <i class="bi bi-search"></i>
                  </button>
                </div>
              </div>
            </div>

            <table class="table mt-4">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Código</th>
                  <th>Descripción</th>
                  <th>Cantidad</th>
                  <th>Valor Unitario</th>
                  <th>Precio Unitario</th>
                  <th>Tipo Precio</th>
                  <th>IGV</th>
                  <th>Valor Total</th>
                  <th>Importe Total</th>
                  <th>Unidad</th>
                  <th>Código Afectación</th>
                  <th>Tipo Afectación</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="detalle in venta.detalles" :key="detalle.item">
                  <td>{{ detalle.item }}</td>
                  <td>{{ detalle.codigo }}</td>
                  <td>{{ detalle.descripcion }}</td>
                  <td>{{ detalle.cantidad }}</td>
                  <td>{{ detalle.valor_unitario }}</td>
                  <td>{{ detalle.precio_unitario }}</td>
                  <td>{{ detalle.tipo_precio }}</td>
                  <td>{{ detalle.igv }}</td>
                  <td>{{ detalle.valor_total }}</td>
                  <td>{{ detalle.importe_total }}</td>
                  <td>{{ detalle.unidad }}</td>
                  <td>{{ detalle.codigo_afectacion }}</td>
                  <td>{{ detalle.tipo_afectacion }}</td>
                </tr>
              </tbody>
            </table>
            <div class="text-end mt-4">
              <button class="btn btn-primary">Enviar nota</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
export default {
  data() {
    return {
      cliente: {
        selectedDocument: 0,
        document: [
          { id: 2, name: "DNI" },
          { id: 1, name: "Sin documento" },
        ],
        idCliente: "",
        dni: "",
        razonSocial: "",
        direccion: "",
      },
      afectado: {
        selected: 0,
        document: [{ id: 1, name: "Boleta" }],
      },
      motivo: {
        selected: 0,
        document: [{ id: "01", name: "Anulación de la Operación" }],
      },
      boleta: {
        serie: "",
        correlativo: "",
      },
      venta: [],
    };
  },
  methods: {
    buscarCliente() {
      const dni = this.cliente.dni;
      axios
        .post("/api/clientes/buscar", {
          document: dni,
        })
        .then((response) => {
          const toastParams = {
            duration: 3000,
            close: true,
            gravity: "bottom",
            position: "left",
          };
          if (response.data.length > 0) {
            Toastify({
              text: "¡Cargando!",
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
            const cliente = response.data[0];
            this.cliente.razonSocial = cliente.nombre;
            this.cliente.idCliente = cliente.id;
            this.cliente.direccion = "Av Arica 123 Breña";
          } else {
            Toastify({
              text: "¡No existe!",
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #ff5733, #ff8f33)",
                color: "#fff",
              },
            }).showToast();
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    obtenerVenta() {
      axios
        .post("/api/ventas/obtenerbyserie", {
          serie: this.boleta.serie,
          correlativo: this.boleta.correlativo,
        })
        .then((response) => {
          console.log(response.data);
          this.venta = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>



