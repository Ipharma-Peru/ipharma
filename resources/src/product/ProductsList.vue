
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
                <th class="col-2">Descripción</th>
                <th class="col-1">Clase</th>
                <th class="col-1">Subclase</th>
                <th class="col-1">Fraccionable</th>
                <th class="col-1">Unidad por Caja</th>
                <th class="col-1">Unidad por Blister</th>
                <th class="col-1">PVP X</th>
                <th class="col-1">PVP X Blister</th>
                <th class="col-1">PVP X Fracción</th>
                <th class="col-1">Presentación</th>
                <th class="col-1">laboratorio</th>
                <th class="col-1">Principio Activo</th>
                <th class="col-1">Acción farmacologica</th>
              </thead>
              <tbody>
                <tr v-for="product in productList" :key="product.id">
                  <td>{{ product.descripcion }}</td>
                  <td>{{ product.clase }}</td>
                  <td>{{ product.subclase }}</td>
                  <td>{{ product.fraccionable ? "Sí" : "No" }}</td>
                  <td>{{ product.unidadCaja }}</td>
                  <td>{{ product.unidadBlister }}</td>
                  <td>{{ product.pvpX }}</td>
                  <td>{{ product.pvpXBlister }}</td>
                  <td>{{ product.pvpXFraccion }}</td>
                  <td>{{ product.presentacion }}</td>
                  <td>{{ product.laboratorio }}</td>
                  <td>
                    <span
                      v-for="activo in product.principioActivo"
                      :key="activo"
                      class="badge bg-primary me-1"
                      >{{ activo }}</span
                    >
                  </td>
                  <td>
                    <span
                      v-for="accion in product.accionFarmacologica"
                      :key="accion"
                      class="badge bg-success me-1"
                      >{{ accion }}</span
                    >
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
      fetch("http://localhost:3000/products")
        .then((response) => response.json())
        .then((data) => {
          this.productList = data;
        })
        .catch((error) => {
          console.error("Error al obtener la lista de productos", error);
        });
    },
  },
};
</script>
