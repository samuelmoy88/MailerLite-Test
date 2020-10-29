<template>
    <div class="create">
        <NavBar/>
        <div class="container">
            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h3>Create Subscriber</h3>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    <a href="/subscribers">
                        <b-icon icon="box-arrow-in-left"></b-icon>
                        Go back</a>
                </div>
            </div>
            <form action="/subscribers/" method="post" class="form-horizontal">
                <div class="row">
                    <div class="col">
                        <div class="form-group text-left">
                            <label for="name" class="control-label">Full name</label>
                            <input type="text" class="form-control" name="name" id="name" v-model="form.name">
                        </div>
                        <div class="form-group text-left">
                            <label for="email" class="control-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" v-model="form.email">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group text-left">
                            <label for="state" class="control-label">State</label>
                            <select name="state" id="state" class="form-control" v-model="form.state">
                                <option :key="state.id" v-for="state in states"
                                        :value="state.id">
                                    {{ state.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-start">
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
                    name: null,
                    email: null,
                    state: null,
                },
                states: [
                    {
                        id: 1,
                        label: 'Active'
                    },
                    {
                        id: 2,
                        label: 'Unsubscribed'
                    },
                    {
                        id: 3,
                        label: 'Junk'
                    },
                    {
                        id: 4,
                        label: 'Bounced'
                    },
                    {
                        id: 5,
                        label: 'Unconfirmed'
                    },
                ],
                loading: false
            }
        },
        methods: {
            create() {
                this.loading = true;

                Axios.post(`http://api.mailerlite.localhost/subscribers`, this.form)
                    .then(response => {
                        if (response.data.code == 202) {
                            this.makeToast(
                                'Success',
                                'Subscriber created successfully',
                                'success'
                            );
                            setTimeout(() => {
                                this.$router.push(
                                    `/subscribers/${response.data.subscriber.id}/edit`
                                );
                            }, 2000);

                        }
                        this.loading = false;
                    }).catch(error => {
                    this.loading = false;
                    this.makeToast(
                        'Warning',
                        error.response.data.message,
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
        },
    }
</script>

<style scoped>

</style>