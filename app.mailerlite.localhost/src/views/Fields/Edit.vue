<template>
    <div class="edit">
        <NavBar/>
        <div class="container">
            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h3>Edit Field</h3>
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
                <div class="row" v-if="form.beingUsed">
                    <div class="col">
                        <div class="alert alert-primary" role="alert">
                            This field has subscribers using it!
                        </div>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" v-on:click="edit(form.id)">
                        <div v-if="loading" class="spinner-border spinner-border-sm"></div>
                        <span v-if="loading" class="px-1">Saving</span>
                        <span v-else>Save</span>
                    </button>
                    <button type="button" class="btn btn-danger" v-on:click="destroy(form.id)">Delete</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import NavBar from "../../components/NavBar";
    import Axios from "axios";
    export default {
        name: "Edit",
        components: {NavBar},
        data() {
            return {
                id: null,
                form: {
                    id: null,
                    title: null,
                    type: null,
                    beingUsed: false,
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
            edit(id) {
                this.loading = true;

                Axios.put(`http://api.mailerlite.localhost/fields/${id}`, this.form)
                    .then(response => {
                        let toastTitle = 'An error has occurred';
                        let toastMessage = 'There has been an error updating this Field';
                        let toastType = 'danger';

                        if (response.data.code === 200) {
                            toastTitle = 'Success';
                            toastMessage = 'Field updated successfully';
                            toastType = 'success';

                        }
                        this.loading = false;
                        this.makeToast(
                            toastTitle,
                            toastMessage,
                            toastType
                        );
                    });
            },
            destroy(id) {
                if (this.form.beingUsed) {
                    this.makeToast(
                        'Warning',
                        'This Field is being used by subscribers, delete it from them first',
                        'danger'
                    );
                    return;
                }
                Axios.delete(`http://api.mailerlite.localhost/fields/${id}`)
                    .then(response => {
                        if (response.data.code === 200) {
                            this.makeToast(
                                'Success',
                                'Field successfully deleted',
                                'success'
                            );
                            setTimeout(() => {
                                this.$router.push('/fields');
                            }, 1000)

                        }

                    }).catch(() => {
                    this.loading = false;
                    this.makeToast(
                        'Warning',
                        'An error has occurred deleting this Field',
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
            },
        },
        mounted: function () {
            this.form.id = this.$route.params.field;
            Axios.get(`http://api.mailerlite.localhost/fields/${this.form.id}`)
                .then(response => {
                    this.form.title = response.data.field.title;
                    this.form.type = response.data.field.type;
                    this.form.beingUsed = response.data.field.is_used;
                }).catch(() => {
                this.makeToast(
                    'Warning',
                    'An error has occurred fetching this Field',
                    'danger'
                );
            });
        }
    }
</script>

<style scoped>

</style>