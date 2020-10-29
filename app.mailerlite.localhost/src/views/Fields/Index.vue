<template>
    <div class="dashboard">
        <NavBar/>
        <div class="container">

            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h2>{{ label }}</h2>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    <button class="btn btn-primary" v-on:click="createField()">
                        <b-icon icon="plus-circle"></b-icon>
                        New Field
                    </button>
                </div>
            </div>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">Field</th>
                    <th scope="col">Type</th>
                    <th scope="col">Is being used</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="field in fields" :key="field.id" v-on:click="edit(field.id)">
                    <td>{{ field.title }}</td>
                    <td>{{ field.type_label }}</td>
                    <td>{{ field.is_used ? 'Yes' : 'No' }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import NavBar from "../../components/NavBar";
    import Axios from "axios";

    export default {
        name: "Dashboard",
        components: {
            NavBar
        },
        methods:{
            edit(id){
                this.$router.push(`fields/${id}/edit`);
            },
            createField(){
                this.$router.push(`fields/create`);
            }
        },
        mounted: function () {
            Axios.get("http://api.mailerlite.localhost/fields")
                .then(response => {
                    this.fields = response.data.fields;
                });
        },
        data() {
            return {
                fields: null,
                label: "Fields list"
            }
        }
    }
</script>

<style scoped>

</style>