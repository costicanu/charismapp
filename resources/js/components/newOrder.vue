<script src="../../../node_modules/http-proxy-middleware/lib/router.js"></script>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <button type="button" class="my-button" v-text="test">sssv</button>
                    <div class="card-body">
                        <table class="table-bordered">
                            <tr>
                                <td>Nume</td>
                                <td>{{woocommerce_order.order.billing_address.first_name}} {{woocommerce_order.order.billing_address.last_name}}</td>
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
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</template>


<script>
    export default {
        mounted() {
            console.log(this.woocommerce_order.order.line_items);
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
        props: [
            'text',
            'woocommerce_order'


            //'name',
        ],

        data: function () {
            return {
                test: null,


            }
        },
        created() {
        },

        methods: {
            getOrder() {
                console.log(this.woocommerce_order.line_items);
                //return  this.woocommerce_order.order.order_number;

            },
        }
    }
</script>

