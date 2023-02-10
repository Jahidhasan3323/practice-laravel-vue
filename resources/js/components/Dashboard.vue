<template>
    <div>
        <div class="container mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="menu d-flex justify-content-between">
                            <div>
                                <nav class="nav">
                                    <router-link class="nav-link" to="/">Dashboard</router-link>
                                    <router-link class="nav-link" to="/report">Report</router-link>
                                </nav>
                            </div>
                            <div class="d-flex justify-content-between">
                                <download-excel
                                    class="me-2"
                                    :data="customers"
                                    :fields="columnList"
                                    type="csv"
                                >
                                    <button class="btn btn-warning float-end">CSV</button>
                                </download-excel>
                                <download-excel
                                    :data="customers"
                                    :fields="columnList"
                                >
                                    <button class="btn btn-success float-end">Excel</button>
                                </download-excel>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between mt-4">
                                    <div class="ms-2">
                                        <select v-model="pagination.perPage" class="form-select">
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option :value="pagination.totalItems">All</option>
                                        </select>
                                    </div>
                                    <div class="">
                                        <input v-model.trim="search.any" type="text" class="form-control"
                                               placeholder="Search...">
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped mt-4">
                                    <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th v-if="filterColumn('Name')">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <input class="form-control" type="text" v-model.trim="search.name"
                                                           @keyup="searchHandaler()">
                                                    <span class="">Name</span>
                                                </div>
                                                <i role="button" @click="sorting()" class="fa fa-sort mt-2"></i>
                                            </div>
                                        </th>
                                        <th v-if="filterColumn('Phone')">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <input class="form-control" type="text" v-model.trim="search.phone"
                                                           @keyup="searchHandaler()">
                                                    <span class="">Phone</span>
                                                </div>
                                                <i role="button" @click="sorting()" class="fa fa-sort mt-2"></i>
                                            </div>
                                        </th>
                                        <th v-if="filterColumn('Address')">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <input class="form-control" type="text"
                                                           v-model.trim="search.address" @keyup="searchHandaler()">
                                                    <span class="">Address</span>
                                                </div>
                                                <i role="button" @click="sorting()" class="fa fa-sort mt-2"></i>
                                            </div>
                                        </th>
                                        <th v-if="filterColumn('City')">
                                            City
                                        </th>
                                        <th v-if="filterColumn('Zip')">
                                            Zip
                                        </th>
                                        <th v-if="filterColumn('Method')">
                                            Method
                                        </th>
                                        <th v-if="filterColumn('Amount')">
                                            Amount
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="customers.length">
                                    <tr v-for="customer in customers" :key="customer.id">
                                        <td>
                                            {{ customer ? customer.id : '' }}
                                        </td>
                                        <td v-if="filterColumn('Name')">
                                            {{ customer ? customer.name : '' }}
                                        </td>
                                        <th v-if="filterColumn('Phone')">
                                            {{ customer ? customer.phone : '' }}
                                        </th>
                                        <th v-if="filterColumn('Address')">
                                            {{ customer.customer_address ? customer.customer_address.address : '' }}
                                        </th>
                                        <th v-if="filterColumn('City')">
                                            {{ customer.customer_address ? customer.customer_address.city : '' }}
                                        </th>
                                        <th v-if="filterColumn('Zip')">
                                            {{ customer.customer_address ? customer.customer_address.zip : '' }}
                                        </th>
                                        <th v-if="filterColumn('Method')">
                                            {{ customer.customer_address ? customer.customer_address.method : '' }}
                                        </th>
                                        <th v-if="filterColumn('Amount')">
                                            <span v-if="customer.customer_purchase.length"
                                                  v-for="customer_purchase in customer.customer_purchase">
                                                <span> {{ customer_purchase.max_amount }}</span><br>
                                            </span>
                                        </th>
                                    </tr>
                                    </tbody>
                                    <h3 v-else class="text-muted mt-2 text-center">No Data Found...</h3>
                                </table>
                                <pagination v-model="pagination.page"
                                            :records="pagination.totalItems"
                                            :per-page="pagination.perPage"
                                            @paginate="getCustomers"/>
                            </div>
                            <div class="col-md-3">
                                <ul class="list-group  mt-4">
                                    <li class="list-group-item" v-for="column in columnList">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="column.value"
                                                   :value="column.value"
                                                   :id="`flexCheckDefault-${column.name}`">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{ column.name }}
                                            </label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Pagination from 'v-pagination-3';

export default {
    name      : "Dashboard",
    components: {
        Pagination
    },
    data() {
        return {
            pagination: {
                page      : 1,
                perPage   : 10,
                totalItems: 0,
            },
            customers : [],
            columnList: [
                {
                    name : 'Name',
                    value: true,
                },
                {
                    name : 'Phone',
                    value: true,
                },
                {
                    name : 'Address',
                    value: true,
                },
                {
                    name : 'City',
                    value: false,
                },
                {
                    name : 'Zip',
                    value: false,
                },
                {
                    name : 'Method',
                    value: false,
                },
                {
                    name : 'Amount',
                    value: false,
                }
            ],
            search    : {
                name   : "",
                phone  : "",
                address: "",
                any    : ""
            },
        }

    },
    mounted() {
        this.getCustomers();
    },
    methods   : {
        sorting() {
            this.customers.reverse()
        },
        filterColumn(menu) {
            return this.columnList.find(item => item.name == menu).value
        },
        getCustomers() {
            const prams = this.makePrams()
            axios.get(`customers${prams}`).then(res => {
                this.customers             = res.data.customers.data
                this.pagination.totalItems = res.data.customers.total;
                // this.$store.commit('setCustomerRecords', this.customers)
            })
        },
        searchHandaler() {
            clearTimeout(this.timer)
            this.timer = setTimeout(() => {
                this.pagination.page = 1;
                this.getCustomers();
            }, 300)

        },
        makePrams() {
            let prams = "";
            prams += this.search.name ? '&name=' + this.search.name : '';
            prams += this.search.phone ? '&phone=' + this.search.phone : '';
            prams += this.search.address ? '&address=' + this.search.address : '';
            prams += this.search.any ? '&any=' + this.search.any : '';
            return `?page=${this.pagination.page}&per_page=${this.pagination.perPage + prams}`;
        },
    },
    watch     : {
        'pagination.perPage': function () {
            this.pagination.page = 1;
            this.getCustomers();
        },
        'search.any'        : function () {
            this.pagination.page = 1;
            this.getCustomers();
        },
    }
}
</script>
