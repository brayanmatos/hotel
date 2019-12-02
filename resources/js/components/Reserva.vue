<template>
  <v-container fluid grid-list-lg text-xs-center>
    <v-layout row wrap>
      <v-flex xs12 sm12 md12>
        <v-card>
          <v-toolbar flat color="white">
            <v-toolbar-title>RESERVAS</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="500px">
              <template v-slot:activator="{ on }">
                <v-btn color="primary" dark class="mb-2" v-on="on">Nueva Reserva</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">
                    {{
                    formTitle
                    }}
                  </span>
                </v-card-title>

                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12 sm12 md12>
                        <v-text-field v-model="editedItem.fecha" label="Fecha"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12>
                        <v-select
                          v-model="editedItem.cliente_id"
                          v-bind:items="clientes"
                          attach
                          chips
                          label="Cliente"
                          item-text="nombres"
                          item-value="id"
                        ></v-select>
                      </v-flex>
                      <v-flex xs12 sm12 md12>
                        <v-flex xs12 sm12 md12>
                          <v-select
                            v-model="editedItem.habitacion_id"
                            v-bind:items="habitaciones"
                            attach
                            chips
                            label="Habitacion"
                            item-text="codigo"
                            item-value="id"
                          ></v-select>
                        </v-flex>
                      </v-flex>
                      <v-flex xs12 sm12 md12>
                        <v-text-field v-model="editedItem.dias" label="Dias"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12>
                        <v-text-field v-model="editedItem.total" label="Total"></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="error darken-1" flat @click="close">Cancel</v-btn>
                  <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
          <v-data-table :headers="headers" :items="reservas" class="elevation-1">
            <template v-slot:items="props">
              <td class="text-xs-left">{{ props.item.fecha }}</td>
              <td class="text-xs-left">{{ props.item.cliente.nombres }}</td>
              <td class="text-xs-left">{{ props.item.habitacion.codigo }}</td>
              <td class="text-xs-left">{{ props.item.dias }}</td>
              <td class="text-xs-left">{{ props.item.total }}</td>

              <!-- <td class="justify-right layout px-0">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem(props.item)"
                                    >edit</v-icon
                                >
                                <v-icon small @click="deleteItem(props.item)"
                                    >delete</v-icon
                                >
              </td>-->
            </template>
            <template slot="no-data">
              <v-alert
                :value="true"
                color="info"
                icon="warning"
                style="margin: 1.5em 0"
              >Aún no se han agregado reservas :(</v-alert>
            </template>
          </v-data-table>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    headers: [
      { text: "Fecha", value: "fecha" },
      { text: "Cliente", value: "nombres" },
      { text: "Habitacion", value: "codigo" },
      { text: "Dias", value: "dias" },
      { text: "Total", value: "total" }

      // { text: "Actions", value: "actions", sortable: false }
    ],
    reservas: [],
    clientes: [],
    habitaciones: [],
    editedIndex: -1,
    editedItem: {
      fecha: "",
      cliente_id: "",
      habitacion_id: "",
      dias: "",
      total: ""
    },
    defaultItem: {
      fecha: "",
      cliente_id: "",
      habitacion_id: "",
      dias: "",
      total: ""
    }
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Nueva Reserva" : "Editar Reserva";
    }
  },
  watch: {
    dialog(val) {
      val || this.close();
    }
  },
  created() {
    this.initialize();
  },
  mounted() {
    axios.get("/api/reservas").then(({ data }) => {
      this.reservas = data;
    });
  },
  methods: {
    initialize() {
      this.reset();
      this.close();
      this.getclientes();
      this.gethabitaciones();
      this.getreservas();
    },
    getreservas() {
      axios.get("/api/reservas").then(({ data }) => {
        this.reservas = data;
      });
    },
    getclientes() {
      axios.get("/api/clientes").then(({ data }) => {
        this.clientes = data;
      });
    },
    gethabitaciones() {
      axios.get("/api/habitaciones").then(({ data }) => {
        this.habitaciones = data;
      });
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    reset() {
      this.editedItem["fecha"] = "";
      this.editedItem["cliente_id"] = "";
      this.editedItem["habitacion_id"] = "";
      this.editedItem["dias"] = "";
      this.editedItem["total"] = "";
    },
    save() {
      if (!this.editedItem.fecha) {
        notify.error("Ingrese fecha");
        return;
      }
      if (!this.editedItem.cliente_id) {
        notify.error("Ingrese cliente ");
        return;
      }

      if (!this.editedItem.habitacion_id) {
        notify.error("Ingrese habitacion ");
        return;
      }

      if (!this.editedItem.dias) {
        notify.error("Ingrese dias ");
        return;
      }

      const data = {
        fecha: this.editedItem.fecha,
        cliente_id: this.editedItem.cliente_id,
        habitacion_id: this.editedItem.habitacion_id,
        dias: this.editedItem.dias,
        total: this.editedItem.total
      };
      axios
        .post("/api/reservas/store", data)
        .then(({ data }) => {
          notify.showCool(data.message);
          this.initialize();
        })
        .catch(error => {
          console.log("Entro aqui");
          notify.error(error.response.data.message);
          // notify.error("Ocurrio un problema");
        });
    }
  }
};
</script>
