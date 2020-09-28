<script src="../../../node_modules/http-proxy-middleware/lib/router.js"></script>


<template>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="card-body" id="update-nomenclator-div">
                    <button v-on:click="refreshNomenclator()" :disabled="loading_nomenclator==1">
                        <template v-if="loading_nomenclator">
                            <div class="loader">
                            </div>
                            Asteapta te rog, extrag nomenclatorul din Charisma
                        </template>

                        <template v-else>
                            Reincarca Nomenclator
                        </template>
                    </button>
                    <p>
                        Ultimul update al nomenclatorului de produse din Charisma: <span class="bold">{{nomenclator_table_last_update.nomenclator_table_last_update.var_value}}</span>
                    </p>
                </div>
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
                        <th>UnitarCharisma</th>
                        <th>Cantitate</th>
                        <th>Subtotal</th>
                        <th>Total cu taxe</th>
                    </tr>
                    <tr v-for="product in this.woocommerce_order.order.line_items">
                        <td>{{product.sku}}</td>
                        <td>{{product.name}}</td>
                        <td>
                            <span class="product-price">{{product.price}}</span>
                            <template v-if="charisma_prices.charisma_prices[product.sku]!=product.price">
                                <span class="price-warning blinking">&#9888;</span>
                            </template>

                        </td>
                        <td><span class="orange">{{charisma_prices.charisma_prices[product.sku]}}</span></td>
                        <td>{{product.quantity}}</td>
                        <td>{{product.subtotal}}</td>
                        <td>{{product.total}}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{theroute()}}<br/>
        ss{{project_id.project_id}}aa


        <template v-if="woocommerce_order.order['Nume Companie']">
            <button id="verifica_companie" class="button" v-on:click="verifica_companie()"
                    :disabled="loading_verifica_companie==1">
                Verifica Companie
                <template v-if="loading_verifica_companie">
                    <div class="loader">
                    </div>
                    Asteapta te rog, verific compania
                </template>
            </button>


            <button id="adaugaComandaPersoanaJuridica" class="button" v-on:click="adaugaComandaPersoanaJuridica()">
                Adauga Comanda Persoana Juridica

            </button>


            <template v-if="companie_in_baza_de_date">
                <div class="col-md-12">
                    <p>
                        Compania exista deja in Charisma!
                    </p>
                    <p>

                    </p>
                </div>


            </template>

        </template>

        <template v-else>

            <button id="adauga_comanda_persoana_fizica" class="button" v-on:click="adauga_comanda_persoana_fizica()"
                    :disabled="loading_verifica_companie==1">
                Adauga Comanda In Charisma PF
                <template v-if="loading_verifica_companie">
                    <div class="loader">
                    </div>
                    Asteapta te rog, verific compania
                </template>
            </button>

        </template>

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
            'project_id',
            'nomenclator_table_last_update',
            'charisma_prices'



            //'name',
        ],

        data: function () {
            return {
                test: null,
                loading_prices: 0,
                loading_nomenclator: 0,
                loading_verifica_companie: 0,
                companie_in_baza_de_date: 0,
                charisma_user_id: 0,


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
                axios.get('/charismapp/public/rewriteDatabasePrices/' + this.project_id.project_id, {})
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

            refreshNomenclator: function () {
                axios.get('/charismapp/public/rewriteDatabaseNomenclator/' + this.project_id.project_id, {})
                    .then(response => {
                        this.loading_nomenclator = 0;
                        location.reload(true);
                    });
                this.loading_nomenclator = 1;
            },

            verifica_companie: function () {
                this.loading_verifica_companie = 1;
                let self = this;
                axios.get('/charismapp/public/companyExistsInCharisma/' + this.woocommerce_order.order['CUI'].replace(/[^a-z0-9]/gi, ''), {})
                    .then(function (response) {
                        self.loading_verifica_companie = 0;
                        console.log(response.data);
                        if (response.data) { // if company not in the Charisma database
                            self.companie_in_baza_de_date = 1;
                        }

                    });

            },

            adauga_comanda_persoana_fizica: function () {
                axios.post('/charismapp/public/adaugaComandaPersoanaFizica', {woocommerce_order: this.woocommerce_order})
                    .then(function (response) {
                        self.charisma_user_id = response.data;
                        // console.log(response.data);

                        //return response.data;
                    });

            },

            adaugaComandaPersoanaJuridica: function () {
                axios.post('/charismapp/public/adaugaComandaPersoanaJuridica',{woocommerce_order: this.woocommerce_order})
                    .then(function (response){
                    });
            },

            adauga_xxl: function () {
                console.log('IT WORKS!!!!');
            },

            theroute: function () {
                return window.location.href;
            },

        }
    }
</script>

