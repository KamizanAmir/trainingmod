<template>
    <div id="readme" class="Box-body readme blob instapaper_body js-code-block-container">
        <article class="markdown-body entry-content p-3 p-md-6" itemprop="text">
            <h1>
                <a
                    id="user-content-project-introduction"
                    class="anchor"
                    aria-hidden="true"
                    href="#project-introduction"
                >
                    <svg
                        class="octicon octicon-link"
                        viewBox="0 0 16 16"
                        version="1.1"
                        width="16"
                        height="16"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"
                        ></path>
                    </svg>
                </a>Simple Dashboard and Alert
            </h1>
            <!-- Notification Alert Section -->
            <div v-if="alerts.length">
                <div v-for="alert in alerts" :key="alert.name" :style="isExpiringSoon(alert.expired_date) ? {color: 'red'} : {}">
                    ( <strong> {{ alert.name }} </strong> ) is expiring on <strong> {{ alert.expired_date | dFormat }} </strong>
                </div>
            </div>
            <div v-else>
                Nothing to show.
            </div>
            <h1>
                <a
                    id="user-content-project-objectives"
                    class="anchor"
                    aria-hidden="true"
                    href="#project-objectives"
                >
                    <svg
                        class="octicon octicon-link"
                        viewBox="0 0 16 16"
                        version="1.1"
                        width="16"
                        height="16"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 9h1v1H4c-1.5 0-3-1.69-3-3.5S2.55 3 4 3h4c1.45 0 3 1.69 3 3.5 0 1.41-.91 2.72-2 3.25V8.59c.58-.45 1-1.27 1-2.09C10 5.22 8.98 4 8 4H4c-.98 0-2 1.22-2 2.5S3 9 4 9zm9-3h-1v1h1c1 0 2 1.22 2 2.5S13.98 12 13 12H9c-.98 0-2-1.22-2-2.5 0-.83.42-1.64 1-2.09V6.25c-1.09.53-2 1.84-2 3.25C6 11.31 7.55 13 9 13h4c1.45 0 3-1.69 3-3.5S14.5 6 13 6z"
                        ></path>
                    </svg>
                </a>Current Record
            </h1>
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