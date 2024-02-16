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
                        <h3 class="text-bold" style="color: orange;">{{item.name}}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Centered Category -->
                        <h5 class="card-title">
                            <span class="text-bold">Training Module :</span>
                            <span v-if="item.category" style="color:blue;">{{item.category.name}}</span>
                            <span v-else class="text-danger">May be deleted</span> ||
                                <span v-if="item.category" class="text-bold">Training Type : </span><a style="color: blueviolet;">{{ item.category.t_type }}</a> ||
                                <span v-if="item.category" class="text-bold">Labor Type :</span> {{ item.category.l_type }} ||
                                <span v-if="item.category" class="text-bold">Training Hour(Hours) :</span> {{ item.category.t_hours }}
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
                            <button @click="exportToExcel" class="btn btn-success">Export to Excel</button> <!-- New Export to Excel Button -->
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
        exportToExcel() {
            let data = this.item; // Assuming this.item contains all the data you want to export
            let csvContent = "data:text/csv;charset=utf-8,";

            // Corrected headers
            csvContent += "Employee ID,Employee Name,Training Module,Trainer Name, Training Type,Labour Type, Training Hours(h), Training Date,Expired Date\r\n";

            // Add each row of data
            JSON.parse(data.properties).forEach((property) => {
                // Corrected order of data to match the headers
                let row = [
                    property.value, // Employee ID
                    property.key, // Employee Name
                    data.category ? data.category.name : 'Deleted', // Training Module
                    data.name, // Trainer Name
                    data.category ? data.category.t_type : 'Deleted', // Training Type
                    data.category ? data.category.l_type : 'Deleted', // Labour Type
                    data.category ? data.category.t_hours : 'Deleted', // Training Hour
                    this.formatDate(data.training_date), // Training Date
                    this.formatDate(data.expired_date) // Expired Date
                ].join(',');
                csvContent += row + "\r\n";
            });

            // Encode the csvContent
            var encodedUri = encodeURI(csvContent);

            // Create a link and click on it to start the download
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "export.csv");
            document.body.appendChild(link); // Required for FF

            link.click(); // This will download the file
            document.body.removeChild(link); // Clean up
        },
        formatDate(dateString) {
            return moment(String(dateString)).format('DD/MM/YYYY');
        },
    },
    mounted() {
        this.loadItem();
        console.log("Component mounted.");
    }
};
</script>