<script setup>
    import axios from 'axios';
    import { onMounted, ref } from 'vue';
    import router from '../../router';

    onMounted(async()=>{
    showSelectedInvoices()
    })

    let form = ref({id: ''});
    const isHidden =ref(false); 


    const props = defineProps({
    id:{
        type:String,
        dafualt:''
    }
    });

    const showSelectedInvoices = async () => {
        try {
            const response = await fetch(`/api/show_Invoices_selec/${props.id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            form.value = data;
        } catch (error) {
            console.error('Error fetching invoices:', error);
        }
    };

    const printData =()=>{
        isHidden.value =!   isHidden.value;

        function waitForPrintToFinish() {
            return new Promise(resolve => {
                setTimeout(() => {
                    window.print();
                    resolve();
                }, 1000); 
            });
        }

        waitForPrintToFinish().then(() => {
            isHidden.value = !isHidden.value;
            router.push('/').catch(() => {});
        }).then(() => {
            console.log("Navigation successful");
        }).catch(error => {
            console.error("Error occurred:", error); 
        });
    }

    const onEdit =(id) =>{
            router.push('/invoice/edit'+id);
            // console.log('hooooo00');
            
        }
  
</script>
<template>
<div class="container" style="margin-top: 0 !important;">   
    <div class="invoices">
        <div :class="{hidden : isHidden}">
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Invoice</h2>
                </div>
                <div>
                    
                </div>
            </div>
            <div>
                <div class="card__header--title ">
                    <h1 class="mr-2">#{{form.id}}</h1>
                    <p>{{form.created_at}}</p>
                </div>
        
                <div>
                    <ul  class="card__header-list">
                        <li>
                            <button class="selectBtnFlat" @click="printData()">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                        </li>
                        <li>
                            <button class="selectBtnFlat" @click="onEdit(form.id)">
                                <i class=" fas fa-reply"></i>
                                Edit
                            </button>
                        </li>
                        <li>
                            <button class="selectBtnFlat ">
                                <i class=" fas fa-pencil-alt"></i>
                                Delete
                            </button>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>

        <div class="table invoice">
            <div class="logo">
                <img src="../../../../assets/img/logo.png" alt="" style="width: 200px;"/>
            </div>
            <div class="invoice__header--title">
                <p></p>
                <p class="invoice__header--title-1">Invoice</p>
                <p></p>
            </div>

            
            <div class="invoice__header--item">
                <div>
                    <h2>Invoice To:</h2>
                    <p v-if="form.customer">{{ form.customer.firstname }}</p>
                </div>
                <div>
                        <div class="invoice__header--item1">
                            <p>Invoice#</p>
                            <span>#{{ form.number }}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Date</p>
                            <span>{{form.date}}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Due Date</p>
                            <span>{{form.due_date}}</span>
                        </div>
                        <div class="invoice__header--item2">
                            <p>Reference</p>
                            <span>{{form.reference}}</span>
                        </div>
                    
                </div>
            </div>

            <div class="table py1">

                <div class="table--heading3">
                    <p>#</p>
                    <p>Item Description</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                </div>
    
                <div class="table--items3" v-for="(itemsInvoice,i) in form.invoice_item " :key="i">
                    <p>{{ i + 1 }}</p>
                    <p>{{ itemsInvoice.product_list.description }}</p>
                    <p>$ {{ itemsInvoice.unit_price }}</p>
                    <p>{{ itemsInvoice.quantity }}</p>
                    <p>$ {{ (itemsInvoice.quantity *itemsInvoice.unit_price )  }}</p>
                </div>
               
            </div>

            <div  class="invoice__subtotal">
                <div>
                    <h2>Thank you for your business</h2>   
                </div>
                <div>
                    <div class="invoice__subtotal--item1">
                        <p>Sub Total</p>
                        <span> $ 1200</span>
                    </div>
                    <div class="invoice__subtotal--item2">
                        <p>Discount</p>
                        <span>$ 100</span>
                    </div>
                    
                </div>
            </div>

            <div class="invoice__total">
                <div>
                    <h2>Terms and Conditions</h2>
                    <p>{{ form.terms_and_conditions }}</p>
                </div>
                <div>
                    <div class="grand__total" >
                        <div class="grand__total--items">
                            <p>Grand Total</p>
                            <span>$ 1100</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card__footer">
            <div>
                
            </div>
            <div>
                <a class="btn btn-secondary">
                    Save
                </a>
            </div>
        </div>
        
    </div>
</div>

    <br/><br/><br/>
</template>