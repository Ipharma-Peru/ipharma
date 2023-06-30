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
              BO19819081098
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
                    type="text"
                    class="form-control me-2"
                    id="dni"
                    placeholder="DNI"
                    aria-label="Recipient's username"
                    aria-describedby="button-addon2"
                  />
                  <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#clientePopup"
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
            <div class="table-responsive search">
              <table class="table table-striped table-hover mb-0">
                <thead>
                  <tr>
                    <th class="col-1">Action</th>
                    <th class="col-1">Ítem</th>
                    <th class="col-3">Artículo</th>
                    <th class="col-1">Lote</th>
                    <th class="col-1">Vcto</th>
                    <th class="col-1">F</th>
                    <th class="col-1">Cantidad</th>
                    <th class="col-1">P. X</th>
                    <th class="col-1">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in selectedProducts" :key="index">
                    <td>
                      <button
                        class="btn icon btn-primary btn-sm"
                        @click="removeProduct(index)"
                      >
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </td>
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.descripcion }}</td>
                    <td>{{ product.numero_lote }}</td>
                    <td>12/2024</td>
                    <td>
                      <div class="form-check">
                        <div class="custom-control custom-checkbox">
                          <input
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
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              class="detail-sales d-flex flex-column justify-content-end align-items-end pt-3"
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
                <span class="badge bg-secondary">{{ selecLabArticle }} </span>
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
                      <th class="col-1">P. Caja</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
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
              <h5 class="">Generico</h5>
              <h5>
                <span class="badge bg-secondary">Lab: </span>
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
                      <th class="col-1">P. Caja</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
                      <th class="col-1">Stock</th>
                      <th class="col-1">Estado</th>
                      <th class="col-1">Presentación</th>
                    </tr>
                  </thead>
                  <tbody class="table-group-divider">
                    <tr v-for="item in generico" :key="item.id">
                      <td>{{ item.codigo }}</td>
                      <td>{{ item.product_description }}</td>
                      <td>{{ item.nombre_laboratorio }}</td>
                      <td>{{ item.precio_caja }}</td>
                      <td>{{ item.precio_blister }}</td>
                      <td>{{ item.precio_unidad }}</td>
                      <td>{{ item.stock }}</td>
                      <td>{{ item.activo }}</td>
                      <td>{{ item.presentacion }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-between align-self-center mt-4">
              <h5 class="">Comercial</h5>
              <h5>
                <span class="badge bg-secondary">Lab: </span>
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
                      <th class="col-1">P. Caja</th>
                      <th class="col-1">P. Blís.</th>
                      <th class="col-1">P. Frac.</th>
                      <th class="col-1">Stock</th>
                      <th class="col-1">Estado</th>
                      <th class="col-1">Presentación</th>
                    </tr>
                  </thead>
                  <tbody class="table-group-divider">
                    <tr v-for="item in marca" :key="item.id">
                      <td>{{ item.codigo }}</td>
                      <td>{{ item.product_description }}</td>
                      <td>{{ item.nombre_laboratorio }}</td>
                      <td>{{ item.precio_caja }}</td>
                      <td>{{ item.precio_blister }}</td>
                      <td>{{ item.precio_unidad }}</td>
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
  </section>
</template>

<script>
import ClientePopup from "./popup/ClientePopup.vue";
import axios from "axios";
export default {
  components: {
    ClientePopup,
  },
  data() {
    return {
      products: [],
      searchTerm: "",
      selecLabArticle: "",
      marca: [],
      generico: [],
      selectedProducts: [],
      cliente: {
        selectedDocument: "",
        document: [
          { id: 0, name: "Con DNI" },
          { id: 1, name: "Sin documento" },
        ],
      },
    };
  },
  mounted() {},
  computed: {
    // --------Sumatoria de Productos----------
    subTotal() {
      let total = 0;
      for (let product of this.selectedProducts) {
        total += parseFloat(product.total_price) || 0;
      }
      return total.toFixed(2);
    },
    igv() {
      const subTotal = parseFloat(this.subTotal) || 0;
      const igvPercentage = 0.18; // Porcentaje de IGV (18%)
      const igvAmount = subTotal * igvPercentage;
      return igvAmount.toFixed(2);
    },
    total() {
      const subTotal = parseFloat(this.subTotal) || 0;
      const igv = parseFloat(this.igv) || 0;
      return (subTotal - igv).toFixed(2);
    },
  },
  methods: {
    fetchProducts() {
      axios
        .post("/api/products", { search: this.searchTerm })
        .then((response) => {
          this.products = response.data;
        })
        .catch((error) => {
          console.error("Error al obtener los datos:", error);
        });
    },
    selectProduct(product) {
      this.selecLabArticle = product.nombre_laboratorio;
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
    addSelectedProduct(product) {
      // Verificar si el producto ya está en el arreglo
      const exists = this.selectedProducts.some(
        (p) => p.codigo === product.codigo
      );

      // Si no existe, agregar el producto al arreglo
      if (!exists) {
        product.selected = false;
        product.quantity = 0;
        this.selectedProducts.push(product);
      }
    },
    updateProduct(index) {
      console.log(this.selectedProducts[index]);

      const inputValue = this.selectedProducts[index].quantity;

      const isChecked = this.selectedProducts[index].selected;

      console.log(isChecked);
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
  },
};
</script>
