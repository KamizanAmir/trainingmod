<template>
    <div id="readme" class="Box-body readme blob instapaper_body js-code-block-container">
        <article class="markdown-body entry-content p-3 p-md-6" itemprop="text">
            <!-- Section for Expired Items -->
            <div v-if="expiredItems.length">
            <h1>Expired Items</h1>
            <div v-for="alert in expiredItems" :key="alert.name" class="alert-container">
                <div style="flex-grow: 1;">
                <strong> {{ alert.name }} </strong> training for <a :href="`/items-show/${alert.id}`" style="color: blue;">{{ alert.category.name }}</a> has expired on <strong style="color: red;"> {{ alert.expired_date | dFormat }} </strong>
                </div>
                <a :href="`/items-show/${alert.id}`" style="white-space: nowrap;">View</a>
            </div>
            </div>
            <div v-else>
                No expired items to show.
            </div>
            <hr>
            <h1>Alert</h1>
            <!-- Notification Alert Section for Expiring Items -->
            <div v-if="expiringSoonItems.length">
            <div v-for="alert in expiringSoonItems" :key="alert.name" class="alert-container">
                <div style="flex-grow: 1;">
                <strong> {{ alert.name }} </strong> training for <a :href="`/items-show/${alert.id}`" style="color: blue;">{{ alert.category.name }}</a> is expiring soon on <strong style="color: red;"> {{ alert.expired_date | dFormat }} </strong>
                </div>
                <a :href="`/items-show/${alert.id}`" style="white-space: nowrap;">View</a>
            </div>
            <hr>
            </div>
            <div v-else>
                Nothing urgent to show.
            </div>
            
            <h1>Current Records</h1>
            <!-- Section for Non-Expiring Items -->
            <div v-if="nonExpiringItems.length">
            <div v-for="alert in nonExpiringItems" :key="alert.name" class="alert-container">
                <div style="flex-grow: 1;">
                <strong> {{ alert.name }} </strong> training for <a :href="`/items-show/${alert.id}`" style="color: blue;">{{ alert.category.name }}</a> is valid until <strong> {{ alert.expired_date | dFormat }} </strong>
                </div>
                <a :href="`/items-show/${alert.id}`" style="padding-left: 20px;">View</a>
            </div>
            </div>
            <div v-else>
                No current records to show.
            </div>
        </article>
    </div>
</template>
<style scoped>
.alert-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px; /* or any spacing you want between items */
}
</style>
  <script>
  import axios from 'axios';
  import moment from 'moment';
  export default {
    data() {
      return {
        alerts: []
      }
    },
    created() {
      this.fetchExpiringItems();
    },
    filters: {
      dFormat(value) {
        return moment(String(value)).format('DD-MM-YYYY');
      }
    },
    computed: {
        expiringSoonItems() {
            const today = moment();
            return this.alerts
            .filter(alert => {
                const expiryDate = moment(alert.expired_date);
                return this.isExpiringSoon(expiryDate) && expiryDate.isAfter(today);
            })
            .sort((a, b) => new Date(a.expired_date) - new Date(b.expired_date));
        },
        expiredItems() {
            const today = moment();
            return this.alerts
            .filter(alert => {
                const expiryDate = moment(alert.expired_date);
                return expiryDate.isBefore(today);
            })
            .sort((a, b) => new Date(a.expired_date) - new Date(b.expired_date));
        },
        nonExpiringItems() {
            return this.alerts
            .filter(alert => !this.isExpiringSoon(alert.expired_date))
            .sort((a, b) => new Date(a.expired_date) - new Date(b.expired_date));
        }
    },
    methods: {
        fetchExpiringItems() {
            axios.get('/api/items/')
            .then(response => {
                this.alerts = response.data;
                this.alerts.sort((a, b) => new Date(a.expired_date) - new Date(b.expired_date));
            })
            .catch(error => {
                console.error('There was an error fetching the expiring items', error);
            });
        },
        isExpiringSoon(expiredDate) {
            const today = moment();
            const expiryDate = moment(expiredDate);
            const diffDays = expiryDate.diff(today, 'days');
            return diffDays <= 3 && diffDays >= 0; // Changed to check that the days difference is non-negative
        },
    }
  }
  </script>  