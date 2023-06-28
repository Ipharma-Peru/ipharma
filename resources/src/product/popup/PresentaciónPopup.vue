<template>
  <LaboratorioPopup
    :modalId="modalId"
    :modalLabel="modalLabel"
    :titulo="titulo"
    :label="label"
    :inputId="inputId"
    @itemAgregado="agregarPresentacion"
    @close="cerrarPopup"
  />
</template>

<script>
import LaboratorioPopup from './LaboratorioPopup.vue';
import Choices from 'choices.js';


export default {
    components: {
        LaboratorioPopup,
    },
    data() {
        return {
            modalId: 'presentacionPopup',
            modalLabel: 'Nueva Presentación',
            titulo: 'Nueva Presentación',
            label: 'Presentación:',
            inputId: 'recipient-presentacion',
        };
    },
    methods: {
        agregarPresentacion(presentacion) {
            // Lógica para agregar la presentación
        },
        cerrarPopup() {
            // Lógica para cerrar el popup
        },

    }
}