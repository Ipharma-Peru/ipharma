<template>
  <div
    class="modal fade"
    id="pagoPopup"
    tabindex="-1"
    aria-labelledby="pagoLabel"
    aria-hidden="true"
    ref="modalPago"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="pagoLabel">Realizar Pago</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="">
            <h4>
              Pagar: <span class="badge bg-secondary">S/ {{ total }}</span>
            </h4>
          </div>
          <div class="mb-3">
            <label for="monto-pago" class="col-form-label"
              >Monto de Pago:</label
            >
            <input
              type="text"
              class="form-control"
              id="monto-pago"
              v-model="montoPago"
              @input="calculateRest"
            />
          </div>
          <div class="mb-3">
            <h4>
              Vuelto: <span class="badge bg-success">S/ {{ restaMonto }}</span>
            </h4>
          </div>
          <div class="mb-3 form-check">
            <input
              type="checkbox"
              class="form-check-input"
              id="pago-tarjeta"
              v-model="pagoConTarjeta"
            />
            <label class="form-check-label" for="pago-tarjeta"
              >Pago con Tarjeta</label
            >
          </div>
          <button type="button" class="btn btn-primary" @click="procesarPago">
            Procesar Pago
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import printer_plugin from "@abrazasoft/thermal_printer_vuejs";
// import './js.js';
// import ConectorPluginV3 from './js.js';

export default {
  props: {
    datos: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      montoPago: 0,
      restaMonto: 0,
      pagoConTarjeta: false,
      total: 0,
      header: {
        rasonSocial: "GUTIERREZ CACERES NATALY",
        nombreComercial: "FARMA MAS VIDA",
        giroNegocio: "FARMACIA",
        ruc: "10475729094",
        DomFisacl: "LIMA LA VICTORIA URB. BALCONCILLO AV. PALERMO NRO 518",
        telefono: "",
        tipoVenta: "",
        correlativo: "",
        sucursal: "",
        fechaHora: "",
      },
      cliente: {
        nombre: "",
        tipoDoc: "",
        dirección: "",
      },
    };
  },
  created() {
    this.calculateTotal(); // Calcula el total al crear el componente
  },
  watch: {
    datos: {
      handler() {
        this.calculateTotal(); // Calcula el total cuando se actualizan los datos
      },
      deep: true,
    },
  },
  methods: {
    calculateRest() {
      this.restaMonto = (this.montoPago - this.total).toFixed(2);
    },
    procesarPago() {
      console.log(this.datos);
      axios
        .post("/api/ventas/registrar", this.datos)
        .then((response) => {
          const toastParams = {
            duration: 3000,
            close: true,
            gravity: "bottom",
            position: "left",
          };
          if (response.data.status) {
            // this.imprimir();
            console.log(response.data.ticket);
            console.log(response.data);
            this.imprimirTabla("SAT22TUS", response.data.ticket);
            Toastify({
              text: "¡Guardado!",
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
            }).showToast();
            this.$emit("pagoRealizado");
            const modal = document.getElementById("pagoPopup");
            const modalInstance = bootstrap.Modal.getInstance(modal);
            modalInstance.hide();
          } else {
            Toastify({
              text: response.data.message,
              ...toastParams,
              style: {
                background: "linear-gradient(to right, #ff5733, #ff8f33)",
                color: "#fff",
              },
            }).showToast();
          }
        })
        .catch((error) => {
          // Manejar el error si ocurre
          console.error(error);
        });
    },
    calculateTotal() {
      let total = 0;
      if (this.datos && this.datos.items) {
        this.datos.items.forEach((item) => {
          const cantidad = item.cantidad;
          const precio = item.precio;
          total += parseFloat(cantidad * precio);
        });
      }
      this.total = total.toFixed(2);
    },
    separarCadenaEnArregloSiSuperaLongitud(cadena, maximaLongitud) {
      const resultado = [];
      let indice = 0;
      while (indice < cadena.length) {
        const pedazo = cadena.substring(indice, indice + maximaLongitud);
        indice += maximaLongitud;
        resultado.push(pedazo);
      }
      return resultado;
    },
    dividirCadenasYEncontrarMayorConteoDeBloques(contenidosConMaximaLongitud) {
      let mayorConteoDeCadenasSeparadas = 0;
      const cadenasSeparadas = [];
      for (const contenido of contenidosConMaximaLongitud) {
        const separadas = this.separarCadenaEnArregloSiSuperaLongitud(
          contenido.contenido,
          contenido.maximaLongitud
        );
        cadenasSeparadas.push({
          separadas,
          maximaLongitud: contenido.maximaLongitud,
        });
        if (separadas.length > mayorConteoDeCadenasSeparadas) {
          mayorConteoDeCadenasSeparadas = separadas.length;
        }
      }
      return [cadenasSeparadas, mayorConteoDeCadenasSeparadas];
    },
    tabularDatos(cadenas, relleno, separadorColumnas) {
      const [
        arreglosDeContenidosConMaximaLongitudSeparadas,
        mayorConteoDeBloques,
      ] = this.dividirCadenasYEncontrarMayorConteoDeBloques(cadenas);
      let indice = 0;
      const lineas = [];
      while (indice < mayorConteoDeBloques) {
        let linea = "";
        for (const contenidos of arreglosDeContenidosConMaximaLongitudSeparadas) {
          let cadena = "";
          if (indice < contenidos.separadas.length) {
            cadena = contenidos.separadas[indice];
          }
          if (cadena.length < contenidos.maximaLongitud) {
            cadena =
              cadena +
              relleno.repeat(contenidos.maximaLongitud - cadena.length);
          }
          linea += cadena + separadorColumnas;
        }
        lineas.push(linea);
        indice++;
      }
      return lineas;
    },
    async imprimirTabla(
      nombreImpresora,
      { detalle, emisor, comprobante, cliente }
    ) {
      const ConectorPluginV3 = (() => {
        class Operacion {
          constructor(nombre, argumentos) {
            this.nombre = nombre;
            this.argumentos = argumentos;
          }
        }

        class ConectorPlugin {
          static URL_PLUGIN_POR_DEFECTO = "http://localhost:8000";
          static Operacion = Operacion;
          static TAMAÑO_IMAGEN_NORMAL = 0;
          static TAMAÑO_IMAGEN_DOBLE_ANCHO = 1;
          static TAMAÑO_IMAGEN_DOBLE_ALTO = 2;
          static TAMAÑO_IMAGEN_DOBLE_ANCHO_Y_ALTO = 3;
          static TAMAÑO_IMAGEN_DOBLE_ANCHO_Y_ALTO = 3;
          static ALINEACION_IZQUIERDA = 0;
          static ALINEACION_CENTRO = 1;
          static ALINEACION_DERECHA = 2;
          static RECUPERACION_QR_BAJA = 0;
          static RECUPERACION_QR_MEDIA = 1;
          static RECUPERACION_QR_ALTA = 2;
          static RECUPERACION_QR_MEJOR = 3;

          constructor(ruta, serial) {
            if (!ruta) ruta = ConectorPlugin.URL_PLUGIN_POR_DEFECTO;
            if (!serial) serial = "";
            this.ruta = ruta;
            this.serial = serial;
            this.operaciones = [];
            return this;
          }

          CargarImagenLocalEImprimir(ruta, tamaño, maximoAncho) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "CargarImagenLocalEImprimir",
                Array.from(arguments)
              )
            );
            return this;
          }
          Corte(lineas) {
            this.operaciones.push(
              new ConectorPlugin.Operacion("Corte", Array.from(arguments))
            );
            return this;
          }
          CorteParcial() {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "CorteParcial",
                Array.from(arguments)
              )
            );
            return this;
          }
          DefinirCaracterPersonalizado(caracterRemplazo, matriz) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "DefinirCaracterPersonalizado",
                Array.from(arguments)
              )
            );
            return this;
          }
          DescargarImagenDeInternetEImprimir(urlImagen, tamaño, maximoAncho) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "DescargarImagenDeInternetEImprimir",
                Array.from(arguments)
              )
            );
            return this;
          }
          DeshabilitarCaracteresPersonalizados() {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "DeshabilitarCaracteresPersonalizados",
                Array.from(arguments)
              )
            );
            return this;
          }
          DeshabilitarElModoDeCaracteresChinos() {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "DeshabilitarElModoDeCaracteresChinos",
                Array.from(arguments)
              )
            );
            return this;
          }
          EscribirTexto(texto) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EscribirTexto",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerAlineacion(alineacion) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerAlineacion",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerEnfatizado(enfatizado) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerEnfatizado",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerFuente(fuente) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerFuente",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerImpresionAlReves(alReves) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerImpresionAlReves",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerImpresionBlancoYNegroInversa(invertir) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerImpresionBlancoYNegroInversa",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerRotacionDe90Grados(rotar) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerRotacionDe90Grados",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerSubrayado(subrayado) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerSubrayado",
                Array.from(arguments)
              )
            );
            return this;
          }
          EstablecerTamañoFuente(multiplicadorAncho, multiplicadorAlto) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "EstablecerTamañoFuente",
                Array.from(arguments)
              )
            );
            return this;
          }
          Feed(lineas) {
            this.operaciones.push(
              new ConectorPlugin.Operacion("Feed", Array.from(arguments))
            );
            return this;
          }
          HabilitarCaracteresPersonalizados() {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "HabilitarCaracteresPersonalizados",
                Array.from(arguments)
              )
            );
            return this;
          }
          HabilitarElModoDeCaracteresChinos() {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "HabilitarElModoDeCaracteresChinos",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasCodabar(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasCodabar",
                Array.from(arguments)
              )
            );
            return this;
          }

          ImprimirCodigoDeBarrasCode128(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasCode128",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasCode39(
            contenido,
            incluirSumaDeVerificacion,
            modoAsciiCompleto,
            alto,
            ancho,
            tamañoImagen
          ) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasCode39",
                Array.from(arguments)
              )
            );
            return this;
          }

          ImprimirCodigoDeBarrasCode93(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasCode93",
                Array.from(arguments)
              )
            );
            return this;
          }

          ImprimirCodigoDeBarrasEan(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasEan",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasEan8(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasEan8",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasPdf417(
            contenido,
            nivelSeguridad,
            alto,
            ancho,
            tamañoImagen
          ) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasPdf417",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasTwoOfFiveITF(
            contenido,
            intercalado,
            alto,
            ancho,
            tamañoImagen
          ) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasTwoOfFiveITF",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasUpcA(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasUpcA",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoDeBarrasUpcE(contenido, alto, ancho, tamañoImagen) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoDeBarrasUpcE",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirCodigoQr(
            contenido,
            anchoMaximo,
            nivelRecuperacion,
            tamañoImagen
          ) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirCodigoQr",
                Array.from(arguments)
              )
            );
            return this;
          }
          ImprimirImagenEnBase64(
            imagenCodificadaEnBase64,
            tamaño,
            maximoAncho
          ) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "ImprimirImagenEnBase64",
                Array.from(arguments)
              )
            );
            return this;
          }

          Iniciar() {
            this.operaciones.push(
              new ConectorPlugin.Operacion("Iniciar", Array.from(arguments))
            );
            return this;
          }

          Pulso(pin, tiempoEncendido, tiempoApagado) {
            this.operaciones.push(
              new ConectorPlugin.Operacion("Pulso", Array.from(arguments))
            );
            return this;
          }

          TextoSegunPaginaDeCodigos(numeroPagina, pagina, texto) {
            this.operaciones.push(
              new ConectorPlugin.Operacion(
                "TextoSegunPaginaDeCodigos",
                Array.from(arguments)
              )
            );
            return this;
          }

          static async obtenerImpresoras(ruta) {
            if (ruta) ConectorPlugin.URL_PLUGIN_POR_DEFECTO = ruta;
            const response = await fetch(
              ConectorPlugin.URL_PLUGIN_POR_DEFECTO + "/impresoras"
            );
            return await response.json();
          }

          static async obtenerImpresorasRemotas(ruta, rutaRemota) {
            if (ruta) ConectorPlugin.URL_PLUGIN_POR_DEFECTO = ruta;
            const response = await fetch(
              ConectorPlugin.URL_PLUGIN_POR_DEFECTO +
                "/reenviar?host=" +
                rutaRemota
            );
            return await response.json();
          }

          async imprimirEnImpresoraRemota(nombreImpresora, rutaRemota) {
            const payload = {
              operaciones: this.operaciones,
              nombreImpresora,
              serial: this.serial,
            };
            const response = await fetch(
              this.ruta + "/reenviar?host=" + rutaRemota,
              {
                method: "POST",
                body: JSON.stringify(payload),
              }
            );
            return await response.json();
          }

          async imprimirEn(nombreImpresora) {
            const payload = {
              operaciones: this.operaciones,
              nombreImpresora,
              serial: this.serial,
            };
            const response = await fetch(this.ruta + "/imprimir", {
              method: "POST",
              body: JSON.stringify(payload),
            });
            return await response.json();
          }
        }

        return ConectorPlugin;
      })();
      const maximaLongitudNombre = parseInt(20),
        maximaLongitudCantidad = parseInt(5),
        maximaLongitudCodigo = parseInt(8),
        maximaLongitudImporte = parseInt(5),
        maximaLongitudPrecio = parseInt(5),
        relleno = " ",
        separadorColumnas = "|";
      const obtenerLineaSeparadora = () => {
        const lineasSeparador = this.tabularDatos(
          [
            { contenido: "-", maximaLongitud: maximaLongitudCodigo },
            { contenido: "-", maximaLongitud: maximaLongitudNombre },
            { contenido: "-", maximaLongitud: maximaLongitudCantidad },
            { contenido: "-", maximaLongitud: maximaLongitudPrecio },
            { contenido: "-", maximaLongitud: maximaLongitudImporte },
          ],
          "-",
          "+"
        );
        let separadorDeLineas = "";
        if (lineasSeparador.length > 0) {
          separadorDeLineas = lineasSeparador[0];
        }
        return separadorDeLineas;
      };
      // Comenzar a diseñar la tabla
      let tabla = obtenerLineaSeparadora() + "\n";

      const lineasEncabezado = this.tabularDatos(
        [
          { contenido: "COD", maximaLongitud: maximaLongitudCodigo },
          { contenido: "DESC", maximaLongitud: maximaLongitudNombre },
          { contenido: "CANT", maximaLongitud: maximaLongitudCantidad },
          { contenido: "P.UNI", maximaLongitud: maximaLongitudPrecio },
          { contenido: "IMPOR", maximaLongitud: maximaLongitudImporte },
        ],
        relleno,
        separadorColumnas
      );

      for (const linea of lineasEncabezado) {
        tabla += linea + "\n";
      }
      tabla += obtenerLineaSeparadora() + "\n";
      function eliminarTildes(texto) {
        return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
      }
      for (const producto of detalle) {
        // Eliminar tildes en el nombre del producto
        producto.descripcion = eliminarTildes(producto.descripcion);
        const lineas = this.tabularDatos(
          [
            {
              contenido: producto.codigo,
              maximaLongitud: maximaLongitudCodigo,
            },
            {
              contenido: producto.descripcion,
              maximaLongitud: maximaLongitudNombre,
            },
            {
              contenido: producto.cantidad.toString(),
              maximaLongitud: maximaLongitudCantidad,
            },
            {
              contenido: producto.precio_unitario.toString(),
              maximaLongitud: maximaLongitudPrecio,
            },
            {
              contenido: producto.importe_total.toString(),
              maximaLongitud: maximaLongitudImporte,
            },
          ],
          relleno,
          separadorColumnas
        );
        for (const linea of lineas) {
          tabla += linea + "\n";
        }
        tabla += obtenerLineaSeparadora() + "\n";
      }
      console.log(tabla);

      let typeOperation = "";
      if (comprobante.total_opexoneradas > 0)
        typeOperation +=
          "OP EXONERADA: " + comprobante.total_opexoneradas.toString() + "\n";
      if (comprobante.total_opinafectas > 0)
        typeOperation +=
          "OP INAFECTA: " + comprobante.total_opinafectas.toString() + "\n";
      if (comprobante.total_opgravadas > 0)
        typeOperation +=
          "OP GRAVA: " + comprobante.total_opgravadas.toString() + "\n";
      let clienteQR = "|";
      if (cliente.tipodoc != 0) {
        clienteQR = `| ${cliente.tipodoc} | ${cliente.ruc} |`;
      }

      const codeQR = `${emisor.ruc} |  ${comprobante.tipodoc} | ${comprobante.serie} | ${comprobante.correlativo} | ${comprobante.igv} | ${comprobante.total} | ${comprobante.fecha_emision} ${clienteQR} `;
      console.log(codeQR);
      const conector = new ConectorPluginV3("http://localhost:8000");
      const fechaHoraActual = new Date().toLocaleString("es-ES", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
      conector
        .Iniciar()
        .CorteParcial()
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
        .Feed(1)
        .EscribirTexto("FARMA + VIDA\n")
        .EscribirTexto("BOTICAS\n")
        .EscribirTexto("R.U.C." + emisor.ruc + " \n")
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
        .EscribirTexto(
          "Domicilio Fiscal: " +
            emisor.direccion +
            " " +
            emisor.distrito +
            " " +
            emisor.provincia +
            " \n"
        )
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
        .TextoSegunPaginaDeCodigos(2, "cp850", "TELEFONO: 0000000\n")
        .EscribirTexto("BOLETA DE VENTA ELECTRONICA" + " \n")
        .EscribirTexto(comprobante.serie + "-" + comprobante.tipodoc + " \n")
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
        .EscribirTexto(
          "Sucursal: " +
            emisor.direccion +
            " " +
            emisor.distrito +
            " " +
            emisor.provincia +
            " \n"
        )
        .EscribirTexto("FECHA DE EMISION: " + fechaHoraActual + " \n")
        .EscribirTexto("CLIENTE: " + cliente.razon_social + " \n")
        .EscribirTexto("NRO DOC: " + cliente.ruc + " \n")
        .EscribirTexto(
          "DIRECCION: " + (cliente.direccion == null)
            ? ""
            : cliente.direccion + " \n"
        )
        .Feed(1)
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_IZQUIERDA)
        .Feed(1)
        .EscribirTexto(tabla)
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_DERECHA)
        .EscribirTexto("TOTAL A PAGAR: " + comprobante.total + " \n")
        .EscribirTexto("-----------------------------------------" + " \n")
        .EscribirTexto(typeOperation)
        .EscribirTexto("IGV: " + comprobante.igv + "\n")
        .EscribirTexto("-----------------------------------------" + " \n")
        .EscribirTexto("IMPORTE TOTAL: " + comprobante.total + " \n")
        .EscribirTexto("SON: " + comprobante.total_texto + " \n")
        .Feed(1)
        .EstablecerAlineacion(ConectorPluginV3.ALINEACION_CENTRO)
        .ImprimirCodigoQr(
          codeQR,
          160,
          ConectorPluginV3.RECUPERACION_QR_MEJOR,
          ConectorPluginV3.TAMAÑO_IMAGEN_DOBLE_ANCHO_Y_ALTO
        )
        .EscribirTexto("Guarda tu voucher. Es el sustento para validar tu compra. Representacion impresa de la BOLETA ELECTRONICA, puede ser consultada en: https://www.efact.pe/consult.html " + " \n")
        .Feed(5)
        .CorteParcial();
      const respuesta = await conector.imprimirEn(nombreImpresora);
      if (respuesta === true) {
        // alert("Impreso correctamente");
      } else {
        alert("Error: " + respuesta);
      }
    },
  },
};
</script>
