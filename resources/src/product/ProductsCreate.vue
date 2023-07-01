
<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3>Crear Productos</h3>
          <router-link to="/inventario/products">Back to List</router-link>
        </div>
        <div class="card-body">
          <form @submit.prevent="addForm()" ref="formCreate">
            <div class="row">
              <div class="col-7">
                <div class="form-group">
                  <label for="descripcion" class="form-label"
                    >Descripción</label
                  >
                  <input
                    type="text"
                    class="form-control round"
                    id="descripcion"
                    v-model="formData.description"
                  />
                </div>
              </div>
              <div class="col-1">
                <div class="form-group">
                  <label for="clase" class="form-label">Clase</label>
                  <select
                    class="form-select"
                    id="clase"
                    v-model="formData.tipoAfectacion"
                  >
                    <option
                      v-for="afetacion in afectaciones"
                      :key="afetacion.id"
                      :value="afetacion.id"
                    >
                      {{ afetacion.descripcion }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="clase" class="form-label">Clase</label>
                  <select
                    class="form-select"
                    id="clase"
                    v-model="formData.selectedClass"
                    @change="fetchSubclases"
                  >
                    <option
                      v-for="clase in clases"
                      :key="clase.id"
                      :value="clase.id"
                    >
                      {{ clase.descripcion }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="subclase" class="form-label">Subclase</label>

                  <select
                    class="form-select"
                    id="subclase"
                    v-model="formData.selectedSubclase"
                  >
                    <option
                      v-for="subclase in subclases"
                      :key="subclase.id"
                      :value="subclase.id"
                    >
                      {{ subclase.descripcion }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2">
                <div class="form-group">
                  <div class="form-check">
                    <div class="checkbox">
                      <input
                        type="checkbox"
                        class="form-check-input form-check-primary form-check-glow"
                        id="fraccionable"
                        v-model="formData.fraccionable"
                      />
                      <label class="form-check-label" for="fraccionable"
                        >Fraccionable</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="unidadCaja" class="form-label"
                    >Unidad por Caja</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    id="unidadCaja"
                    v-model="formData.unidadesByCaja"
                  />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="unidadBlister" class="form-label"
                    >Unidad por Blister</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    id="unidadBlister"
                    v-model="formData.unidadesByBlister"
                  />
                </div>
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label for="pvpX" class="form-label">PVP X</label>
                  <input
                    type="number"
                    class="form-control"
                    id="pvpX"
                    v-model="formData.pvpx"
                  />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="pvpBlister" class="form-label"
                    >PVP X Blister</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    id="pvpBlister"
                    v-model="formData.pvpBlister"
                    disabled
                  />
                </div>
              </div>
              <div class="col-2">
                <div class="form-group">
                  <label for="pvpFraccion" class="form-label"
                    >PVP X Fracción</label
                  >
                  <input
                    type="number"
                    class="form-control"
                    id="pvpFraccion"
                    v-model="formData.pvpFraccion"
                  />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="presentacion" class="form-label"
                  >Presentación</label
                >
                <fieldset>
                  <div class="input-group">
                    <select
                      class="form-select"
                      id="presentacion"
                      v-model="formData.selectedPresentacion"
                    >
                      <option
                        v-for="presentacion in presentaciones"
                        :key="presentacion.id"
                        :value="presentacion.id"
                      >
                        {{ presentacion.presentacion }}
                      </option>
                    </select>
                    <button
                      type="button"
                      class="btn btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#presentation"
                    >
                      +
                    </button>
                  </div>
                </fieldset>
              </div>
              <PresentationPopup @close="cerrarPopup" />
              <div class="col-6">
                <label for="laboratorio" class="form-label">Laboratorio</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="form-select"
                      id="laboratorio"
                      v-model="formData.selectedLaboratorio"
                    >
                      <option
                        v-for="lab in laboratorios"
                        :key="lab.id"
                        :value="lab.id"
                      >
                        {{ lab.nombre_laboratorio }}
                      </option>
                    </select>
                    <button
                      type="button"
                      class="btn btn-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#laboratory"
                      @click="abrirPopup"
                    >
                      +
                    </button>
                  </div>
                </fieldset>
              </div>
              <!-- Agrega el componente LaboratorioPopup en el mismo archivo -->
              <LaboratoryPopup @close="cerrarPopup" />
            </div>

            <div class="row mt-3">
              <div class="col-6">
                <label for="principioActivo" class="form-label"
                  >Principio Activo</label
                >
                <fieldset>
                  <div class="input-group">
                    <div class="col-11">
                      <multiselect
                        v-model="formData.selectedPrincipios"
                        :options="principios"
                        :multiple="true"
                        label="nombre"
                        track-by="id"
                        placeholder="Selecciona"
                      ></multiselect>
                    </div>
                    <div class="col-1 m-0 text-end">
                      <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#activePrinciple"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </fieldset>
              </div>
              <ActivePrinciplePopup @close="cerrarPopup" />
              <div class="col-6">
                <label for="accionFarmacologica" class="form-label"
                  >Acción Farmacológica</label
                >
                <fieldset>
                  <div class="input-group">
                    <div class="col-11">
                      <multiselect
                        v-model="formData.selectedactionPharma"
                        :options="actionPharma"
                        :multiple="true"
                        label="nombre"
                        track-by="id"
                        placeholder="Selecciona"
                      ></multiselect>
                    </div>
                    <div class="col-1 m-0 text-end">
                      <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#pharmaAction"
                      >
                        +
                      </button>
                    </div>
                  </div>
                </fieldset>
              </div>
              <PharmaActionPopup @close="cerrarPopup" />
            </div>
            <div class="row mt-3">
              <div class="col-6">
                <div class="buttons">
                  <button
                    type="submit"
                    class="btn btn-primary"
                    :disabled="isButtonDisabled"
                  >
                    <i class="bi bi-pencil"></i> Guardar
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LaboratoryPopup from "./popup/LaboratoryPopup.vue";
import PresentationPopup from "./popup/PresentationPopup.vue";
import PharmaActionPopup from "./popup/PharmaActionPopup.vue";
import ActivePrinciplePopup from "./popup/ActivePrinciplePopup.vue";
import axios from "axios";
import Multiselect from "vue-multiselect";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

export default {
  components: {
    LaboratoryPopup,
    PresentationPopup,
    Multiselect,
    PharmaActionPopup,
    ActivePrinciplePopup,
  },
  computed: {
    isButtonDisabled() {
      // Deshabilitar el botón si no se han llenado todos los campos excepto el checkbox de fraccionable
      const {
        description,
        selectedClass,
        selectedSubclase,
        unidadesByCaja,
        unidadesByBlister,
        pvpx,
        pvpBlister,
        pvpFraccion,
        selectedPresentacion,
        selectedLaboratorio,
        selectedPrincipios,
        selectedactionPharma,
        tipoAfectacion,
      } = this.formData;

      return (
        !description ||
        !selectedClass ||
        !selectedSubclase ||
        unidadesByCaja < 0 ||
        unidadesByBlister < 0 ||
        pvpx < 0 ||
        pvpBlister < 0 ||
        pvpFraccion < 0 ||
        !selectedPresentacion ||
        !selectedLaboratorio ||
        !selectedPrincipios ||
        !selectedactionPharma ||
        !tipoAfectacion
      );
    },
  },
  data() {
    return {
      mostrarPopup: false,
      clases: [],
      subclases: [],
      laboratorios: [],
      presentaciones: [],
      principios: [],
      actionPharma: [],
      formData: {
        description: "",
        selectedClass: "",
        selectedSubclase: "",
        fraccionable: false,
        unidadesByCaja: 0,
        unidadesByBlister: 0,
        pvpx: 0,
        pvpFraccion: 0,
        pvpBlister: 0,
        selectedPresentacion: "",
        selectedLaboratorio: "",
        selectedPrincipios: "",
        selectedactionPharma: "",
        tipoAfectacion: "",
      },
      afectaciones: [
          { id: 1, descripcion: "GRAVADAS" },
          { id: 2, descripcion: "EXONERADAS" },
          { id: 3, descripcion: "INAFECTAS" },
        ],
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchSubclases() {
      axios
        .post("/api/subclassbyid", { idClass: this.formData.selectedClass })
        .then((response) => {
          // Obtener los datos de respuesta y asignarlos a la variable subclases
          this.subclases = response.data.items;
          console.log(this.subclases);
        })
        .catch((error) => {
          console.error("Error al obtener las subclases:", error);
        });
    },
    abrirPopup() {
      this.mostrarPopup = true;
    },
    cerrarPopup() {
      this.mostrarPopup = false;
      this.fetchData();
    },
    fetchData() {
      axios
        .get("/api/productInit")
        .then((response) => {
          const data = response.data;
          this.clases = data.clases;
          this.laboratorios = data.laboratorios;
          this.presentaciones = data.presentaciones;
          this.principios = data.principios;
          this.actionPharma = data.acciones;
        })
        .catch((error) => {
          console.error("Error al obtener los datos:", error);
        });
    },
    addForm() {
      axios
        .post("/api/registerproduct", this.formData)
        .then((response) => {
          // Lógica para manejar la respuesta de la solicitud
          if (response.data.status) {
            Toastify({
              text: "¡Guardado!",
              duration: 3000,
              close: true,
              gravity: "bottom", // `top` or `bottom`
              position: "left", // `left`, `center` or `right`
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
            // Reiniciar los valores del formulario
            this.formData = {
              description: "",
              selectedClass: "",
              selectedSubclase: "",
              fraccionable: false,
              unidadesByCaja: "",
              unidadesByBlister: "",
              pvpx: "",
              pvpFraccion: "",
              pvpBlister: "",
              selectedPresentacion: "",
              selectedLaboratorio: "",
              selectedPrincipios: "",
              selectedactionPharma: "",
              tipoAfectacion:"",
            };
          }
        })
        .catch((error) => {
          // Lógica para manejar el error de la solicitud
        });
    },
  },
};
</script>
