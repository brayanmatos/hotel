<template>
    <v-container fluid grid-list-lg text-xs-center>
        <v-layout row wrap>
            <v-flex xs12 sm12 md12>
                <v-card>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>CLIENTES</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    color="primary"
                                    dark
                                    class="mb-2"
                                    v-on="on"
                                    >Nuevo Cliente</v-btn
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
                                                    v-model="editedItem.dni"
                                                    label="Dni"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="editedItem.nombres"
                                                    label="Nombres"
                                                ></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field
                                                    v-model="
                                                        editedItem.apellidos
                                                    "
                                                    label="Apellidos"
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
                        :items="clientes"
                        class="elevation-1"
                    >
                        <template v-slot:items="props">
                            <td class="text-xs-left">{{ props.item.dni }}</td>
                            <td class="text-xs-left">
                                {{ props.item.nombres }}
                            </td>
                            <td class="text-xs-left">
                                {{ props.item.apellidos }}
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
                                >Aún no se han agregado clientes :(</v-alert
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
            { text: "Dni", value: "dni" },
            { text: "Nombres", value: "nombres" },
            { text: "Apellidos", value: "apellidos" },
            { text: "Actions", value: "actions", sortable: false }
        ],
        clientes: [],
        editedIndex: -1,
        editedItem: {
            dni: "",
            nombres: "",
            apellidos: ""
        },
        defaultItem: {
            dni: "",
            nombres: "",
            apellidos: ""
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
        axios.get("/api/clientes").then(({ data }) => {
            this.clientes = data;
        });
    },
    methods: {
        initialize() {
            this.reset();
            this.close();
            this.getclientes();
        },
        getclientes() {
            axios.get("/api/clientes").then(({ data }) => {
                this.clientes = data;
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
            this.editedItem["dni"] = "";
            this.editedItem["nombres"] = "";
            this.editedItem["apellidos"] = "";
        },
        save() {
            if (!this.editedItem.dni) {
                notify.error("Ingrese dni");
                return;
            }
            if (!this.editedItem.nombres) {
                notify.error("Ingrese nombres ");
                return;
            }

            if (!this.editedItem.apellidos) {
                notify.error("Ingrese apellidos ");
                return;
            }

            const data = {
                dni: this.editedItem.dni,
                nombres: this.editedItem.nombres,
                apellidos: this.editedItem.apellidos
            };
            axios
                .post("/api/clientes/store", data)
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
