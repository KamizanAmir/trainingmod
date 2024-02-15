<template>
    <div class="mt-5">
        <div class="card catd-body">
            <div class="card pt-6 pb-2">
                <div class="card-header">
                    <div class="d-flex justify-content-inline">
                        <h1><strong>Training by </strong> ( {{form.name}} )</h1>
                    </div>
                    <div class="card-tools mt-2">
                        <router-link to="/items" class="btn btn-success">
                            <i class="fas fa-list"></i>
                            List Item
                        </router-link>
                    </div>
                </div>
            </div>
            <form @submit.prevent="createItem" class="bg-white p-4">
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
            <span v-if="selectedCategory">{{ selectedCategory.t_type }}</span>
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
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            categories: [],
            id: 0,
            form: new Form({
                name: "",
                category_id: "null",
                training_date: "",
                expired_date: "",
                departments: "",
                rows: [{ id: 0, key: "", value: "" }]
            })
        };
    },
    computed: {
        selectedCategory() {
        return this.categories.find(category => category.id === this.form.category_id);
        }
    },
    watch: {
        'form.training_date': function(newDate, oldDate) {
        if (newDate && this.selectedCategory) {
            let expiredDate = new Date(newDate);
            if (this.selectedCategory.t_type !== 'Recert') {
            expiredDate.setFullYear(expiredDate.getFullYear() + 50); // Set to 50 years for non-Recert
            } else {
            expiredDate.setFullYear(expiredDate.getFullYear() + 1); // Set to 1 year for Recert
            }
            this.form.expired_date = expiredDate.toISOString().split('T')[0];
        }
        },
        'form.category_id': function(newVal, oldVal) {
        // Trigger the watcher to update the expired date when category changes
        this.form.training_date && this.$nextTick(() => {
            this.form.training_date = this.form.training_date;
        });
        }
    },
    methods: {
        loadCategories() {
            axios
                .get("/api/category/1")
                .then(({ data }) => (this.categories = data))
                .catch(error => {
                    console.log(error.response.data.message);
                });
        },
        addForm: function() {
            this.id = this.id + 1;
            this.form.rows.push({ id: this.id, key: "", value: "" });
        },
        removeItem: function(key) {
            this.form.rows = this.form.rows.filter(e => e.id !== key);
        },
        createItem() {
            this.$Progress.start();

            // Format the training_date to d-m-Y
            const formattedTrainingDate = this.formatDate(this.form.training_date);
            const formattedExpiredDate = this.formatDate(this.form.expired_date);

            this.form
                .post("/api/items", {
                    name: this.form.name,
                    category_id: this.form.category_id,
                    training_date: formattedTrainingDate, // Use the formatted date
                    expired_date: formattedExpiredDate, // Use the formatted date for expired_date as well
                    departments: this.form.departments,
                })
                .then(() => {
                    Toast.fire({
                        type: "success",
                        title: "Item created successfully"
                    });
                    this.$Progress.finish();
                })
                .catch(() => {});
        },

        // Helper method to format date from Y-m-d to d-m-Y
        formatDate(dateString) {
            const [year, month, day] = dateString.split('-');
            return `${day}-${month}-${year}`;
        },

    },
    mounted() {
        console.log("Component mounted.");
        this.loadCategories();
    }
};
</script>
