<template>
  <div class="buttons">
            <router-link
              to="/inventario/shopping/create"
              class="btn btn-outline-primary block"
              ><i class="bi bi-pencil"></i> Nuevo</router-link
            >
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
