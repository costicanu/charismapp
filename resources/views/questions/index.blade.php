@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('All Orders') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="content">
                            <table>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        {{$order->order_id}}
                                    </td>
                                </tr>
                            @endforeach
                            </table>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
