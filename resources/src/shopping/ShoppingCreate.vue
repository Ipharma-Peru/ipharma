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
              <div class="col-md-12">
                <div class="form-group">
                  <label for="proveedor" class="form-label">Proveedor</label>
                  <input
                    type="text"
                    class="form-control"
                    id="proveedor"
                    v-model="formData.proveedores"
                    placeholder="Proveedor"
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="factura" class="form-label">Factura</label>
                  <input
                    type="text"
                    class="form-control"
                    id="factura"
                    v-model="formData.factura"
                    placeholder="Factura"
                  />
                </div>
              </div>
              <div class="col-md-4">
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
            <div class="row">
              <div class="container">
                <table class="table">
                  <thead>
                    <tr>
                      <th class="col-1">Acciones</th>
                      <th class="col-3">Artículo</th>
                      <th class="col-1">Lote</th>
                      <th class="col-1">Fecha</th>
                      <th class="col-1">Cantidad</th>
                      <th class="col-1">Precio</th>
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
                              @click="selectItem(option, item)"
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
                          type="number"
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
import axios from "axios";

export default {
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
          codigoArticulo: "",
        },
      ],
      formData: {
        proveedores: "",
        fechaFactura: "",
        factura: "",
      },
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
          articulo: item.articulo,
          lote: item.lote,
          fecha: item.fecha,
          cantidad: item.cantidad,
          precio: item.precio,
          codigoArticulo: item.codigoArticulo, // Agregar el código del artículo
        })),
        proveedor: this.formData,
      };
      console.log(datos);
      debugger;
      axios
        .post("URL_DEL_ENDPOINT", datos)
        .then((response) => {
          // La solicitud se completó con éxito, puedes manejar la respuesta aquí
          console.log(response.data);
        })
        .catch((error) => {
          // Ocurrió un error al realizar la solicitud POST, puedes manejar el error aquí
          console.error(error);
        });
    },
    handleInput(item) {
      item.showSuggestions = item.articulo.length >= 3;
      this.fetchOptions(item);
    },
    handleBlur(item) {
      setTimeout(() => {
        if (!item.optionSelected) {
          item.showSuggestions = false;
        }
        item.optionSelected = false;
      }, 200);
    },
    selectItem(option, item) {
      item.articulo = option.nombre;
      item.showSuggestions = false;
      item.optionSelected = true;
      item.codigoArticulo = option.id;
    },
    fetchOptions(item) {
      axios
        .post("/api/products", {
          search: item.articulo.toLowerCase(),
        })
        .then((response) => {
          item.options = response.data.map((product) => ({
            nombre: product.descripcion,
            id: product.codigo,
            // uniqueId: `${item.articulo}-${index}`,
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