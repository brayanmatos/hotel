<template>
    <v-container fluid grid-list-lg text-xs-center>
        <v-layout row wrap>
            <v-flex xs12 sm12 md12>
                <v-card>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>HABITACIONES</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    color="primary"
                                    dark
                                    class="mb-2"
                                    v-on="on"
                                    >Nueva Habitacion</v-btn
                                >
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{
                                        formTitle
                                    }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="editedItem.codigo"
                                                    label="Codigo"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="
                                                        editedItem.descripcion
                                                    "
                                                    label="Descripcion"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="editedItem.estado"
                                                    label="Estado"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="editedItem.precio"
                                                    label="Precio"
                                                ></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="error darken-1"
                                        flat
                                        @click="close"
                                        >Cancel</v-btn
                                    >
                                    <v-btn
                                        color="blue darken-1"
                                        flat
                                        @click="save"
                                        >Save</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                    <v-data-table
                        :headers="headers"
                        :items="habitaciones"
                        class="elevation-1"
                    >
                        <template v-slot:items="props">
                            <td class="text-xs-left">
                                {{ props.item.codigo }}
                            </td>
                            <td class="text-xs-left">
                                {{ props.item.descripcion }}
                            </td>
                            <td class="text-xs-left">
                                {{ props.item.estado }}
                            </td>

                            <td class="text-xs-left">
                                {{ props.item.precio }}
                            </td>

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
                            </td> -->
                        </template>
                        <template slot="no-data">
                            <v-alert
                                :value="true"
                                color="info"
                                icon="warning"
                                style="margin: 1.5em 0"
                                >Aún no se han agregado habitaciones :(</v-alert
                            >
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
            { text: "Codigo", value: "codigo" },
            { text: "Descripcion", value: "descripcion" },
            { text: "Estado", value: "estado" },
            { text: "Precio", value: "precio" },
            { text: "Actions", value: "actions", sortable: false }
        ],
        habitaciones: [],
        editedIndex: -1,
        editedItem: {
            codigo: "",
            descripcion: "",
            estado: "",
            precio: ""
        },
        defaultItem: {
            codigo: "",
            descripcion: "",
            estado: "",
            precio: ""
        }
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "Nuevo Cliente" : "Editar Cliente";
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
        axios.get("/api/habitaciones").then(({ data }) => {
            this.habitaciones = data;
        });
    },
    methods: {
        initialize() {
            this.reset();
            this.close();
            this.gethabitaciones();
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
            this.editedItem["codigo"] = "";
            this.editedItem["descripcion"] = "";
            this.editedItem["estado"] = "";
            this.editedItem["precio"] = "";
        },
        save() {
            if (!this.editedItem.dni) {
                notify.error("Ingrese codigo");
                return;
            }
            if (!this.editedItem.descripcion) {
                notify.error("Ingrese descripcion ");
                return;
            }

            if (!this.editedItem.estado) {
                notify.error("Ingrese estado ");
                return;
            }

            if (!this.editedItem.precio) {
                notify.error("Ingrese precio ");
                return;
            }

            const data = {
                codigo: this.editedItem.codigo,
                descripcion: this.editedItem.descripcion,
                estado: this.editedItem.estado,
                precio: this.editedItem.precio
            };
            axios
                .post("/api/habitaciones/store", data)
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
