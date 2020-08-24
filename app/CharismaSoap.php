<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
Use Illuminate\Support\Facades\DB;
Use SoapClient;
Use SoapHeader;

class CharismaSoap extends Model
{
    private $wsdl_file_url;
    private $charisma_application_id;
    private $client;
    private $headerbody;
    private $header;

    public function __construct($project_id)
    {
        $wsdl_file_url = DB::table('options')->where([
            ['var_name', '=', 'wsdl_file_url'],
            ['project_id', '=', $project_id],
        ])->first();

        $charisma_application_id = DB::table('options')->where([
            ['var_name', '=', 'charisma_application_id'],
            ['project_id', '=', $project_id],
        ])->first();

        $this->client = new SoapClient($wsdl_file_url->var_value, ['trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE]);
        $this->headerbody = ['ApplicationId' => $charisma_application_id->var_value];
        $this->header = new SoapHeader('totalsoft.charisma.commonMessages', 'IdentificationMessage', $this->headerbody);
        $this->client->__setSoapHeaders($this->header);
    }

    /*
     * @param array $data Array of values to send to getPrice SOAP method
     */
    public function getPrice($data)
    {
        return $this->client->getPrice($data);
    }

    /*
     * @param array $data Array of values to send to getItem SOAP method
     * @return Object  returns a Charisma SOAP object, returns the entire list of products from CharismaZoomania SQL database (entire nomenclator)
     */
    public function getItem($data=null)
    {
        return $this->client->GetItem($data);
    }

}
