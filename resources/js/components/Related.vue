<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">
                <div class="card text-center">
                    <div class="card-header text-bold">
                        <h3>{{category.name}}</h3>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <span class="text-bold">Current trainer for this module :</span>
                        </h5>
                        <ul class="card-text" v-for="item in category.item" :key="item.id">
                            <li>
                                <router-link :to="'/items-show/'+item.id">{{item.name}}</router-link>
                            </li>
                        </ul>
                        <div class="button-group" style="margin-top: 1rem;"> <!-- Button group for alignment -->
                            <router-link to="/category" class="btn btn-secondary">Back</router-link> <!-- Home button -->
                        </div>
                    </div>
                    <div class="card-footer text-muted">{{category.created_at | dFormat}}</div>
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
            category: {}
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
                .get("/api/related/" + this.id)
                .then(({ data }) => (this.category = data))
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.loadItem();
        console.log("Component mounted.");
    }
};
</script>
