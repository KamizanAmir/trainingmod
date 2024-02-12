<template>
    <div id="readme" class="Box-body readme blob instapaper_body js-code-block-container">
      <article class="markdown-body entry-content p-3 p-md-6" itemprop="text">
        <h1>Simple Dashboard and Alert</h1>
        <!-- Notification Alert Section for Expiring Items -->
        <div v-if="expiringSoonItems.length">
          <div v-for="alert in expiringSoonItems" :key="alert.name" style="color: red;">
            ( <strong> {{ alert.name }} </strong> training) is expiring on <strong> {{ alert.expired_date | dFormat }} </strong>
            <a :href="`/items-show/${alert.id}`" style="padding-left: 20px;">View</a>
          </div>
        </div>
        <div v-else>
          Nothing urgent to show.
        </div>
        
        <h1>Current Records</h1>
        <!-- Section for Non-Expiring Items -->
        <div v-if="nonExpiringItems.length">
          <div v-for="alert in nonExpiringItems" :key="alert.name">
            ( <strong> {{ alert.name }} </strong> training) is expiring on <strong> {{ alert.expired_date | dFormat }} </strong>
            <a :href="`/items-show/${alert.id}`" style="padding-left: 20px;">View</a>
          </div>
        </div>
        <div v-else>
          No current records to show.
        </div>
      </article>
    </div>
  </template>
  
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
        return this.alerts.filter(alert => this.isExpiringSoon(alert.expired_date));
      },
      nonExpiringItems() {
        return this.alerts.filter(alert => !this.isExpiringSoon(alert.expired_date));
      }
    },
    methods: {
      fetchExpiringItems() {
        axios.get('/api/items/')
          .then(response => {
            this.alerts = response.data;
          })
          .catch(error => {
            console.error('There was an error fetching the expiring items', error);
          });
      },
      isExpiringSoon(expiredDate) {
        const today = moment();
        const expiryDate = moment(expiredDate);
        const diffDays = expiryDate.diff(today, 'days');
        return diffDays <= 3;
      },
    }
  }
  </script>  