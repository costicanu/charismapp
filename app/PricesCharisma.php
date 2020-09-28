<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
Use Illuminate\Support\Facades\DB;

class PricesCharisma extends Model
{

    protected $table = 'prices_charisma';
    protected $project_id;

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function project()
    {
        return $this->hasOne('\App\Project');
    }

    /*  @param $price_list Prices array from Charisma SOAP API
     *
     */
    public function rewriteDatabasePrices($price_list)
    {
        $test = DB::table($this->table)->where('project_id', '=', $this->project_id)->delete();
        if(empty($price_list)){
           return 0;
           if(!is_array($price_list->PriceList->PriceListDetail)){
               return 0;
           }
        }

        #echo '<pre>';var_dump($price_list->PriceList->PriceListDetail); die();
        $i=-1;$thousand_records=[];
        foreach($price_list->PriceList->PriceListDetail as $each){
            $i++;
            $thousand_records[$i]=[
                'ItemId'=>$each->ItemId,
                'Price'=>$each->Price,
                'PriceWithVAT'=>$each->PriceWithVAT,
                'CurrencyId'=>$each->CurrencyId,
                'VATPercent'=>$each->VATPercent,
                'VATId'=>$each->VATId,
                'MeasuringUnitId'=>$each->MeasuringUnitId,
                'project_id'=>$this->project_id
            ];
            if($i%1000==0&&$i>0){
                DB::table($this->table)->insert($thousand_records);
                $i=-1;
                unset($thousand_records);
                $thousand_records=[];
            }

        }

        DB::table($this->table)->insert($thousand_records); // insert latest records also (everything  over last multiple of 1000


    }





}
