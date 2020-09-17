@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('All Orders') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div id="app">

                            <neworder v-bind:woocommerce_order='<?php echo json_encode($woocommerceOrder);?>'
                                      v-bind:prices_table_last_update='<?php echo json_encode(['date'=>$prices_table_last_update]);?>'
                                      v-bind:project_id='<?php echo json_encode(['project_id'=>$project_id]);?>'
                                      v-bind:nomenclator_table_last_update='<?php echo json_encode(['nomenclator_table_last_update'=>$nomenclator_table_last_update]);?>'
                                      v-bind:charisma_prices='<?php echo json_encode(['charisma_prices'=>$charisma_prices]);?>'>

                            </neworder>

                        </div>


                    </div>


                </div>
            </div>
        </div>
    </div>


@endsection
