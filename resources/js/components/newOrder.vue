<script src="../../../node_modules/http-proxy-middleware/lib/router.js"></script>


<template>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-7">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <div class="prices-latest-update">

                        <button v-on:click="refreshPreturi()" :disabled="loading_prices==1">

                            <template v-if="loading_prices">
                                <div class="loader">
                                </div>
                                Asteapta te rog, extrag preturile din Charisma
                            </template>

                            <template v-else>
                                Reincarca Preturile
                            </template>

                        </button>
                        <p>
                            Ultimul update al preturilor din Charisma: <span class="bold">{{prices_table_last_update.date}}</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <div class="card-body">
                        <table class="table-bordered">
                            <tr>
                                <td>Nume</td>
                                <td>{{woocommerce_order.order.billing_address.first_name}}
                                    {{woocommerce_order.order.billing_address.last_name}}
                                </td>
                            </tr>
                            <tr>
                                <td>Id comanda:</td>
                                <td> {{woocommerce_order.order.order_number}}</td>
                            </tr>
                            <tr>
                                <td>Email Pers Fizica</td>
                                <td>{{woocommerce_order.order.customer.email}}</td>
                            </tr>
                            <template v-if="woocommerce_order.order['Nume Companie']">
                                <tr>
                                    <td><b>Companie:</b></td>
                                    <td>{{woocommerce_order.order['Nume Companie']}}</td>
                                </tr>
                                <tr>
                                    <td>CUI</td>
                                    <td>{{woocommerce_order.order['CUI']}}</td>
                                </tr>
                                <tr>
                                    <td>Registrul Comertului</td>
                                    <td>{{woocommerce_order.order['Registrul Comertului']}}</td>
                                </tr>
                                <tr>
                                    <td>Email companie</td>
                                    <td>{{woocommerce_order.order.billing_address.email}}</td>
                                </tr>
                            </template>
                        </table>


                        {{woocommerce_order}}

                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2>Produse:</h2>
                <table class="table">
                    <tr>
                        <th>SKU</th>
                        <th>Nume</th>
                        <th>Pret Unitar</th>
                        <th>Cantitate</th>
                        <th>Subtotal</th>
                        <th>Total cu taxe</th>
                    </tr>
                    <tr v-for="product in this.woocommerce_order.order.line_items">
                        <td>{{product.sku}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.price}}</td>
                        <td>{{product.quantity}}</td>
                        <td>{{product.subtotal}}</td>
                        <td>{{product.total}}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{theroute()}}<br />
        ss{{project_id.project_id}}aa
    </div>
</template>




<script>
    export default {
        mounted() {
            // console.log('youhooo');

            // console.log(this.woocommerce_order.order.line_items);
            /*
            axios.get('/charismapp/public/api/listOrders',{})
                .then(response =>{
                    //console.log(response);
                    this.orders=response.data;
                    console.log(this.orders.data);
                })
            ;

             */
        },

        computed: {},

        props: [
            'text',
            'woocommerce_order',
            'prices_table_last_update',
            'project_id'


            //'name',
        ],

        data: function () {
            return {
                test: null,
                loading_prices: 0,


            }
        },
        created() {
        },

        methods: {
            getOrder() {
                //console.log(this.woocommerce_order.line_items);
                //return  this.woocommerce_order.order.order_number;

            },
            refreshPreturi: function () {
                axios.get('/charismapp/public/rewriteDatabasePrices/'+this.project_id.project_id, {})
                    .then(response => {
                        //console.log(response);


                        //console.log(this.orders.data);
                    })
                    .then(response => {
                        this.loading_prices = 0;
                        location.reload(true);
                    })

                this.loading_prices = 1; // punem sa astepte

            },

            theroute: function(){
                return window.location.href;
            },

        }
    }
</script>

