<script setup>
    import axios from 'axios';
    import {onMounted, ref} from 'vue';
    import {useRoute, useRouter} from 'vue-router';
    import customServiceInstance from '../includes/CustomService';

    // #try to do like sesrvice provider in laravel
   const messageProvider= customServiceInstance.doSomething()

    const router = useRouter();
    let invoices = ref([]);
    let searchInvoices = ref([]);
    let currentPage = ref(1);
    let per_Page = ref(5);
    let total = ref(0);
    let from = ref(0);
    let to = ref(0);

    onMounted(async() => {
        getInvoices()

    });

    const getInvoices = async(page = 1) => {
        let response = await axios.get(`/api/get_all_invoice?page=${page}`);
        // console.log(response.data.invoices);
        invoices.value = response.data.invoices.data;
        per_Page.value = response.data.invoices.per_page;
        currentPage.value = response.data.invoices.current_page;
        total.value = response.data.invoices.total;
        from.value = response.data.invoices.from;
        to.value = response.data.invoices.to;
    };
    const search = async() => {
        let response = await axios.get("/api/search_invoices?s=" + searchInvoices.value);
        invoices.value = response.data.invoices;
    };

    const newInvoice = async() => {
        let form = await axios.get("/api/create_invoice");
        router.push('/invoices/new');
    };

    const nextPage = () => {
        if (currentPage.value * per_Page.value < total.value) {
            getInvoices(currentPage.value + 1);
        }
    };
    const previousPage = () => {
        if (currentPage.value > 1) {
            getInvoices(currentPage.value - 1);
        }
    };
    const onShow = (id) => {
            router.push('/invoices/show'+id);
    };
 

    const returnIndexNum = (fromNum, toNum) => {
        const result = [];
        for (let i = fromNum; i <= toNum; i++) {
            result.push(i);
        }
        return result;
    };
    const showTablePractice = () => {
            router.push('/demo/showTablePractice');
    };


</script>
<template>
    <div class="container">
        <div class="row">
            <div class="invoices">

                <div class="card__header">
                    <div>
                        <h2 class="invoice__title">{{messageProvider??'Invoices'}}</h2>
                    </div>
                    <div class='flex gap-4'>
                        <div @click="showTablePractice()">
                            <a class="btn btn-secondary">
                            Show Table prac.
                            </a>
                        </div>
                        <div>
                            <a class="btn btn-secondary" @click="newInvoice">
                                New Invoice
                            </a>
                        </div>
                    </div>
                   
                   
                </div>

                <div class="table card__content w-full">
                    <div class="table--filter">
                        <span class="table--filter--collapseBtn">
                            <i class="fas fa-ellipsis-h"></i>
                        </span>
                        <div class="table--filter--listWrapper">
                            <ul class="table--filter--list">
                                <li>
                                    <p class="table--filter--link table--filter--link--active">
                                        All
                                    </p>
                                </li>
                                <li>
                                    <p class="table--filter--link">
                                        Paid
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table--search">
                        <div class="table--search--wrapper">
                            <select class="table--search--select" name="" id="">
                                <option value="">Filter</option>
                            </select>
                        </div>
                        <div class="relative">
                            <i class="table--search--input--icon fas fa-search"></i>
                            <input
                                class="table--search--input"
                                type="text"
                                placeholder="Search invoice"
                                v-model="searchInvoices"
                                @keyup="search()"/>
                        </div>
                    </div>

                    <div class="table--heading">
                        <p>ID</p>
                        <p>Date</p>
                        <p>Number</p>
                        <p>Customer</p>
                        <p>Due Date</p>
                        <p>Total</p>
                    </div>

                    <div class="table--rows min-h-[224px]" v-if="invoices.length > 0">
                        <div class="table--items" v-for="(item ,i ) in invoices" :key="item.id">
                            <a href="#" @click="onShow(item.id)" class="table--items--transactionId">#{{ returnIndexNum(from , to)[i] }}</a>
                            <p>{{ item.date }}</p>
                            <p>#{{ item.number }}</p>
                            <p class="overflow-hidden whitespace-nowrap overflow-ellipsis" v-if="item.customer">
                                {{ item.customer.firstname }}
                            </p>
                            <p v-else>
                                -
                            </p>
                            <p>{{ item.due_date }}</p>
                            <p>
                                ${{ item.total }}
                            </p>
                        </div>
                    </div>
                    <div class="table--items" v-else>
                        <p>NO invoice found</p>
                    </div>
                    <div class="flex flex-col items-center pt-6">
                        <!-- Help text -->
                        <span class="text-sm text-gray-700 dark:text-gray-400">
                            Showing <span class="font-semibold text-gray-900 dark:text-blue-600">{{ from }}</span> to <span class="font-semibold text-gray-900 dark:text-blue-700">{{ to }}</span> of <span class="font-semibold text-gray-900 dark:text-blue-700">{{total}}</span> Entries
                        </span>
                        <!-- Buttons -->
                        <div class="inline-flex mt-2 xs:mt-0">
                            <button @click="previousPage()" class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                Prev
                            </button>
                            <button @click="nextPage()" class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                Next
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>