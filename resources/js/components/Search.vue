<template>
  <div>
    <!-- Search container for styling -->
    <div class="search-container">
      <input class="search-input" v-model="query" @input="debouncedSearch" placeholder="Search...">
    </div>
    <!-- Table container for overflow handling -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Training Module</th> <!-- New header for Training Module -->
            <th>Employer Name</th>
            <th>Employer ID</th>
            <th>Training Date</th>
            <th>Expiration Date</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in filteredItems" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ item.category.name }}</td>
            <td>
              <div v-for="prop in item.parsedProperties" :key="prop.key" class="employee-name">
                {{ prop.key }}
              </div>
            </td>
            <td>
              <div v-for="prop in item.parsedProperties" :key="prop.value" class="employee-id">
                {{ prop.value }}
              </div>
            </td>
            <td>{{ item.training_date }}</td>
            <td>{{ item.expired_date }}</td>
            <td>
              <a :href="`/items-show/${item.id}`" class="view-button" style="white-space: nowrap;">View/Print</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<style scoped>
/* Add this for the search input styling */
.search-container {
  margin-top: 15px;
  display: flex;
  justify-content: center; /* Center the search box horizontally */
  margin-bottom: 20px; /* Spacing below the search box */
}
.employee-name, .employee-id {
  border-bottom: 1px solid #ddd; /* Light grey border under each name/ID */
  padding: 5px 0; /* Padding for each name/ID */
}
.search-input {
  width: 50%; /* Set the width of the search input */
  border-radius: 15px; /* Rounded corners for the input */
  border: 1px solid #ccc; /* Light grey border */
  padding: 10px 15px; /* Padding inside the input */
  font-size: 16px; /* Set the font size */
}

/* Styles for the table */
.table-container {
  overflow-x: auto; /* Allows table to scroll horizontally on small screens */
}

table {
  width: 100%; /* Full width tables */
  border-collapse: collapse; /* Collapse borders */
  margin-top: 20px; /* Spacing above the table */
  border-collapse: separate;
  border-spacing: 0;
}

th, td {
  text-align: left; /* Align text to the left */
  padding: 12px; /* Padding in cells */
  border-bottom: 1px solid #ddd; /* Light grey border under each row */
  border-right: 1px solid #ddd; /* Adds a border to the right of each cell */
  border-bottom: 1px solid #ddd; /* Adds a border under each cell */
}
th:last-child, td:last-child {
  border-right: none; /* Removes the right border for the last cell in the row */
}

thead th {
  border-bottom: 2px solid #ddd; /* Makes the header bottom border thicker for emphasis */
}

/* Ensure that the top cells have a top border for better separation */
tbody tr:first-child td {
  border-top: 1px solid #ddd;
}
th {
  background-color: #f2f2f2; /* Light grey background for headers */
}

tr:hover {
  background-color: #e8f4f8; /* Light blue background on row hover */
}

/* add more custom styles as needed */
</style>
<script>
import _ from 'lodash'; // Ensure you have lodash installed for debouncing

export default {
  data() {
    return {
      query: '',
      items: [],
      filteredItems: [],
    };
  },
  created() {
    this.fetchItems();
  },
  watch: {
    query(newQuery) {
      if (newQuery === '') {
        this.filteredItems = this.items;
      }
    },
  },
  methods: {
    fetchItems() {
        axios.get('/api/items')
        .then(response => {
            // Parse the properties JSON for each item
            this.items = response.data.map(item => ({
                ...item,
                parsedProperties: JSON.parse(item.properties)
            }));
            this.filteredItems = this.items;
        })
        .catch(error => {
            console.error('There was an error fetching the items', error);
        });
    },
    search() {
      if (this.query.trim() === '') {
        this.filteredItems = this.items;
      } else {
        const lowerCaseQuery = this.query.toLowerCase();
        
        this.filteredItems = this.items.filter(item => {
          const nameMatch = item.name.toLowerCase().includes(lowerCaseQuery);
          
          // Check if any property contains the search query
          const propertiesMatch = item.parsedProperties.some(prop => 
            (prop.key && prop.key.toLowerCase().includes(lowerCaseQuery)) || 
            (prop.value && prop.value.toLowerCase().includes(lowerCaseQuery))
          );
          return nameMatch || propertiesMatch;
        });
      }
    },
    debouncedSearch: _.debounce(function () {
      this.search();
    }, 500), // Debounce the search method
  }
}
</script>