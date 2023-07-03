<template>
  <div class="container">
    <button @click="getPrinters">Mostrar Impresoras</button><br /><br /><br />
    <button @click="imprimir">Imprimir</button>
  </div>
</template>

<script>
import printer_plugin from "@abrazasoft/thermal_printer_vuejs";
export default {
  methods: {
    getPrinters() {
      printer_plugin.obtenerImpresoras().then((printers) => {
        console.log(printers);
      });
    },
    async imprimir() {
      var nombreImpresora = "SAT 22TUS";
      var api_key = "123456";

      if (!nombreImpresora) return;

      const conector = new printer_plugin();
      conector.fontsize("2");
      conector.textaling("center");
      conector.text("Ferreteria Angel");
      conector.fontsize("1");
      conector.text("Calle de las margaritas #45854");
      conector.text("PEC7855452SCC");
      conector.feed("3");
      conector.textaling("left");
      conector.text("Fecha: Miercoles 8 de ABRIL 2022 13:50");
      conector.text("Cant.       Descripcion      Importe");
      conector.text("------------------------------------------");
      conector.text("1- Barrote 2x4x8                    $110");
      conector.text("3- Esquinero Vinil                  $165");
      conector.feed("1");
      conector.fontsize("2");
      conector.textaling("center");
      conector.text("Total: $275");
      conector.qr("https://abrazasoft.com");
      conector.feed("5");
      conector.cut("0");

      const resp = await conector.imprimir(nombreImpresora, api_key);

      if (resp === true) {
        console.log(resp);
      } else {
        console.log("Error: " + resp);
      }
    },
  },
};
</script>

<style>
.container {
  margin-top: 20px;
  text-align: center;
}
</style>
