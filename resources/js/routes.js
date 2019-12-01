import Vue from "vue";
import VueRouter from "vue-router";

import Cliente from "./components/Cliente.vue";
import Habitacion from "./components/Habitacion.vue";
import Reserva from "./components/Reserva.vue";

Vue.use(VueRouter);

let routes = [
    {
        path: "/clientes",
        component: Cliente
    },
    {
        path: "/habitaciones",
        component: Habitacion
    },
    {
        path: "/reservas",
        component: Reserva
    }
];

export const router = new VueRouter({
    routes
});
