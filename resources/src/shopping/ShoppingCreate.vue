<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Crear Compra</h3>
          <router-link to="/inventario/shopping"
            >Regresar a Compras</router-link
          >
        </div>

        <div class="card-body">
          <form @submit.prevent="addData">
            <div class="row">
              <div class="col-md-7">
                <label for="proveedor" class="form-label">Proveedor</label>
                <div class="form-group">
                  <div class="input-group">
                    <div class="full-row">
                      <input
                        type="text"
                        class="form-control"
                        id="proveedor"
                        v-model="formData.proveedores"
                        placeholder="Proveedor"
                        @input="fetchSuggestions"
                        @focus="toggleSuggestions(true)"
                        @blur="toggleSuggestions(false)"
                      />
                      <ul v-if="showSuggestionList" class="suggestion-list">
                        <li
                          v-for="suggestion in suggestions"
                          :key="suggestion"
                          @mousedown="selectSuggestion(suggestion)"
                        >
                          {{ suggestion.razon_social }}
                        </li>
                      </ul>
                    </div>
                    <button
                      type="button"
                      class="btn btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#proveedorPopup"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="factura" class="form-label"
                    >Numero de Factura</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="factura"
                    v-model="formData.factura"
                    placeholder="Factura"
                  />
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="fechaFactura" class="form-label"
                    >Fecha de Factura</label
                  >
                  <input
                    type="date"
                    class="form-control"
                    id="fechaFactura"
                    v-model="formData.fechaFactura"
                    placeholder="Fecha de Factura"
                  />
                </div>
              </div>
            </div>
            <ProveedorPopup />
            <div class="row"></div>
            <div class="row">
              <div class="container">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="col-1">Acciones</th>
                      <th class="col-3">Productos</th>
                      <th class="col-1">Lote</th>
                      <th class="col-1">F. Vencimiento</th>
                      <th class="col-1">Cantidad</th>
                      <th class="col-1">Precio Unitario</th>
                      <th class="col-1">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in items" :key="index">
                      <td>
                        <button
                          class="btn btn-danger"
                          @click="removeRow(index)"
                        >
                          <i class="bi bi-trash"></i>
                        </button>
                      </td>
                      <td>
                        <div class="autocomplete-container">
                          <input
                            class="form-control"
                            type="text"
                            v-model="item.articulo"
                            @input="handleInput(item)"
                            @blur="handleBlur(item)"
                          />
                          <ul
                            v-show="item.showSuggestions"
                            class="list-group autocomplete-list"
                          >
                            <li
                              v-for="option in item.options"
                              :key="option.id"
                              class="list-group-item"
                              @mousedown="selectItem(option, item)"
                            >
                              {{ option.nombre }}
                            </li>
                          </ul>
                        </div>
                      </td>
                      <td>
                        <input
                          type="text"
                          class="form-control"
                          v-model="item.lote"
                        />
                      </td>
                      <td>
                        <input
                          type="date"
                          class="form-control"
                          v-model="item.fecha"
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          class="form-control"
                          v-model="item.cantidad"
                        />
                      </td>
                      <td>
                        <input
                          type="text"
                          class="form-control"
                          v-model="item.precio"
                        />
                      </td>
                      <td>
                        <label>{{ calcularTotal(item) }}</label>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="7">
                        <button
                          class="btn btn-outline-primary block"
                          @click.prevent="addRow"
                          :disabled="!areAllRowsComplete"
                        >
                          Agregar fila
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <button
                  class="btn btn-primary"
                  :disabled="!areAllFieldsFilled"
                  type="submit"
                >
                  Enviar
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import ProveedorPopup from "./popup/ProveedorPopup.vue";
import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import Swal from "sweetalert2";

export default {
  components: {
    ProveedorPopup,
  },
  data() {
    return {
      selectedItem: "",
      showSuggestions: false,
      options: [],
      optionClicked: false,
      items: [
        {
          articulo: "",
          lote: "",
          fecha: "",
          cantidad: "",
          precio: "",
          showSuggestions: "",
          optionSelected: "",
          id: "",
        },
      ],
      formData: {
        proveedores: "",
        proveedorId: "",
        fechaFactura: "",
        factura: "",
      },
      suggestions: [],
      showSuggestionList: false,
    };
  },
  computed: {
    filteredOptions() {
      const searchTerm = removeAccents(this.selectedItem.toLowerCase());
      return this.options.filter((option) =>
        removeAccents(option.nombre.toLowerCase()).includes(searchTerm)
      );
    },
    areAllRowsComplete() {
      return this.items.every((item) => {
        return Object.values(item).every((value) => value !== "");
      });
    },
    areAllFieldsFilled() {
      const itemsFieldsFilled =
        this.items.length > 0 &&
        this.items.every((item) => {
          return Object.values(item).every((value) => value !== "");
        });

      const formDataFieldsFilled = Object.values(this.formData).every(
        (value) => value !== ""
      );
      console.log(formDataFieldsFilled);

      return itemsFieldsFilled && formDataFieldsFilled;
    },
  },
  mounted() {},
  watch: {
    selectedItem() {
      if (this.selectedItem.length >= 3) {
        this.fetchOptions();
      }
    },
  },
  methods: {
    addData() {
      // Recopilar los datos de la tabla y formData
      const datos = {
        items: this.items.map((item) => ({
          lote: item.lote,
          fechaVencimiento: item.fecha,
          cantidad: item.cantidad,
          precioUnitario: item.precio,
          idProducto: item.id, // Agregar el código del artículo
        })),
        idProveedor: this.formData.proveedorId,
        numeroDocumento: this.formData.factura,
        fechaCompra: this.formData.fechaFactura,
      };
      axios
        .post("/api/compras/registrar", datos)
        .then((response) => {
          if (response.data.status) {
            this.clearFormData()
            Swal.fire("Registrado!", "Se ingreso correctamente", "success");
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    handleInput(item) {
      item.showSuggestions = item.articulo.length >= 3;
      this.fetchOptions(item);
    },
    handleBlur(item) {
      item.showSuggestions = false;
    },
    selectItem(option, item) {
      item.articulo = option.nombre;
      item.showSuggestions = false;
      item.optionSelected = true;
      item.id = option.id;
    },
    fetchOptions(item) {
      axios
        .post("/api/compras/buscarproductos", {
          search: item.articulo.toLowerCase(),
        })
        .then((response) => {
          item.options = response.data.map((product) => ({
            nombre: product.descripcion,
            codigo: product.codigo,
            id: product.id,
          }));
        })
        .catch((error) => {
          console.error(error);
        });
    },
    addRow() {
      if (this.areAllRowsComplete) {
        this.items.push({
          articulo: "",
          lote: "",
          fecha: "",
          cantidad: "",
          precio: "",
          showSuggestions: false,
          optionSelected: false,
        });
      }
    },
    removeRow(index) {
      this.items.splice(index, 1);
    },
    calcularTotal(item) {
      return item.cantidad * item.precio;
    },
    // -----Provedor Sugestion------
    fetchSuggestions() {
      const inputValue = this.formData.proveedores;

      axios
        .post("/api/proveedor/buscar", { search: inputValue })
        .then((response) => {
          this.suggestions = response.data;
          this.showSuggestionList = true;
        })
        .catch((error) => {
          console.error("Error al obtener las sugerencias:", error);
        });
    },
    toggleSuggestions(show) {
      this.showSuggestionList = show && this.suggestions.length > 0;
    },
    selectSuggestion(suggestion) {
      this.formData.proveedores = suggestion.razon_social;
      this.formData.proveedorId = suggestion.id;
      this.showSuggestionList = false;
    },
    clearFormData() {
      this.formData.proveedorId = "";
      this.formData.factura = "";
      this.formData.fechaFactura = "";
      this.formData.proveedores = "";
      this.items = [];
    },
  },
};
function removeAccents(text) {
  return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}
</script>
<style scoped>
.autocomplete-container {
  position: relative;
}

.autocomplete-list {
  z-index: 99999;
  position: absolute;
  width: 100%;
}
.autocomplete-list li:hover {
  background-color: #435ebe34;
  cursor: pointer;
}
</style>
<style>
.suggestion-list {
  position: absolute;
  background-color: #fff;
  border: 1px solid #ccc;
  border-top: none;
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 100%;
}
.suggestion-list li {
  padding: 5px 10px;
  cursor: pointer;
}
.full-row {
  width: 95%;
}
</style>