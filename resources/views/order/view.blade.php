<?php
/**
 * @var $order App\Order
 */
?>

@extends('app')

@section('content')
    <div class="page-header">
        <h1>Order info</h1>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Weight</td>
            <td>Length</td>
            <td>Width</td>
            <td>Height</td>
            <td>Amount</td>
        </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->weight}}</td>
                <td>{{$item->length}}</td>
                <td>{{$item->width}}</td>
                <td>{{$item->height}}</td>
                <td>{{$item->pivot->amount}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-lg-12 col-lg-offset-10">
            <p class="text-info">Total <strong id="price">{{$order->price}}</strong></p>
        </div>
    <div/>
@endsection
