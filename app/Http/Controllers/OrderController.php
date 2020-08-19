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

    public function listOrders()
    {
        $orders = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            //->ord
            ->paginate(20)
            //->first()
            ->toArray();
        # return $orders;
        //return ['name'=>'test1','price'=>'price1'];

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
        $url = $project->url.'/wc-api/v3/orders/' . $order_id . '/?' . $username_password;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            //If an error occured, throw an Exception.
            throw new Exception(curl_error($ch));
        }
        return json_decode($response);
    }



    public function rewriteDatabasePrices($project_id=1){
        $client=new CharismaSoap($project_id);
        $price_list=$client->getPrice([ 'PriceListDate' => date('Y-m-d')]);
        $charisma_prices=new PricesCharisma($project_id);
        $charisma_prices->rewriteDatabasePrices($price_list);
        DB::table('options')->where(['var_name'=>'prices_table_last_update','project_id'=>$project_id])->update(['var_value'=> date('Y-m-d')]);

    }

    public function test(){
        $project = DB::table('projects')->where('id', '=', $project_id)->first();
        $username_password = 'consumer_key=' . $project->username . '&consumer_secret=' . $project->password;
        $url = $project->url.'/wc-api/v3/orders/' . $order_id . '/?' . $username_password;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            //If an error occured, throw an Exception.
            throw new Exception(curl_error($ch));
        }
        return json_decode($response);
       # sleep(7);
       # return '2203';
        return 'x';
    }



    private function charismaSoap(){

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

        $prices_table_last_update= DB::table('options')->where('var_name','prices_table_last_update')->first(); // si projectid-ul trebuie aici... id

        return view('orders.newOrder',['woocommerceOrder'=>$response,'prices_table_last_update'=>$prices_table_last_update->var_value,'project_id'=>$id]);
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
}
