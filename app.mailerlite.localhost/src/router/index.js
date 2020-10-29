import Vue from 'vue'
import VueRouter from 'vue-router'
import Dashboard from "../views/Dashboard";
import EditSubscriber from "../views/Subscribers/Edit";
import CreateSubscriber from "../views/Subscribers/Create";
import Fields from "../views/Fields/Index";
import CreateField from "../views/Fields/Create";
import EditField from "../views/Fields/Edit";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/subscribers',
    name: 'Subscribers',
    component: Dashboard
  },
  {
    path: '/subscribers/create',
    name: 'CreateSubscriber',
    component: CreateSubscriber
  },
  {
    path: '/subscribers/:subscriber/edit',
    name: 'EditSubscriber',
    component: EditSubscriber
  },
  {
    path: '/fields',
    name: 'Fields',
    component: Fields
  },
  {
    path: '/fields/create',
    name: 'CreateField',
    component: CreateField
  },
  {
    path: '/fields/:field/edit',
    name: 'EditField',
    component: EditField
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
