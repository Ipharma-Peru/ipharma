<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Crear Compra</h3>
          <div>
            <label for="autocomplete">Selecciona un artículo:</label>
            <input
              id="autocomplete"
              class="form-control"
              type="text"
              v-model="selectedItem"
              @input="handleInput"
              @blur="handleBlur"
            />
            <ul v-show="showSuggestions" class="list-group">
              <li
                v-for="option in filteredOptions"
                :key="option.id"
                class="list-group-item"
                @click="selectItem(option)"
              >
                {{ option.nombre }}
              </li>
            </ul>
            <p>Artículo seleccionado: {{ selectedItem }}</p>
          </div>

          <router-link to="/inventario/shopping">Back to List</router-link>
        </div>

        <div class="card-body">
          <form @submit.prevent="addData">
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    class="form-control"
                    id="proveedores"
                    v-model="formData.proveedores"
                    placeholder="Proveedores"
                  />
                  <label for="proveedores">Proveedores</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input
                    type="text"
                    class="form-control"
                    id="factura"
                    v-model="formData.factura"
                    placeholder="Factura"
                  />
                  <label for="factura">Factura</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input
                    type="date"
                    class="form-control"
                    id="fechaFactura"
                    v-model="formData.fechaFactura"
                    placeholder="Fecha de Factura"
                  />
                  <label for="fechaFactura">Fecha de Factura</label>
                </div>
              </div>
            </div>
            <div class="container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Acciones</th>
                    <th>Artículo</th>
                    <th>Lote</th>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in items" :key="index">
                    <td>
                      <button class="btn btn-danger" @click="removeRow(index)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                    <td>
                      <input
                        type="text"
                        class="form-control"
                        v-model="item.articulo"
                      />
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
                      <button class="btn btn-primary" @click.prevent="addRow">
                        Agregar fila
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <button type="submit">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// import VueAutosuggest from "vue-autosuggest";
import axios from "axios";
// import { removeAccents } from "vue-autosuggest";

export default {
  components: {
    // VueAutosuggest,
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
    addRow() {
      this.items.push({
        articulo: "",
        lote: "",
        fecha: "",
        cantidad: "",
        precio: "",
      });
    },
    addData() {
      // Recopilar los datos de la tabla y formData
      const datos = {
        items: this.items,
        formData: this.formData,
      };
      console.log(this.items);
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
    removeRow(index) {
      this.items.splice(index, 1);
    },
    calcularTotal(item) {
      return item.cantidad * item.precio;
    },

    // Metodos para Auselect
    handleInput() {
      this.showSuggestions = this.selectedItem.length >= 3;
    },
    handleBlur() {
      setTimeout(() => {
        if (!this.optionSelected) {
          this.showSuggestions = false;
        }
        this.optionSelected = false;
      }, 200);
    },
    selectItem(option) {
      this.selectedItem = option.nombre;
      this.showSuggestions = false;
    },
    fetchOptions() {
      axios
        .post("/api/products", { search: this.selectedItem })
        .then((response) => {
          this.options = response.data.map((product) => ({
            nombre: product.descripcion,
            id: product.codigo,
          }));
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
function removeAccents(text) {
  return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}
</script>