<template>
    <div class="create">
        <NavBar/>
        <div class="container">
            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h3>Create Field</h3>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    <a href="/fields">
                        <b-icon icon="box-arrow-in-left"></b-icon>
                        Go back</a>
                </div>
            </div>
            <form action="/fields/" method="post" class="form-horizontal">
                <div class="row">
                    <div class="col">
                        <div class="form-group text-left">
                            <label for="title" class="control-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title" v-model="form.title">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group text-left">
                            <label for="type" class="control-label">Type</label>
                            <select name="type" id="type" class="form-control" v-model="form.type">
                                <option :key="type.id" v-for="type in types"
                                        :value="type.id">
                                    {{ type.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" v-on:click="create(form.id)">
                        <div v-if="loading" class="spinner-border spinner-border-sm"></div>
                        <span v-if="loading" class="px-1">Creating</span>
                        <span v-else>Create</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import NavBar from "../../components/NavBar";
    import Axios from "axios";

    export default {
        name: "Create",
        components: {NavBar},
        data() {
            return {
                form: {
                    id: null,
                    title: null,
                    type: null,
                },
                types: [
                    {
                        id: 1,
                        label: 'Date'
                    },
                    {
                        id: 2,
                        label: 'Number'
                    },
                    {
                        id: 3,
                        label: 'String'
                    },
                    {
                        id: 4,
                        label: 'Boolean'
                    },
                ],
                loading: false
            }
        },
        methods: {
            create() {
                this.loading = true;
                Axios.post(`http://api.mailerlite.localhost/fields`, this.form)
                    .then(response => {
                        if (response.data.code == 202) {
                            this.makeToast(
                                'Success',
                                'Field created successfully',
                                'success'
                            );
                            setTimeout(() => {
                                this.$router.push(
                                    `/fields/${response.data.field.id}/edit`
                                );
                            }, 2000);

                        }
                        this.loading = false;
                    }).catch(() => {
                    this.loading = false;
                    this.makeToast(
                        'Warning',
                        'An error has occurred creating the Field',
                        'danger'
                    );
                });
            },
            makeToast(title, text, type) {
                this.toastCount++
                this.$bvToast.toast(text, {
                    title: title,
                    variant: type,
                    autoHideDelay: 5000,
                    appendToast: true
                })
            }
        }
    }
</script>

<style scoped>

</style>