<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Order;
Use App\Customer;
Use Illuminate\Support\Facades\DB;
Use App\Http\Resources\Order as OrderResource;
Use App\CharismaSoap;
Use App\PricesCharisma;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #$order=Order::find(1)->customer;
        /*
        $orders=DB::table('orders')
            ->join('customers','orders.customer_id','=','customers.id')
            //->ord
            ->paginate(3)->toArray();
        */
        #return OrderResource::collection($orders);
        return view('orders.index');

    }

    public function companyExistsInCharisma($fiscalRegistration)
    {
        $client = new CharismaSoap(1);
        $company = $client->getPartner(['FiscalRegistration' => $fiscalRegistration]);
        var_dump($company);
        if (!empty($company->PartnerList)) {
            return 1;
        }
        return 0;
    }


    /*
     * Get all the cities from Charisma and fill 'city_id' table with them
     */
    public function getCityIdFromCharisma()
    {
        DB::table('city_id')->truncate();
        $client = new CharismaSoap(1);
        $city_id_list = $client->getCity();
        $city_id_list = $city_id_list->City;
        $city_list = [];
        $i = 0;

        #echo '<pre>';var_dump($city_id_list);die();
        foreach ($city_id_list as $each) {
            $i++;
            $city_list[$i]['CityCode'] = $each->CityCode;//json_decode(json_encode($each),true);
            $city_list[$i]['CityId'] = $each->CityId;
            $city_list[$i]['CityName'] = $each->CityName;
            $city_list[$i]['DistrictId'] = !empty($each->DistrictId) ? $each->DistrictId : 'x';
            $city_list[$i]['DistrictName'] = !empty($each->DistrictName) ? $each->DistrictName : 'x';

            if ($i % 500 == 0) {
                DB::table('city_id')->insert($city_list);
                $city_list = [];
                $i = 0;

            }
        }


        DB::table('city_id')->insert($city_list);

    }


    /*
     * Match Charisma city and
     * @return Object contains the city info from Charisma
     */
    private function matchCity($city_name)
    {
        # Query-ul trebuie sa fie: select *,MATCH(CityName) AGAINST('Bucov') from city_id  where MATCH(CityName) AGAINST('Bucov')

        $query = DB::table('city_id')
            ->selectRaw("*,MATCH(CityName) AGAINST('$city_name')")
            ->whereRaw("MATCH(CityName) AGAINST('$city_name')")
            ->first();
        if (empty($query)) {
            return 0;
        }
        return $query;
    }


    public function createCharismaOrder($user_id_charisma, $order_array)
    {
        print($user_id_charisma);
        die();
        echo 'test';
        $client = new CharismaSoap();
        $data = [

            'OrderReq' => [
                'ExternalId' => '3345',
                'PartnerId' => '228587',
                'BillingAddressId' => '234010',
                'OrderDate' => '2020-09-11',
                'PaymentType' => 'Card',
                'Comments' => 'nu sunt',
                'OrderState' => 'Draft',
                'ItemList' => [
                    [
                        'ExternalId' => '12345678',
                        'ItemId' => '3998',
                        'ItemName' => 'Name here...xxx',
                        'Quantity' => '5',
                        'Price' => '10',
                        'PriceWithVAT' => '12.4',
                        'VATId' => '28',
                        'Amount' => '50',
                        'VATAmount' => '12',
                        'IsPriceWithVAT' => 'true',
                        'CurrencyId' => '25',
                        'DeliveryAddressId' => '234010',
                    ],
                    [
                        'ExternalId' => '14545',
                        'ItemId' => '1946',
                        'ItemName' => 'Name here...xxx',
                        'Quantity' => '8',
                        'Price' => '10',
                        'PriceWithVAT' => '12.4',
                        'VATId' => '28',
                        'Amount' => '50',
                        'VATAmount' => '12',
                        'IsPriceWithVAT' => 'true',
                        'CurrencyId' => '25',
                        'DeliveryAddressId' => '234010',
                    ]
                ],


            ]
        ];


        $order_response = $client->createOrder($data);
        var_dump($order_response);


    }

    private function getItemFromCharisma($cod_charisma)
    {
        $query = DB::table('nomenclator')->where('ItemCode', '=', $cod_charisma)->leftJoin('prices_charisma', 'nomenclator.ItemId', '=', 'prices_charisma.ItemId')->first();
        return $query;
    }

    public function adaugaComandaPersoanaFizica(Request $request)
    {
        $woocommerce_order = $request->post();
        #echo gettype($woocommerce_order);
        #echo '<pre>';var_dump($woocommerce_order['woocommerce_order']['order']);
        $match = $this->matchCity($woocommerce_order['woocommerce_order']['order']['shipping_address']['city']);
        $client = new CharismaSoap();
        $data = ['PartnerReq' => ['ExternalId' => $woocommerce_order['woocommerce_order']['order']['id'], // nu are, ii pun id-ul 'extern', adica id user woocommerce,
            'PartnerType' => 'PF',
            'PartnerName' => $woocommerce_order['woocommerce_order']['order']['billing_address']['last_name'] . ' ' . $woocommerce_order['woocommerce_order']['order']['billing_address']['first_name'],
            'FiscalRegistration' => $woocommerce_order['woocommerce_order']['order']['id'], // nu are, ii pun id-ul 'extern', adica id user woocommerce,
            //'ComercialRegistration' => 'J40/23/1991',
            'IsVATPayer' => 'true',
            'Email' => $woocommerce_order['woocommerce_order']['order']['billing_address']['email'],

            'DeliveryAddressList' => [
                'Name' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['address_2'],
                'Street' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['address_1'],
                'LocalNumber' => '0',
                'City' => ['CityId' => $match->CityId,
                    'CityName' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['city'],
                ],
            ],

        ]];
        $partner = $client->createPartner($data);

        $partner_id = $partner->PartnerResp->PartnerId;
        $billing_address_id = $partner->PartnerResp->DeliveryAddressList->PartnerAddressId;
        $produse = $woocommerce_order['woocommerce_order']['order']['line_items'];

        $ItemList = [];
        $i = 0;
        foreach ($produse as $each) {
            $charisma_item = $this->getItemFromCharisma($each['sku']);
            $ItemList[$i] = ['ExternalId' => $each['id'],
                'ItemId' => $charisma_item->ItemId,
                'ItemName' => $each['name'],
                'Quantity' => $each['quantity'],
                'Price' => $each['price'],//+$each['price']*($charisma_item->VATId/100),
                'PriceWithVAT' => $each['price'],// $each['price'],
                'VATId' => $charisma_item->VATId,
                'Amount' => $each['subtotal'],
                'VATAmount' => $charisma_item->VATPercent / 100 * $each['price'] * $each['quantity'],
                'IsPriceWithVAT' => 'true',
                'CurrencyId' => $charisma_item->CurrencyId,
                'DeliveryAddressId' => $billing_address_id,
            ];
            $i++;
        }

        $PaymentType = 'Card';
        if ($woocommerce_order['woocommerce_order']['order']['payment_details']['method_title'] == 'Plata la primirea coletului') {
            $PaymentType = 'Card'; // 'cash';
        }


        # $client = new CharismaSoap();
        $data = [
            'OrderReq' => [
                'ExternalId' => $woocommerce_order['woocommerce_order']['order']['id'],
                'PartnerId' => $partner_id,//'229238',
                'BillingAddressId' => $billing_address_id,//'235040',
                'OrderDate' => date('Y-m-d'),//data din site (probabil...) $woocommerce_order['woocommerce_order']['order']['created_at'], //'2020-09-17',
                'PaymentType' => $PaymentType,
                'Comments' => 'nu sunt',
                'OrderState' => 'Draft',
                'ItemList' => $ItemList,
            ]
        ];

        $order_response = $client->createOrder($data);


    }


    public function adaugaComandaPersoanaJuridica(Request $request)
    {
        $woocommerce_order = $request->post();
        $match = $this->matchCity($woocommerce_order['woocommerce_order']['order']['shipping_address']['city']);
        $client = new CharismaSoap();


        $data = ['PartnerReq' => ['ExternalId' => $woocommerce_order['woocommerce_order']['order']['order_number'],
            'PartnerType' => 'PJ',
            'PartnerName' => $woocommerce_order['woocommerce_order']['order']['Nume Companie'],
            'FiscalRegistration' => $woocommerce_order['woocommerce_order']['order']['CUI'],
            'ComercialRegistration' => $woocommerce_order['woocommerce_order']['order']['Registrul Comertului'],
            'IsVATPayer' => 'true',
            'Email' => $woocommerce_order['woocommerce_order']['order']['billing_address']['email'],
            'DeliveryAddressList' => [
                'Name' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['address_2'],
                'Street' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['address_1'],
                'LocalNumber' => '0',
                'City' => ['CityId' => $match->CityId,
                    'CityName' => $woocommerce_order['woocommerce_order']['order']['shipping_address']['city'],
                ],
            ],
            'ContactInfoList' => [
                'PersonName' => $woocommerce_order['woocommerce_order']['order']['billing_address']['last_name'] . ' ' . $woocommerce_order['woocommerce_order']['order']['billing_address']['first_name'],
                'Email' => $woocommerce_order['woocommerce_order']['order']['billing_address']['email'],
                'MobileNumber' => $woocommerce_order['woocommerce_order']['order']['billing_address']['phone'],

            ]

        ]];

        $partner = $client->createPartner($data);

#        echo '<pre>';var_dump($partner);

        $partner_id = $partner->PartnerResp->PartnerId;
        $billing_address_id = $partner->PartnerResp->DeliveryAddressList->PartnerAddressId;

        $produse = $woocommerce_order['woocommerce_order']['order']['line_items'];
        $ItemList = [];
        $i = 0;
        foreach ($produse as $each) {
            $charisma_item = $this->getItemFromCharisma($each['sku']);

            $ItemList[$i] = ['ExternalId' => $each['id'],
                'ItemId' => $charisma_item->ItemId,
                'ItemName' => $each['name'],
                'Quantity' => $each['quantity'],
                'Price' => $each['price'],//+$each['price']*($charisma_item->VATId/100),
                'PriceWithVAT' => $each['price'],// $each['price'],
                'VATId' => $charisma_item->VATId,
                'Amount' => $each['subtotal'],
                'VATAmount' => $charisma_item->VATPercent / 100 * $each['price'] * $each['quantity'],
                'IsPriceWithVAT' => 'true',
                'CurrencyId' => $charisma_item->CurrencyId,
                'DeliveryAddressId' => $billing_address_id,
            ];
            $i++;
        }

        $PaymentType = 'Card';
        if ($woocommerce_order['woocommerce_order']['order']['payment_details']['method_title'] == 'Plata la primirea coletului') {
            $PaymentType = 'Cash';
        }


        # $client = new CharismaSoap();
        $data = [
            'OrderReq' => [
                'ExternalId' => $woocommerce_order['woocommerce_order']['order']['id'],
                'PartnerId' => '229238',
                'BillingAddressId' => '235040',
                'OrderDate' => '2020-09-17',
                'PaymentType' => $PaymentType,
                'Comments' => 'nu sunt',
                'OrderState' => 'Draft',
                'ItemList' => $ItemList,
            ]
        ];

        $order_response = $client->createOrder($data);


    }


    public function listOrders()
    {
        $orders = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->paginate(20)
            ->toArray();

        return $orders;
    }

    /**
     *Calling website api
     * @param int $id the project id from projects database table
     * @param int $order_id the order id coming from website
     * @return Object an object containing the website API response
     */
    private function getOrderArray($project_id, $order_id)
    {
        $project = DB::table('projects')->where('id', '=', $project_id)->first();
        $username_password = 'consumer_key=' . $project->username . '&consumer_secret=' . $project->password;
        $url = $project->url . '/wc-api/v3/orders/' . $order_id . '/?' . $username_password;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            //If an error occured, throw an Exception.
            throw new Exception(curl_error($ch));
        }
        return json_decode($response);
    }


    public function rewriteDatabasePrices($project_id = 1)
    {
        $client = new CharismaSoap($project_id);
        $price_list = $client->getPrice(['PriceListDate' => date('Y-m-d')]);
        $charisma_prices = new PricesCharisma($project_id);
        $charisma_prices->rewriteDatabasePrices($price_list);
        DB::table('options')->where(['var_name' => 'prices_table_last_update', 'project_id' => $project_id])->update(['var_value' => date('Y-m-d')]);

    }

    public function rewriteDatabaseNomenclator($project_id = 1)
    {
        $client = new CharismaSoap($project_id);
        $nomenclator = $client->getItem();
        $nomenclator = $nomenclator->ItemList;
        DB::table('nomenclator')->truncate();
        $i = 0;
        $dataContainer = [];
        foreach ($nomenclator as $each) {
            $i++;
            $data = [];
            $data['ItemId'] = $each->ItemId;
            $data['ItemCode'] = $each->ItemCode;
            $data['ItemName'] = $each->ItemName;
            $data['ItemTypeId'] = $each->ItemTypeId;
            $data['ItemType'] = $each->ItemType;
            $data['MeasuringUnitId'] = $each->MeasuringUnitId;
            $data['MeasuringUnitName'] = $each->MeasuringUnitName;
            $data['MeasuringUnitCode'] = $each->MeasuringUnitCode;
            $data['VATId'] = $each->VATId;
            $data['VATPercent'] = $each->VATPercent;
            $data['IsValid'] = $each->IsValid;
            $data['IsStockable'] = $each->IsStockable;
            $data['IsComposite'] = $each->IsComposite;

            $data['Grupa'] = $data['CategorieArticol'] = $data['Familie'] = $data['Subfamilie'] = $data['Brand'] = ''; // initializam cu nimic pentru a nu genera erori la inserare in baza de date
            /*
            if (isset($each->ItemProperties)) {
                $data['Grupa'] = isset($each->ItemProperties[0]->PropertyValue) ? $each->ItemProperties[0]->PropertyValue : '';
                $data['CategorieArticol'] = isset($each->ItemProperties[2]->PropertyValue) ? $each->ItemProperties[2]->PropertyValue : '';
                $data['Familie'] = isset($each->ItemProperties[3]->PropertyValue) ? $each->ItemProperties[3]->PropertyValue : '';
                $data['Subfamilie'] = isset($each->ItemProperties[4]->PropertyValue) ? $each->ItemProperties[4]->PropertyValue : '';
                $data['Brand'] = isset($each->ItemProperties[5]->PropertyValue) ? $each->ItemProperties[5]->PropertyValue : '';
            }
            */

            $data['ConversionRate'] = $data['BaseMeasuringUnitId'] = $data['BaseMeasuringUnitName'] = $data['BaseMeasuringUnitCode'] = '';// initializam cu nimic pentru a nu genera erori la inserare in baza de date
            if (isset($each->ItemMeasuringUnits)) {
                $data['ConversionRate'] = isset($each->ItemMeasuringUnits->ConversionRate) ? $each->ItemMeasuringUnits->ConversionRate : '';
                $data['BaseMeasuringUnitId'] = isset($each->ItemMeasuringUnits->BaseMeasuringUnitId) ? $each->ItemMeasuringUnits->BaseMeasuringUnitId : '';
                $data['BaseMeasuringUnitName'] = isset($each->ItemMeasuringUnits->BaseMeasuringUnitName) ? $each->ItemMeasuringUnits->BaseMeasuringUnitName : '';
                $data['BaseMeasuringUnitCode'] = isset($each->ItemMeasuringUnits->BaseMeasuringUnitCode) ? $each->ItemMeasuringUnits->BaseMeasuringUnitCode : '';
            }

            $data['BarCodeId'] = $data['SiteId'] = $data['BarCode'] = $data['IsVariable'] = '';
            if (isset($each->ItemBarCodes)) {
                $data['BarCodeId'] = isset($each->ItemBarCodes->BarCodeId) ? $each->ItemBarCodes->BarCodeId : '';
                $data['SiteId'] = isset($each->ItemBarCodes->SiteId) ? $each->ItemBarCodes->SiteId : '';
                $data['BarCode'] = isset($each->ItemBarCodes->BarCode) ? $each->ItemBarCodes->BarCode : '';
                $data['IsVariable'] = isset($each->ItemBarCodes->IsVariable) ? $each->ItemBarCodes->IsVariable : '';

            }

            $dataContainer[] = $data;
            if ($i % 1500 == 0) {
                DB::table('nomenclator')->insert($dataContainer);
                $dataContainer = [];
            }
            #$data['']=$each->;


        }

        if (count($dataContainer) > 0) {
            DB::table('nomenclator')->insert($dataContainer);
        }

        DB::table('options')->where(['var_name' => 'nomenclator_table_last_update', 'project_id' => $project_id])->update(['var_value' => date('Y-m-d')]);

    }


    /**
     * @param $skus array Array of sku's
     * @return array of skus and Charisma prices
     */
    private function getCharismaPrices($skus)
    {
        $prices = DB::table('nomenclator')->select(['nomenclator.ItemId', 'nomenclator.ItemCode', 'nomenclator.ItemName', 'prices_charisma.PriceWithVAT'])
            ->whereIn('ItemCode', $skus)
            ->leftJoin('prices_charisma', 'nomenclator.ItemId', '=', 'prices_charisma.ItemId')
            ->get();

        $price_array = [];
        foreach ($prices as $price) {
            $price_array[$price->ItemCode] = $price->PriceWithVAT;
        }
        return $price_array;
    }

    /*
     * Takes care of changing the SKU, quantity and price for special products (products with sku's like:  2101111031-24  => Dog Concept Plic Pui 100 g   24 buc/bax: 18+6 GRATIS
     *
     */
    private function substringSKUFromPromotions($response)
    {
        foreach ($response->order->line_items as $index => $each) {
            if (strpos($each->sku, '-') !== false) {
                $response->order->line_items[$index]->name = $response->order->line_items[$index]->name . ' (cantit. pe buc.)'; //'sss';
                $response->order->line_items[$index]->quantity = substr($each->sku, strpos($each->sku, "-") + 1); //1;
                $response->order->line_items[$index]->sku = substr($each->sku, 0, strpos($each->sku, "-")); //'2101111031';
                $response->order->line_items[$index]->price = round($each->price / $response->order->line_items[$index]->quantity, 2); //'1.89';
            }

        }

        return $response;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param int $id The project id from projects table
     * @param int $order_id The order id received from Wordpress
     * @return \Illuminate\Http\Response
     */
    public function newOrder($id, $order_id)
    {
        $response = $this->getOrderArray($id, $order_id);
        $response = $this->substringSKUFromPromotions($response);
        $prices_table_last_update = DB::table('options')->where('var_name', 'prices_table_last_update')->first(); // si projectid-ul trebuie aici... id
        $nomenclator_table_last_update = DB::table('options')->where('var_name', 'nomenclator_table_last_update')->first();

        $products_from_order = $response->order->line_items;


        $skus = [];
        foreach ($products_from_order as $each_product) {
            $skus[] = $each_product->sku;
        }

        $charisma_prices = $this->getCharismaPrices($skus);


        return view('orders.newOrder', ['woocommerceOrder' => $response,
                'prices_table_last_update' => $prices_table_last_update->var_value,
                'nomenclator_table_last_update' => $nomenclator_table_last_update,
                'charisma_prices' => $charisma_prices,
                'project_id' => $id]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /*
 public function addClientToCharisma($pf_data)
 {

     $cnp = '1850709297247';

     $client = new CharismaSoap();
     $data = ['PartnerReq' => ['ExternalId' => 'ext01',
         'PartnerType' => 'PF',
         'PartnerName' => 'Persoanaxxl',
         'FiscalRegistration' => '1850709297247',
         //'ComercialRegistration' => 'J40/23/1991',
         'IsVATPayer' => 'true',
         'Email' => 'xxlcontact3@firma.ro',

         'DeliveryAddressList' => [
             'Name' => 'Adresa de livrare',
             'Street' => 'Sos Armatei',
             'LocalNumber' => '15',
             'City' => ['CityId' => '420',
                 'CityName' => 'Bucuresti Sector 4',
             ],
         ],

     ]];
     $partner = $client->createPartner($data);
     var_dump($partner);
 }

 */


}
