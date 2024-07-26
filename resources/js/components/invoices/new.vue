<script setup>

    import axios from 'axios';
    import { onMounted  , ref} from 'vue';
import router from '../../router';


    let form = ref([]);
    let allCustomers = ref([]);
    let customer_id = ref([]);
    let item = ref([]);
    let listCart = ref([]);
    const showModal = ref(false);
    const hideModal = ref(true);
    let listProducts= ref([]);

    onMounted(async() => {
        indexForm()
        getAllCustomers()
        getProducts()

    })
    
    const indexForm = async()=>{
        let response = await axios.get("/api/create_invoice");
        form.value = response.data;
    }

    const getAllCustomers = async()=>{
        let response = await axios.get("/api/customers");
        allCustomers.value = response.data.customers;
    };

    const addCart = (item)=>{
       const itemCart = {
            id:item.id,
            iteam_cart:item.ite_code,
            description :item.description,
            unit_price: item.unit_price,
            quantity :item.quantity ?? 1,
        }
       listCart.value.push(itemCart);
       closeModel();
    };

    const SubTotal = ()=>{
        let total = 0;
        listCart.value.map((data=>{
            total += data.quantity * data.unit_price;
        }))
        return total;
    };
    const Total = ()=>{
        return SubTotal() - form.value.discount;
    };

    const openModal = ()=>{
      showModal.value =! showModal.value;
    };

    const closeModel = ()=>{
      showModal.value =! hideModal.value;
    };

    const removeItem = (i)=>{
        listCart.value.splice(i, 1);
    };
    
    const getProducts = async()=>{
        let response = await axios.get("/api/products");
        listProducts.value = response.data.products;
    };

    const onSave = ()=>{
        if(listCart.value.length >=1){
            let subTotals = 0;
            subTotals = SubTotal();

            let total = 0;
            total = Total();

            const formData = new FormData()
            formData.append('invoice_item' ,JSON.stringify(listCart.value));
            
            formData.append('Customer_Id' ,customer_id.value );
            formData.append('invoice item' ,listCart.value);
            formData.append('data' ,form.value.date);
            formData.append('due_date' ,form.value.due_date);
            formData.append('number' ,form.value.number);
            formData.append('reference' ,form.value.reference);
            formData.append('discount' ,form.value.discount);
            formData.append('subtotal' ,form.value.subtotal);
            formData.append('total' ,form.value.total);
            formData.append('terms_and_conditions' ,form.value.terms_and_conditions);
            
            axios.post("/api/add_invoice" ,formData);
            listCart.value = [];
            router.push('/');            


        }
    };
    
   
    
</script>


<template>
    <div class="container">
        <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">New Invoice</h2>
            </div>
            <div>
                
            </div>
        </div>

        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">Customer</p>
                    <select name="" id="" class="input" v-model="customer_id"> 
                        <option disabled>Select Customers</option>
                        <option v-for="customer in allCustomers" :key="customer.id">
                            {{ customer.firstname }}
                        </option>
                    </select>
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                    <p class="my-1">Due Date</p> 
                    <input id="due_date" type="date" class="input" v-model="form.due_date">
                </div>
                <div>
                    <p class="my-1">Numero</p> 
                    <input type="text" class="input"  v-model="form.number"> 
                    <p class="my-1">Reference(Optional)</p> 
                    <input type="text" class="input"  v-model="form.reference">
                </div>
            </div>
            <br><br>
            <div class="table">

                <div class="table--heading2">
                    <p>Item Description</p>
                    <p>Unit Price</p>
                    <p>Qty</p>
                    <p>Total</p>
                    <p></p>
                </div>
    
                <!-- item 1 -->
                <div class="table--items2" v-for="(itemCart , i) in listCart" :key="itemCart.id" >
                    <p>#{{ itemCart.id }} {{ itemCart.description }}</p>
                    <p>
                        <input type="text" class="input" v-model="itemCart.unit_price" >
                    </p>
                    <p>
                        <input type="text" class="input"  v-model="itemCart.quantity" >
                    </p>
                    <p v-if="itemCart.quantity">
                        $ {{ (itemCart.quantity) * (itemCart.unit_price)  }}
                    </p>
                    <p v-else>
                     
                    </p>
                    <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeItem(i)">
                        &times;
                    </p>
                </div>
                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="openModal()">
                        Add New Line
                    </button>
                </div>
            </div>

            <div class="table__footer">
                <div class="document-footer" >
                    <p>Terms and Conditions</p>
                    <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions" ></textarea>
                </div>
                <div>
                    <div class="table__footer--subtotal">
                        <p>Sub Total</p>
                        <span>$ {{SubTotal()}}</span>
                    </div>
                    <div class="table__footer--discount">
                        <p>Discount</p>
                        <input type="text" class="input" v-model="form.discount">
                    </div>
                    <div class="table__footer--total">
                        <p>Grand Total</p>
                        <span>$ {{ Total() }}</span>
                    </div>
                </div>
            </div>

           
        </div>
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
                <a class="btn btn-secondary" @click="onSave()">
                    Save
                </a>
            </div>
        </div>
        
    </div>
    <!--==================== add modal items ====================-->
    <div class="modal main__modal " :class="{show :showModal}">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModel()">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <div className="  modal__items">

                <ul class=" w-full list-none">
                    <li class="flex justify-between items-center p-4 hover:bg-neutral-300" v-for="(item) in listProducts" :key="item.id">
                        <p class="text-center flex-1 text-black">{{ item.item_code }} {{ item.description }}</p>
                        <button class="ml-4 transition ease-in-out delay-120 bg-blue-500 hover:-translate-y-1 hover:scale-110 hover:bg-indigo-500 duration-300 text-white px-4 py-2 rounded-md drop-shadow-2xl" @click="addCart(item)">+</button>
                    </li>
                </ul>

                <!-- <select class="input my-1">
                    <option value="None">None</option>
                    <option value="None">LBC Padala</option>
                </select> -->
            </div>
            <br><hr>
            <div class="model__footer">
                <button class="btn btn-light mr-2 btn__close--modal" @click="closeModel()">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal " >Save</button>
            </div>
        </div>
    </div>
    
    <br><br><br>
    </div>
</template>