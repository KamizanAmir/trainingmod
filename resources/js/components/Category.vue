<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card pt-2 pb-2">
                    <div class="card-header">
                        <div style="text-align: left;">
                            <h1><strong>Training Module List</strong></h1>
                                <h2 style="text-align: left;">
                                    <span style="text-align: left;">Total no. of training module : {{resultCount}}</span>
                                </h2>
                        </div>
                        <div class="card-tools mt-2">
                            <button class="btn btn-success" @click="createModal">
                                Add New
                                <i class="fas fa-layer-group"></i>
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th>Training name</th>
                                    <th>Prepared By</th>
                                    <th>No of employees</th> <!--Column inside website-->
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                <tr v-for="category in categories.data" :key="category.id">
                                    <td>
                                        <router-link
                                            :to="'/related/'+category.id"
                                        >{{category.name|ucFirst}}</router-link>
                                    </td>
                                    <td>{{category.t_name}}</td> <!--Just call the column name Kamizan-->
                                    <td>{{category.count || '0'}}</td>
                                    <td>{{category.created_at | dFormat}}</td>
                                    <td>{{category.updated_at | dFormat}}</td>
                                    <td class="text-right">
                                        <a href="#" @click="editModal(category)">
                                            <i class="fa fa-edit green m-1"></i>
                                        </a>
                                        <a href="#" @click="deleteCategory(category.id)">
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
        <!-- Modal -->
        <div
            class="modal fade"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Module</h5>
                        <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Module</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent=" editMode ? updateCategory() : createCategory()">
                        <div class="modal-body">
                        <label for="name">Module</label>
                            <div class="form-group">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter module name"
                                    :class="{ 'is-invalid': form.errors.has('name') }"
                                >
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <label for="t_name">Preparation</label>
                            <div class="form-group"> <!--This one Added for input field Kamizan-->
                                <input
                                    v-model="form.t_name"
                                    type="text"
                                    name="t_name"
                                    class="form-control"
                                    placeholder="Module prepared by"
                                    :class="{ 'is-invalid': form.errors.has('t_name') }"
                                >
                                <has-error :form="form" field="t_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="t_hours">Training Hour</label>
                                <input
                                    v-model="form.t_hours"
                                    type="text"
                                    name="t_hours"
                                    class="form-control"
                                    placeholder="HH:MM hour"
                                    :class="{ 'is-invalid': form.errors.has('t_hours') }"
                                >
                                <has-error :form="form" field="t_hours"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="t_type">Training Type</label>
                                <select v-model="form.t_type" name="t_type" class="form-control" :class="{ 'is-invalid': form.errors.has('t_type') }">
                                    <option disabled value="">Please select one</option>
                                    <option value="Recert">Recert</option>
                                    <option value="Training">Training</option>
                                    <option value="Briefing">Briefing</option>
                                </select>
                                <has-error :form="form" field="t_type"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="l_type">Labour Type</label>
                                <select v-model="form.l_type" name="l_type" class="form-control" :class="{ 'is-invalid': form.errors.has('l_type') }">
                                    <option disabled value="">Please select one</option>
                                    <option value="DL">DL</option>
                                    <option value="IDL">IDL</option>
                                </select>
                                <has-error :form="form" field="l_type"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
                            <button v-show="!editMode" type="submit" class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
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
            editMode: false,
            categories: {},
            form: new Form({
                id: "",
                name: "",
                t_name: "",
                t_type: "",
                l_type: "",
                t_hours: "",
            })
        };
    },
    filters: {
        dFormat(value) {
            return moment(String(value)).format('DD-MM-YYYY HH:mm:ss');
        }
    },
    methods: {
        createModal() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            this.form.t_name = ""; //Critical also lol kamizan
            $("#addNew").modal("show");
        },
        editModal(category) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            $("#addNew").modal("show");
            this.form.fill(category);
            this.form.t_name = category.t_name || ''; //need to set it up this way kamizan
        },

        loadCategories() {
            this.$Progress.start();
            axios
                .get("api/category")
                .then(({ data }) => (this.categories = data))
                .catch(error => {
                    console.log(error.response.data.message);
                });
            this.$Progress.finish();
        },
        createCategory() {
            this.$Progress.start();
            this.form
                .post("api/category")
                .then(() => {
                    $("#addNew").modal("hide");
                    Toast.fire({
                        type: "success",
                        title: "Category created successfully"
                    });
                    this.$Progress.finish();
                    this.loadCategories();
                })
                .catch();
        },
        updateCategory() {
            this.$Progress.start();
            this.form
                .put("api/category/" + this.form.id)
                .then(() => {
                    $("#addNew").modal("hide");
                    Swal.fire(
                        "Updated!",
                        "Your category has been updated successfully.",
                        "success"
                    );
                })
                .catch();
            this.$Progress.finish();
            this.loadCategories();
        },
        deleteCategory(id) {
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
                        .delete("api/category/" + id)
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
                this.loadCategories();
            });
        }
    },
    computed: {
        resultCount() {
            if (Object.keys(this.categories).length == 0) {
                return 0;
            }
            return Object.keys(this.categories.data).length;
        }
    },
    mounted() {
        console.log("Component mounted.");
    },
    created() {
        this.loadCategories();
        // setInterval(() => this.loadCategories(), 4000);
    }
};
</script>