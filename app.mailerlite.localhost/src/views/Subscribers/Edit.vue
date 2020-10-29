<template>
    <div class="edit">
        <NavBar/>
        <div class="container">
            <div class="row mb-3">
                <div class="col-sm-8 col-xs-12">
                    <h3>Edit Subscriber</h3>
                </div>
                <div class="col-sm-4 col-xs-12 text-right">
                    <a href="/subscribers">
                        <b-icon icon="box-arrow-in-left"></b-icon>
                        Go back</a>
                </div>
            </div>
            <form action="/subscribers/:subscriber.id/edit" class="">
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
                        <div class="form-group text-left">
                            <label for="state" class="control-label">State</label>
                            <select name="state" id="state" class="form-control" v-model="form.state">
                                <option :key="state.id" v-for="state in states"
                                        :value="state.id" :selected="state.id == form.state">
                                    {{ state.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group text-left">
                            <label for="state" class="control-label">Fields</label>
                            <div class="fields">
                                <form v-if="form.fields">
                                    <div class="row align-items-end form-group" :key="field.id" v-for="field in form.fields">
                                        <div class="text-left col">
                                            <input type="hidden" name="old_field" v-model="field.field_id">
                                            <label for="field_id" class="control-label">Field type</label>
                                            <select name="field_id" id="field_id" class="form-control"
                                                    v-model="field.field_id">
                                                <option :key="fieldTypes.id" v-for="fieldTypes in fields"
                                                        :value="fieldTypes.id"
                                                        :selected="fieldTypes.id == field.field_id">
                                                    {{ fieldTypes.title }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="text-left col">
                                            <label for="value" class="control-label">Value</label>
                                            <input type="text" class="form-control" name="value" id="value"
                                                   v-model="field.value">
                                        </div>
                                        <div class="text-left col">
                                            <button type="button" class="btn btn-primary mr-3" v-on:click="editSubscriberField(form.id, field.field_id, field.value, field.id)">Edit</button>
                                            <button type="button" class="btn btn-danger" v-on:click="deleteSubscriberField(form.id, field.id)">Delete</button>
                                        </div>
                                    </div>
                                </form>
                                <form>
                                    <div class="row align-items-end">
                                        <div class="text-left col">
                                            <label for="subscriber_field" class="control-label">Field type</label>
                                            <select required name="field_id" id="subscriber_field" class="form-control"
                                                    v-model="subscriberFields.field_id">
                                                <option :key="fieldTypes.id" v-for="fieldTypes in fields"
                                                        :value="fieldTypes.id">
                                                    {{ fieldTypes.title }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="text-left col">
                                            <label for="subscriber_value" class="control-label">Value</label>
                                            <input required class="form-control" name="value" id="subscriber_value"
                                                   v-model="subscriberFields.value"
                                                    >
                                        </div>
                                        <div class="text-left col">
                                            <button type="button" class="btn btn-primary" v-on:click="addSubscriberField(form.id)">Add field</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                    name: null,
                    email: null,
                    state: null,
                    fields: []
                },
                fields: [],
                subscriberFields: {
                    subscriber_id: null,
                    field_id: null,
                    value: null,
                },
                fieldMetaData:[
                    {
                        type: 'date',
                        id: '1',
                    },
                    {
                        type: 'text',
                        id: '3',
                    },
                ],
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
            edit(id) {
                this.loading = true;
                Axios.put(`http://api.mailerlite.localhost/subscribers/${id}`, this.form)
                    .then(response => {
                        let toastTitle = 'An error has occurred';
                        let toastMessage = 'There has been an error updating this subscriber';
                        let toastType = 'danger';

                        if (response.data.code === 200) {
                            toastTitle = 'Success';
                            toastMessage = 'Subscriber updated successfully';
                            toastType = 'success';

                        }
                        this.loading = false;

                        this.makeToast(
                            toastTitle,
                            toastMessage,
                            toastType
                        );
                    }).catch(error => {
                    this.loading = false;
                    this.makeToast(
                        'Warning',
                        error.response.data.message,
                        'danger'
                    );
                });
            },
            destroy(id) {
                Axios.delete(`http://api.mailerlite.localhost/subscribers/${id}`)
                    .then(response => {
                        if (response.data.code === 200) {
                            this.makeToast(
                                'Success',
                                'Subscriber deleted successfully',
                                'success'
                            );
                            setTimeout(() => {
                                this.$router.push('/subscribers');
                            }, 1000)

                        }

                    });
            },
            addSubscriberField(subscriberId){
                if (!this.subscriberFields.value ||
                    !this.subscriberFields.field_id ) {
                    this.makeToast(
                        'Warning',
                        'An error has occurred adding the field to this subscriber',
                        'danger'
                    );
                    return;
                }
                Axios.post(`http://api.mailerlite.localhost/subscribers/${subscriberId}/fields`, this.subscriberFields)
                    .then(response => {

                        if (response.data.code === 202) {
                            this.makeToast(
                                'Success',
                                'Subscriber field added successfully',
                                'success'
                            );
                            setTimeout(() => {
                                location.reload();
                            },500);

                        }
                    }).catch(() => {
                    this.makeToast(
                        'Warning',
                        'An error has occurred adding the field to this subscriber',
                        'danger'
                    );
                });
            },
            editSubscriberField(subscriberId, fieldId, value, id){
                let data = {
                    subscriber_id: subscriberId,
                    field_id: fieldId,
                    value: value
                };

                Axios.put(`http://api.mailerlite.localhost/subscribers/${subscriberId}/fields/${id}`, data)
                    .then(response => {

                        if (response.data.code === 200) {
                            this.makeToast(
                                'Success',
                                'Subscriber field updated successfully',
                                'success'
                            );

                        }
                    }).catch(() => {
                    this.makeToast(
                        'Warning',
                        'An error has occurred updating the field to this subscriber',
                        'danger'
                    );
                });
            },
            deleteSubscriberField(subscriberId, id){
                Axios.delete(`http://api.mailerlite.localhost/subscribers/${subscriberId}/fields/${id}`,)
                    .then(response => {

                        if (response.data.code === 200) {
                            this.makeToast(
                                'Success',
                                'Subscriber field deleted successfully',
                                'success'
                            );
                            setTimeout(() => {
                                location.reload();
                            },500);
                        }
                    }).catch(() => {
                    this.makeToast(
                        'Warning',
                        'An error has occurred deleting the field to this subscriber',
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
            this.form.id = this.$route.params.subscriber;
            this.subscriberFields.subscriber_id = this.$route.params.subscriber;
            Axios.get(`http://api.mailerlite.localhost/subscribers/${this.form.id}`, {})
                .then(response => {
                    this.form.name = response.data.subscriber.name;
                    this.form.email = response.data.subscriber.email;
                    this.form.state = response.data.subscriber.state;
                    this.form.fields = response.data.subscriber.fields;
                });

            Axios.get("http://api.mailerlite.localhost/fields")
                .then(response => {
                    this.fields = response.data.fields;
                });
        }
    }
</script>

<style scoped>

</style>