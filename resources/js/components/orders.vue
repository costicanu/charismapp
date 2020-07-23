<script src="../../../node_modules/http-proxy-middleware/lib/router.js"></script>
<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <button type="button" class="my-button" v-text="test">sssv</button>
                    <div class="card-body">
                            <table class="table">
                            <tr v-for="order in orders.data">
                                <td>
                                    {{order.order_id}}
                                </td>
                                <td>
                                    {{order.billing_first_name}} {{order.billing_last_name}}
                                </td>
                                <td>
                                    {{order.billing_phone}}
                                </td>
                                <td>
                                    {{order.billing_city}}
                                </td>
                                <td>

                                </td>
                            </tr>

                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        mounted() {
            console.log('Component mounted.');
            axios.get('/charismapp/public/api/listOrders',{})
                .then(response =>{
                    //console.log(response);
                    this.orders=response.data;
                    console.log(this.orders.data);
                })
            ;
        },
        props:[
            'text',
           //'name',
        ],

        data: function(){
            return {
                test: null,
                orders: [], //contains full orders, pagination included
                name: ''
            }
        },

        created(){

        },

        methods:{

        }
    }
</script>

