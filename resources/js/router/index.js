import { createRouter , createWebHistory } from "vue-router";
import NotFound from "../components/NotFound.vue";
import InvoiceIndex from "../components/invoices/index.vue";
import InvoiceNew from "../components/invoices/new.vue";
import InvoiceShow from "../components/invoices/show.vue";


const router = createRouter({
    history: createWebHistory(),
    routes:[
        {
            path: '/',
            component: InvoiceIndex 
        },
        {
            path: '/invoices/new',
            component: InvoiceNew 
        },
        {
            path: '/invoices/show:id',
            component: InvoiceShow ,
            props:true
        },
        {
            path: '/:pathMatch(.*)*',
            component: NotFound 
        }
    ]
   
});

export default router;