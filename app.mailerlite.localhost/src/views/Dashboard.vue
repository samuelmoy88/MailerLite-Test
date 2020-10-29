<template>
    <div class="dashboard">
        <NavBar/>
        <div class="container">

            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h2>{{ label }}</h2>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    <button class="btn btn-primary" v-on:click="createSubscriber()">
                        <b-icon icon="plus-circle"></b-icon>
                        New Subscriber
                    </button>
                </div>
            </div>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">Full name</th>
                        <th scope="col">Email</th>
                        <th scope="col">State</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="subscriber in subscribers" :key="subscriber.id" v-on:click="edit(subscriber.id)">
                        <td>{{ subscriber.name }}</td>
                        <td>{{ subscriber.email }}</td>
                        <td>{{ subscriber.state_label }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import NavBar from "../components/NavBar";
    import Axios from "axios";

    export default {
        name: "Dashboard",
        components: {
            NavBar
        },
        methods:{
            edit(id){
                this.$router.push(`subscribers/${id}/edit`);
            },
            createSubscriber(){
                this.$router.push(`subscribers/create`);
            }
        },
        mounted: function () {
            Axios.get("http://api.mailerlite.localhost/subscribers")
                .then(response => {
                    this.subscribers = response.data.subscribers;
                });
        },
        data() {
            return {
                subscribers: null,
                label: "Subscribers list"
            }
        }
    }
</script>

<style scoped>

</style>