<template>
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
        <span class="badge bg-secondary">Lab:{{ selecLabArticle }} </span>
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
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      products: [],
      searchTerm: "",
      selecLabArticle: "",
      marca: [],
      generico: [],
    };
  },
  mounted() {
    // this.fetchUsers(); // Llama al método para obtener los usuarios al cargar el componente
  },
  methods: {
    fetchProducts() {
      axios
        .post("/api/products", { search: this.searchTerm })
        .then((response) => {
          console.log(response.data);
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
          console.log(generico)
          this.generico = generico;
          this.marca = marca;
        })
        .catch((error) => {
          console.error("Error al obtener los datos:", error);
        });
    },
  },
};
</script>
