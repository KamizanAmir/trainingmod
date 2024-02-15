<template>
    <div class="mt-5">
        <div class="card catd-body">
            <div class="card pt-6 pb-2">
                <div class="card-header">
                    <div class="d-flex justify-content-inline">
                        <h1><strong>Currently modify : </strong> {{form.name}}</h1>
                    </div>
                    <div class="card-tools mt-2">
                        <router-link to="/items" class="btn btn-success">
                            <i class="fas fa-list"></i>
                            List Item
                        </router-link>
                    </div>
                </div>
            </div>
            <form @submit.prevent="editItem" class="bg-white p-4">
                <div class="form-group row">
                    <!-- Trainer Name -->
                    <div class="col-md-2">
                        <label for="name">Trainer Name :</label>
                        <input
                            v-model="form.name"
                            type="text"
                            class="form-control"
                            placeholder="Trainer name"
                            :class="{ 'is-invalid': form.errors.has('name') }"
                        >
                        <has-error :form="form" field="name"></has-error>
                    </div>

                    <!-- Training Module -->
                    <div class="col-md-2">
                        <label for="category">Training Module :</label>
                        <select
                            v-model="form.category_id"
                            name="category"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('category_id') }"
                        >
                            <option value="null" disabled>Select a module</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >{{ category.name }}</option>
                        </select>
                        <has-error :form="form" field="category_id"></has-error>
                    </div>

                            <!-- Departments -->
                    <div class="col-md-2">
                        <label for="departments">Departments :</label>
                        <input
                            v-model="form.departments"
                            type="text"
                            class="form-control"
                            placeholder="Enter departments"
                            :class="{ 'is-invalid': form.errors.has('departments') }"
                        >
                        <has-error :form="form" field="departments"></has-error>
                    </div>

                    <!-- Training Date -->
                    <div class="col-md-2">
                        <label for="trainingDate">Training Date :</label>
                        <input
                            v-model="form.training_date"
                            type="date"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('training_date') }"
                        >
                        <has-error :form="form" field="training_date"></has-error>
                    </div>

                    <!-- Expired Date -->
                    <div class="col-md-2">
                        <label for="expiredDate">Expired Date :</label>
                        <input
                            v-model="form.expired_date"
                            type="date"
                            class="form-control"
                            :class="{ 'is-invalid': form.errors.has('expired_date') }"
                            disabled
                        >
                        <has-error :form="form" field="expired_date"></has-error>
                    </div>
                </div>

                <!-- Employee Name and Employee ID -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="text-bold">Employee Name :</label>
                    </div>
                    <div class="col-md-6">
                        <label class="text-bold">Employee ID :</label>
                    </div>
                </div>

                <!-- Employee Name and Employee ID Input Fields -->
                <div v-for="(row,index) in form.rows" :key="row.id" class="form-group row">
                    <div class="col-md-6">
                        <input
                            v-model="row.key"
                            type="text"
                            class="form-control"
                            placeholder="Enter employee name"
                            :class="{ 'is-invalid': form.errors.has('rows.'+index+'.key') }"
                        >
                        <has-error :form="form" :field="'rows.'+index+'.key'"></has-error>
                    </div>
                    <div class="col-md-6 d-flex">
                        <input
                            v-model="row.value"
                            type="text"
                            class="form-control"
                            placeholder="Enter employee ID"
                            :class="{ 'is-invalid': form.errors.has('rows.'+index+'.value') }"
                        >
                        <has-error :form="form" :field="'rows.'+index+'.value'"></has-error>
                        <a href="#" @click.prevent="removeItem(row.id)" class="btn btn-icon">
                            <i class="fas fa-minus-circle fa-lg"></i>
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <a @click.prevent="addForm" class="btn bnt-lg btn-outline-success">
                        Add More
                        <i class="fas fa-plus-circle fa-lg blue"></i>
                    </a>
                </div>
                <button type="submit" class="btn btn-success float-right">Update</button>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: ["id"],
    data() {
        return {
            itemId: 0,
            categories: {},
            form: new Form({
                name: "",
                category_id: "",
                departments: "",
                training_date: "",
                expired_date: "",
                rows: [{ id: 0, key: "", value: "" }]
            })
        };
    },
    watch: {
    'form.training_date': function(newDate) {
        if (newDate) {
            let expiredDate = new Date(newDate);
            expiredDate.setDate(expiredDate.getDate() + 365); // Change date to user request
            this.form.expired_date = expiredDate.toISOString().split('T')[0];
        }
    }
},
    methods: {
        loadItem() {
            let vm = this;
            axios
                .get("/api/items/" + this.id)
                .then(function(response) {
                    vm.form.fill({
                        name: response.data.name,
                        category_id: response.data.category_id,
                        departments: response.data.departments,
                        training_date: response.data.training_date,
                        expired_date: response.data.expired_date,
                        rows: JSON.parse(response.data.properties)
                    });
                    vm.itemId =
                        vm.form.rows.length > 0
                            ? vm.form.rows[vm.form.rows.length - 1].id
                            : 0;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        loadCategories() {
            axios
                .get("/api/category/1")
                .then(({ data }) => (this.categories = data))
                .catch(error => {
                    console.log(error.response.data.message);
                });
        },
        addForm: function() {
            this.itemId = this.itemId + 1;
            this.form.rows.push({ id: this.itemId, value: "" });
        },
        removeItem: function(key) {
            this.form.rows = this.form.rows.filter(e => e.id !== key);
        },
        editItem() {
            this.$Progress.start();
            this.form
                .put("/api/items/" + this.id)
                .then(() => {
                    Toast.fire({
                        type: "success",
                        title: "Item updated successfully"
                    });
                    this.$Progress.finish();
                    // Add a delay to allow the toast message to be read by the user before redirecting
                    setTimeout(() => {
                        this.$router.push("/items");
                    }, 1000); // Delay for 2 seconds
                })
                .catch(() => {
                    Swal("Failed!", "There was something wrong.", "warning");
                });
        }

    },
    mounted() {
        console.log("Component mounted.");
        this.loadCategories();
        this.loadItem();
    }
};
</script>