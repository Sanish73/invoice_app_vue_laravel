<script setup>
import DataTable from "./dataTable.vue";

import { onMounted , ref } from 'vue';


let datas = ref([])


onMounted(async()=>{
    try {
      const response = await fetch('/api/get_All_Invoice_customer');
       datas.value = await response.json();
    } catch (error) {
      console.error(error);
    }
});



</script>


<template>
  
  <DataTable :items='datas'/>
</template>
