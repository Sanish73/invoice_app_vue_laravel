<script setup>

import { onMounted , ref } from 'vue';
import  SearchButton from '../includes/searchButton.vue';
import  FilterRaidosButtons from '../includes/filterRaidosButtons.vue';
import  FilterDropdown from '../includes/filterDropdown.vue';



let datas = ref([])


defineProps({
items:{
    type:Array,
    required: true
}
});



</script>


<template>
  <div class="p-6 mx-auto">
    <div class="flex w-full  justify-between">
        <SearchButton/>
        <div class="flex justify-end gap-5">
            <FilterRaidosButtons/>
            <FilterDropdown/>
        </div>
    </div>
    <div class="overflow-x-auto">
      <table class="table-auto w-full">
        <thead>
          <tr>
            <th class=" text-black px-4 py-2">ID</th>
            <th class=" text-black px-4 py-2">numer</th>
            <th class=" text-black px-4 py-2">reference</th>
            <th class=" text-black px-4 py-2">firstname</th>
            <th class=" text-black px-4 py-2">lastname</th>
            <th class=" text-black px-4 py-2">Email</th>
            <th class=" text-black px-4 py-2">address</th>
           
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item,i) in items" :key="i">
            <td class=" text-black border px-4 py-2">{{ item.id }}</td>
            <td class=" text-black border px-4 py-2">{{ item.number }}</td>
            <td class=" text-black border px-4 py-2">{{ item.reference }}</td>
            <td class=" text-black border px-4 py-2">{{ item.customer.firstname }}</td>
            <td class=" text-black border px-4 py-2">{{ item.customer.lastname }}</td>
            <td class=" text-black border px-4 py-2">{{ item.customer.email }}</td>
            <td class=" text-black border px-4 py-2">{{ item.customer.address }}</td>
          </tr>
        
        </tbody>
      </table>
    </div>
    <!-- <pre class="text-black">{{ JSON.stringify(items, null, 2) }}</pre> -->
  </div>
</template>
