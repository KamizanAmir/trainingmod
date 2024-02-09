<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card pt-2 pb-2">
                    <div class="card-header">
                        <div style="text-align: left;">
                            <h1><strong>Employee List</strong></h1>
                                <h2 style="text-align: left;">
                                    <span style="text-align: left;">Total no. of employees : {{resultCount}}</span>
                                </h2>
                        </div>
                        <div class="card-tools mt-2">
                            <router-link to="/items-create" class="btn btn-success">
                                Add New
                                <i class="fas fa-layer-group"></i>
                                <i class="fas fa-plus-circle"></i>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th>Trainer name</th>
                                    <th>Training Item</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                <tr v-for="item in items" :key="item.id">
                                    <td>{{item.name | ucFirst}}</td>
                                    <td v-if="item.category">{{item.category.name}}</td>
                                    <td v-else class="text-danger">may be deleted</td>
                                    <td>{{item.created_at | dFormat}}</td>
                                    <td>{{item.updated_at | dFormat}}</td>
                                    <td class="text-right">
                                        <router-link :to="'/items-show/'+item.id">
                                            <i class="fa fa-eye blue m-1"></i>
                                        </router-link>
                                        <router-link :to="'/items-edit/'+item.id">
                                            <i class="fa fa-edit green m-1"></i>
                                        </router-link>
                                        <a href="#" @click="deleteItem(item.id)">
                                            <i class="fa fa-trash red m-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>

<script>
// import { setInterval } from "timers";
import moment from 'moment';
export default {
    data() {
        return {
            items: [],
            form: new Form({
                id: ""
            })
        };
    },
    filters: {
        dFormat(value) {
            return moment(String(value)).format('DD-MM-YYYY HH:mm:ss');
        }
    },
    methods: {
        loadItems() {
            this.$Progress.start();
            axios
                .get("api/items")
                .then(({ data }) => (this.items = data))
                .catch(error => {
                    console.log(error.response.data.message);
                });
            this.$Progress.finish();
        },
        editItem(id) {
            this.$router.push({ name: "edit", params: { id: id } });
        },
        deleteItem(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.value) {
                    //Send delete req to controller
                    this.form
                        .delete("api/items/" + id)
                        .then(() => {
                            Swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                        })
                        .catch(() => {
                            Swal(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
                this.loadItems();
            });
        }
    },
    computed: {
        resultCount() {
            if (Object.keys(this.items).length == 0) {
                return 0;
            }
            return Object.keys(this.items).length;
        }
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {
        this.loadItems();
    }
};
</script>
