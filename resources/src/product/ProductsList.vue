
<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Productos</h3>
          <div class="buttons">
            <router-link
              to="/inventario/products/create"
              class="btn btn-outline-primary block"
              ><i class="bi bi-pencil"></i> Nuevo</router-link
            >
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped mb-0">
              <thead>
                <th class="col-1">Código</th>
                <th class="col-2">Descripcion</th>
                <th class="col-1">Laboratotio</th>
                <th class="col-1">PVP X</th>
                <th class="col-1">PVP X Blister</th>
                <th class="col-1">PVP X Fracción</th>
                <th class="col-1">Presentación</th>
                <th class="col-1">Clase</th>
                <th class="col-1">Sub Clase</th>
                <th class="col-1">Activo</th>
                <th class="col-1">Accion</th>
              </thead>
              <tbody>
                <tr v-for="product in productList" :key="product.id">
                  <td>{{ product.codigo }}</td>
                  <td>{{ product.descripcion }}</td>
                  <td>{{ product.nombre_laboratorio }}</td>
                  <td>{{ product.precio_caja }}</td>
                  <td>{{ product.precio_blister }}</td>
                  <td>{{ product.precio_unidad }}</td>
                  <td>{{ product.presentacion }}</td>
                  <td>{{ product.clase }}</td>
                  <td>{{ product.subclase }}</td>
                  <td>{{ product.activo ? "Sí" : "No" }}</td>
                  <td>
                    <button class="btn icon btn-primary btn-sm me-2" @click="deleteProduct(product.id)">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                    <button class="btn icon btn-primary btn-sm">
                      <i class="bi bi-pen-fill"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "ProductList",
  data() {
    return {
      productList: [],
    };
  },
  mounted() {
    // Realiza la llamada al JSON en el montaje del componente
    this.fetchProductList();
  },
  methods: {
    fetchProductList() {
      // Realiza la solicitud HTTP para obtener el JSON
      axios
        .post("/api/allproducts")
        .then((response) => {
          this.productList = response.data;
      console.log(response)

        })
        .catch((error) => {
          console.error("Error al obtener la lista de productos", error);
        });
    },
    deleteProduct(productId) {
      // Realiza la solicitud HTTP para eliminar un producto
      console.log(productId)
      axios
        .delete(`/api/products/${productId}`)
        .then((response) => {
          console.log("Producto eliminado con éxito", response.data);
          // Actualiza la lista de productos después de eliminar
          this.fetchProductList();
        })
        .catch((error) => {
          console.error("Error al eliminar el producto", error);
        });
    },
  },
};
</script>
