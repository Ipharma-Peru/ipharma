<template>
  <div id="reportrange" class="btn btn-primary mb-4" style="border-radius: 3px !important;border: transparent ; display: flex;height: 58px; align-items: center;
    justify-content: center;">
    <i class="fa fa-calendar"></i>&nbsp;
    <span> {{ this.fechaInicio }} - {{ this.fechaFin }} </span> <i class="fa fa-caret-down" style="margin-left: 10px"></i>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-12" id="ventasDetail">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Ventas</h4>
          </div>
          <div class="card-body">
            <table class="table mt-3 display" id="tblreporteVentas" style="width: 100%;">
              <thead>
                <tr>
                  <th>Serie</th>
                  <th>Correlativo</th>
                  <th>Producto</th>
                  <th>Fecha</th>
                  <th>Cantidad</th>
                  <th>Valor Unitario</th>
                  <th>Precio Unitario</th>
                  <th>Valor Total</th>
                  <th>Igv</th>
                  <th>Importe Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="venta in ventas" :key="venta.id">
                  <td>{{ venta.serie }}</td>
                  <td>{{ venta.correlativo }}</td>
                  <td>{{ venta.descripcion }}</td>
                  <td>{{ venta.fecha_emision }}</td>
                  <td>{{ venta.cantidad }}</td>
                  <td>{{ venta.valor_unitario }}</td>
                  <td>{{ venta.precio_unitario }}</td>
                  <td>{{ venta.valor_total }}</td>
                  <td>{{ venta.igv }}</td>
                  <td>{{ venta.importe_totalsale_detailssale_details }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>

import axios from "axios";

export default {
  name: "ReporteVentas",
  data() {
    return {
      ventas: [],
      fechaInicio: "",
      fechaFin: "",
      dataTable: null,
      nombreFile: ""
    };
  },
  mounted() {

    this.fechaInicio = moment().subtract(7, 'days');
    this.fechaFin = moment();

    $('#reportrange').daterangepicker({
      startDate: this.fechaInicio,
      endDate: this.fechaFin,
      ranges: {
        'Los último 7 días': [moment().subtract(6, 'days'), moment()],
        'último 30 días': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'El mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      "locale": {
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "DE",
        "toLabel": "HASTA",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
          "Dom",
          "Lun",
          "Mar",
          "Mie",
          "Jue",
          "Vie",
          "Sab"
        ],
        "monthNames": [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre"
        ],
        "firstDay": 1
      },
    }, this.cb);

    this.cb(this.fechaInicio, this.fechaFin);

  },
  methods: {

    generarNombreArchivo() {
      return this.nombreFile;
    },

    inicializarDataTable() {


      this.dataTable = $("#tblreporteVentas").DataTable({
        "pageLength": 10,
        dom: 'Bfrtip',
        "buttons": [
          {
            extend: 'excelHtml5',
            text: 'Exportar a Excel',
            className: 'btn btn-primary',
            filename: this.generarNombreArchivo(),
            title: 'Reporte de Ventas'
          }
        ],
        resposive: true,
        "language": {
          "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
      });
    },

    cb(start, end) {
      this.fechaInicio = start.format('YYYY-MM-DD');
      this.fechaFin = end.format('YYYY-MM-DD');
      this.nombreFile = "ReporteVentas_" + start.format('DD-MM-YYYY')+"AL"+end.format('DD-MM-YYYY');
      this.fetchSalesList();
    },

    fetchSalesList() {
      axios
        .post("/api/ventas/reporteventas", { desde: this.fechaInicio, hasta: this.fechaFin })
        .then((response) => {

          this.ventas = response.data;

          if (this.dataTable) {
            var tablaVentas = $('#tblreporteVentas').DataTable();
            tablaVentas.destroy();
          }

          setTimeout(() => {
            this.inicializarDataTable();
          }, 10);

        })
        .catch((error) => {
          console.error("Error al obtener la lista", error);
        });
    },
  },
};
</script>

<style>
/* Estilos personalizados si es necesario */
</style>
