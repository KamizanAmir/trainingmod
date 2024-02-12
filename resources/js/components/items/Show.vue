<style scoped>
.card-title {
  display: block; /* Make sure it's a block-level element */
  text-align: center; /* Center-align the text */
  width: 100%; /* Use full width of the container */
  margin: auto; /* Center block-level elements horizontally */
}

.employee-info {
  text-align: center; /* Align employee information text to the left */
  margin-bottom: 1rem; /* Spacing below employee info before divider */
}

.dates-info {
  text-align: center; /* Align dates information text to the left */
  margin-top: 1rem; /* Spacing above dates info after divider */
}
</style>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card text-center">
                    <div class="card-header text-bold">
                        <h3>{{item.name}}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Centered Category -->
                        <h5 class="card-title">
                            <span class="text-bold">Training Module :</span>
                            <span v-if="item.category" style="color:red;">{{item.category.name}}</span>
                            <span v-else class="text-danger">May be deleted</span>
                        </h5>
                        <hr style="margin-top: 25px;"> <!-- Divider -->
                        <div class="employee-info">
                            <p class="card-text" v-for="(property, index) in JSON.parse(item.properties)">
                                <span class="text-bold">{{ index + 1 }} - Employee Name : </span>{{ property.key }} || 
                                <span class="text-bold">Department:</span> {{ item.departments }} ||
                                <span class="text-bold">Employee ID :</span>{{ property.value }} 
                            </p>
                        </div>
                        <hr> <!-- Divider -->
                        <div class="dates-info">
                            <!-- Display Training Date -->
                            <p class="card-text">
                                <span class="text-bold">Training Date:</span>
                                {{ item.training_date | dFormat }}
                            </p>
                            <!-- Display Expired Date -->
                            <p class="card-text">
                                <span class="text-bold">Expired Date:</span>
                                {{ item.expired_date | dFormat }}
                            </p>
                        </div>
                        <div class="button-group" style="margin-top: 1rem;"> <!-- Button group for alignment -->
                            <router-link to="/intro" class="btn btn-secondary">Home</router-link> <!-- Home button -->
                            <router-link :to="'/items-edit/'+item.id" class="btn btn-primary">Edit Item</router-link>
                        </div>
                    </div>
                    <div class="card-footer text-muted">{{item.created_at | dFormat}}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
export default {
    props: ["id"],
    data() {
        return {
            item: {},
            counter: 1, // Initialize counter if you want to use it in a loop
        };
    },
    filters: {
        dFormat(value) {
            return moment(String(value)).format('DD-MM-YYYY HH:mm:ss');
        }
    },
    methods: {
        loadItem() {
            axios
                .get("/api/items/" + this.id)
                .then(({ data }) => (this.item = data))
                .catch(error => {
                    console.log(error);
                });
        },
        incrementCounter() {
            this.counter += 1;
        },
    },
    mounted() {
        this.loadItem();
        console.log("Component mounted.");
    }
};
</script>
