<template>
  <section class="section">
    <div class="row">
      <div class="col-12 col-sm-12 col-lg-6" id="ventasDetail">
        <div class="card">
          <div
            class="card-header d-flex flex-column flex-sm-row justify-content-sm-between align-items-center"
          >
            <div class="custom-switch col-12 col-sm-6">
              <div class="btn-custom"></div>
              <button type="button" class="toggle-btn active">Boleta</button>
              <button type="button" class="toggle-btn">Factura</button>
            </div>
            <h4 class="card-title col-12 col-sm-6 text-center text-sm-end">
              B001-0000{{ correlativo }}
            </h4>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Cliente</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <fieldset class="form-group">
                  <select
                    v-model="cliente.selectedDocument"
                    class="form-select"
                  >
                    <option
                      v-for="option in cliente.document"
                      :key="option.id"
                      :value="option.id"
                    >
                      {{ option.name }}
                    </option>
                  </select>
                </fieldset>
              </div>
              <div class="col-6">
                <div class="input-group">
                  <input
                    maxlength="8"
                    type="text"
                    class="form-control me-2"
                    id="dni"
                    placeholder="DNI"
                    aria-label="Recipient's username"
                    aria-describedby="button-addon2"
                    v-model="cliente.dni"
                    @keydown.enter="fetchClienteData"
                    @input="handleDNIInput"
                    :disabled="cliente.selectedDocument === 1"
                  />
                  <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#clientePopup"
                    :disabled="cliente.selectedDocument === 1"
                  >
                    +
                  </button>
                </div>
              </div>
              <ClientePopup />
              <div class="col-md-6">
                <div class="form-group has-icon-left mt-2">
                  <div class="position-relative">
                    <input
                      type="text"
                      class="form-control"
                      id="direccion"
                      v-model="cliente.direccion"
                      disabled
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group has-icon-left mt-2">
                  <div class="position-relative">
                    <input
                      type="text"
                      class="form-control"
                      id="razonSocial"
                      v-model="cliente.razonSocial"
                      disabled
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="table-responsive productSelect">
              <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th class="col-1">Ítem</th>
                    <th class="col-3">Artículo</th>
                    <th class="col-1">Lote</th>
                    <th class="col-2">F. vcto.</th>
                    <th class="col-1">F</th>
                    <th class="col-1">Cantidad</th>
                    <th class="col-1">P. X</th>
                    <th class="col-1">Total</th>
                    <th class="col-1">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in selectedProducts" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.descripcion }}</td>
                    <td>{{ product.numero_lote }}</td>
                    <td>{{ product.fecha_vencimiento }}</td>
                    <td>
                      <div class="form-check">
                        <div class="custom-control custom-checkbox">
                          <input
                            v-if="product.fraccionable"
                            class="form-check-input form-check-primary"
                            type="checkbox"
                            :checked="product.selected"
                            @click="toggleProductSelection(product, index)"
                          />
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="form-group m-0">
                        <input
                          type="text"
                          @input="updateProduct(index)"
                          v-model="product.quantity"
                          class="form-control"
                          placeholder="Cantidad"
                        />
                      </div>
                    </td>
                    <td>
                      {{
                        product.selected
                          ? product.precio_unidad
                          : product.precio_caja
                      }}
                    </td>
                    <td>{{ calculateTotalPrice(product) }}</td>
                    <td>
                      <button
                        class="btn icon btn-primary btn-sm"
                        @click="removeProduct(index)"
                      >
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="detail-sales d-flex flex-column justify-content-end align-items-end pt-4"
            >
              <div class="sub-total d-flex">
                <label for="">Sub total</label>
                <h5 class="ps-5">S/ {{ subTotal }}</h5>
              </div>
              <div class="igv d-flex">
                <label for="">I.G.V</label>
                <h5 class="ps-5">S/ {{ igv }}</h5>
              </div>
              <div class="sub-total d-flex">
                <h5 for="">Total a cobrar</h5>
                <h5 class="ps-5">S/ {{ total }}</h5>
              </div>
            </div>
            <div class="text-end mt-4">
              <button
                class="btn btn-primary"
                @click="sendData"
                :disabled="disableVenderButton"
                data-bs-toggle="modal"
                data-bs-target="#pagoPopup"
              >
                Vender
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-12 col-lg-6" id="ventasProducts">
        <div class="card">
          <div
            class="card-header d-flex flex-column justify-content-center align-items-center"
          >
            <div class="col-md-8">
              <div class="form-group">
                <input
                  type="text"
                  class="form-control"
                  id="articulo"
                  placeholder="Artículo"
                  v-model="searchTerm"
                  @keydown.enter="fetchProducts"
                />
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-self-center">
              <h5 class="">Artículo</h5>
              <h5>
                <span class="badge bg-secondary"
                  >{{ selected.labArticle }}
                </span>
              </h5>
            </div>
            <div class="table-responsive search">
              <div class="scrollable-table">
                <table class="table table-striped table-hover mb-0 table-fixed">
                  <thead class="sticky-thead">
                    <tr>
                      <th class="col-1">Código</th>
                      <th class="col-4">Artículo</th>
                      <th class="col-1">Laboratorio</th>
                      <th class="col-1">P. X</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
                      <th class="col-2">F. vcto</th>
                      <th class="col-1">Stock</th>
                      <th class="col-1">Estado</th>
                      <th class="col-1">Presentación</th>
                    </tr>
                  </thead>

                  <tbody class="table-group-divider">
                    <tr
                      v-for="(product, index) in products"
                      :key="index"
                      @click="selectProduct(product)"
                      @dblclick="addSelectedProduct(product)"
                    >
                      <td>{{ product.codigo }}</td>
                      <td>{{ product.descripcion }}</td>
                      <td>{{ product.nombre_laboratorio }}</td>
                      <td>{{ product.precio_caja }}</td>
                      <td>{{ product.precio_blister }}</td>
                      <td>{{ product.precio_unidad }}</td>
                      <td>{{ product.fecha_vencimiento }}</td>
                      <td>{{ product.stock }}</td>
                      <td>{{ product.activo }}</td>
                      <td>{{ product.presentacion }}</td>
                      <td class="d-none">{{ product.numero_lote }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-between align-self-center mt-4">
              <h5 class="">Comercial</h5>
              <h5>
                <span class="badge bg-secondary">{{ selected.labMarca }} </span>
              </h5>
            </div>
            <div class="table-responsive search">
              <div class="scrollable-table">
                <table class="table table-striped table-hover mb-0 table-fixed">
                  <thead class="sticky-thead">
                    <tr>
                      <th class="col-1">Código</th>
                      <th class="col-4">Artículo</th>
                      <th class="col-1">Laboratorio</th>
                      <th class="col-1">P. X</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
                      <th class="col-2">F. vcto</th>
                      <th class="col-1">Stock</th>
                      <th class="col-1">Estado</th>
                      <th class="col-1">Presentación</th>
                    </tr>
                  </thead>
                  <tbody class="table-group-divider">
                    <tr
                      v-for="item in marca"
                      :key="item.id"
                      @click="setLaboratoryMarca(item)"
                      @dblclick="addSelectedProduct(item)"
                    >
                      <td>{{ item.codigo }}</td>
                      <td>{{ item.descripcion }}</td>
                      <td>{{ item.nombre_laboratorio }}</td>
                      <td>{{ item.precio_caja }}</td>
                      <td>{{ item.precio_blister }}</td>
                      <td>{{ item.precio_unidad }}</td>
                      <td>{{ item.fecha_vencimiento }}</td>
                      <td>{{ item.stock }}</td>
                      <td>{{ item.activo }}</td>
                      <td>{{ item.presentacion }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-between align-self-center mt-4">
              <h5 class="">Generico</h5>
              <h5>
                <span class="badge bg-secondary"
                  >{{ selected.labGeneric }}
                </span>
              </h5>
            </div>
            <div class="table-responsive search">
              <div class="scrollable-table">
                <table class="table table-striped table-hover mb-0 table-fixed">
                  <thead class="sticky-thead">
                    <tr>
                      <th class="col-1">Código</th>
                      <th class="col-4">Artículo</th>
                      <th class="col-1">Laboratorio</th>
                      <th class="col-1">P. X</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
                      <th class="col-2">F. vcto</th>
                      <th class="col-1">Stock</th>
                      <th class="col-1">Estado</th>
                      <th class="col-1">Presentación</th>
                    </tr>
                  </thead>
                  <tbody class="table-group-divider">
                    <tr
                      v-for="item in generico"
                      :key="item.id"
                      @click="setLaboratoryGeneric(item)"
                      @dblclick="addSelectedProduct(item)"
                    >
                      <td>{{ item.codigo }}</td>
                      <td>{{ item.descripcion }}</td>
                      <td>{{ item.nombre_laboratorio }}</td>
                      <td>{{ item.precio_caja }}</td>
                      <td>{{ item.precio_blister }}</td>
                      <td>{{ item.precio_unidad }}</td>
                      <td>{{ item.fecha_vencimiento }}</td>
                      <td>{{ item.stock }}</td>
                      <td>{{ item.activo }}</td>
                      <td>{{ item.presentacion }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Print :datos="datos" @pagoRealizado="limpiarAtributos"></Print>
  </section>
</template>

<script>
import ClientePopup from "./popup/ClientePopup.vue";
import Print from "./Boleta.vue";
import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
export default {
  components: {
    ClientePopup,
    Print,
  },
  data() {
    return {
      products: [],
      searchTerm: "",
      selected: {
        labArticle: "",
        labMarca: "",
        labGeneric: "",
      },
      marca: [],
      generico: [],
      selectedProducts: [],
      cliente: {
        selectedDocument: 1,
        document: [
          { id: 2, name: "DNI" },
          { id: 1, name: "Sin documento" },
        ],
        idCliente: "",
        dni: "",
        razonSocial: "",
        direccion: "",
      },
      datos: [],
      correlativo: "",
    };
  },
  watch: {
    "cliente.selectedDocument": function (newVal) {
      if (newVal === 1) {
        this.cliente.dni = "";
        this.cliente.idCliente = "";
        this.cliente.razonSocial = "";
        this.cliente.direccion = "";
      }
    },
  },
  mounted() {},
  created() {
    this.fetchCorrelativo();
  },
  computed: {
    disableVenderButton() {
      if (this.cliente.selectedDocument === 1) {
        return (
          this.selectedProducts.length === 0 ||
          this.selectedProducts.some((product) => product.quantity <= 0)
        );
      } else {
        return (
          this.selectedProducts.length === 0 ||
          this.selectedProducts.some((product) => product.quantity <= 0) ||
          !this.cliente.idCliente
        );
      }
    },
    // --------Sumatoria de Productos----------
    subTotal() {
      const subTotal = parseFloat(this.total) || 0;
      const igv = parseFloat(this.igv) || 0;
      return (subTotal - igv).toFixed(2);
    },
    igv() {
      const subTotal = parseFloat(this.total) || 0;
      const igvPercentage = 0.18; // Porcentaje de IGV (18%)

      // Filtrar los productos con afectacion "IGV"
      const igvProducts = this.selectedProducts.filter(
        (product) => product.afectacion === "IGV"
      );

      // Calcular el monto del IGV solo para los productos con afectacion "IGV"
      const igvAmount =
        igvProducts.reduce((total, product) => {
          return total + (parseFloat(product.total_price) || 0);
        }, 0) * igvPercentage;

      return igvAmount.toFixed(2);
    },
    total() {
      return this.selectedProducts
        .reduce((total, product) => {
          return total + (parseFloat(product.total_price) || 0);
        }, 0)
        .toFixed(2);
    },
  },
  methods: {
    fetchProducts() {
      if (this.searchTerm.length >= 3) {
        axios
          .post("/api/products", { search: this.searchTerm })
          .then((response) => {
            this.products = response.data;
          })
          .catch((error) => {
            console.error("Error al obtener los datos:", error);
          });
      }
    },
    selectProduct(product) {
      this.selected.labArticle = product.nombre_laboratorio;
      axios
        .post("/api/productscode", {
          codigo: product.codigo,
        })
        .then((response) => {
          const { generico, marca } = response.data;
          this.generico = generico;
          this.marca = marca;
        })
        .catch((error) => {
          console.error("Error al obtener los datos:", error);
        });
    },
    setLaboratoryGeneric({ nombre_laboratorio }) {
      this.selected.labGeneric = nombre_laboratorio;
    },
    setLaboratoryMarca({ nombre_laboratorio }) {
      this.selected.labMarca = nombre_laboratorio;
    },
    addSelectedProduct(product) {
      // Verificar si el producto ya está en el arreglo
      const exists = this.selectedProducts.some(
        (p) => p.codigo === product.codigo
      );

      // Verificar si el stock del producto es mayor a 0
      const hasStock = product.stock > 0;

      // Si no existe y tiene stock, agregar el producto al arreglo
      if (!exists && hasStock) {
        product.selected = false;
        product.quantity = 0;
        this.selectedProducts.push(product);
      }
    },
    updateProduct(index) {
      const inputValue = this.selectedProducts[index].quantity;

      const isChecked = this.selectedProducts[index].selected;

      if (isChecked) {
        this.selectedProducts[index].total_price = (
          inputValue * this.selectedProducts[index].precio_unidad
        ).toFixed(2);
      } else {
        this.selectedProducts[index].total_price = (
          inputValue * this.selectedProducts[index].precio_caja
        ).toFixed(2);
      }
    },
    calculateTotalPrice(product) {
      return product.total_price || 0;
    },
    toggleProductSelection(product, index) {
      product.selected = !product.selected; // Invierte el estado de la propiedad 'selected'
      this.updateProduct(index);
    },
    removeProduct(index) {
      this.selectedProducts.splice(index, 1);
      // this.calculateInvoiceTotals(); // Actualiza los totales después de eliminar el ítem
    },
    // ------Clientes--------
    fetchClienteData() {
      if (this.cliente.dni.length === 8) {
        axios
          .post("/api/clientes/buscar", {
            document: this.cliente.dni,
          })
          .then((response) => {
            const toastParams = {
              duration: 3000,
              close: true,
              gravity: "bottom",
              position: "left",
            };

            if (response.data.length > 0) {
              const cliente = response.data[0];
              this.cliente.razonSocial = cliente.nombre;
              this.cliente.idCliente = cliente.id;
              this.cliente.direccion = "Av Arica 123 Breña";

              Toastify({
                text: "¡Cargando!",
                ...toastParams,
                style: {
                  background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
              }).showToast();
            } else {
              Toastify({
                text: "¡No existe, agrégalo!",
                ...toastParams,
                style: {
                  background: "linear-gradient(to right, #ff5733, #ff8f33)",
                  color: "#fff",
                },
              }).showToast();
            }
          })
          .catch((error) => {
            console.error("Error al obtener los datos del cliente:", error);
          });
      } else {
        this.cliente.razonSocial = "";
        this.cliente.direccion = "";
        this.cliente.idCliente = "";
      }
    },
    handleDNIInput(event) {
      const input = event.target;
      const dni = input.value;

      if (dni.length > 8) {
        input.value = dni.slice(0, 8);
      }

      if (dni.length !== 8) {
        this.clearRazonSocialAndDireccion();
      }
    },
    clearRazonSocialAndDireccion() {
      this.cliente.razonSocial = "";
      this.cliente.direccion = "";
      this.cliente.idCliente = "";
    },
    sendData() {
      const items = this.selectedProducts.map((item) => {
        return {
          idProducto: item.product_id,
          fraccion: item.selected,
          cantidad: item.quantity,
          precio: item.selected ? item.precio_unidad : item.precio_caja,
        };
      });

      this.datos = {
        tipoDocumento: this.cliente.selectedDocument,
        idCliente: this.cliente.idCliente,
        items: items,
        fecha_emision: new Date().toISOString().split("T")[0],
      };
    },
    limpiarAtributos() {
      this.selectedProducts = [];
      this.clearRazonSocialAndDireccion();
      this.cliente.dni = "";
      this.cliente.selectedDocument = 1;
      this.searchTerm = "";
      this.marca = [];
      this.generico = [];
      this.products = [];
      this.fetchCorrelativo();
    },
    fetchCorrelativo() {
      axios
        .post("/api/documentos/correlativo", { serie: "B001" })
        .then((response) => {
          console.log(response);
          this.correlativo = response.data.correlativo;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
<style scoped>
.search tbody tr:hover {
  background-color: #435ebe34;
}
</style>
