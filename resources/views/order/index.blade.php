<?php
/**
 * @var $orders App\Order[]
 */
?>
@extends('app')

@section('content')
<div class="page-header">
    <h1>List Orders</h1>
</div>

<table class="table table-bordered">
    <thead>
    <tr>
        <td>№</td>
        <td>Zip code from</td>
        <td>Zip code to</td>
        <td>Created at</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->zipcode_from}}</td>
            <td>{{$order->zipcode_to}}</td>
            <td>{{$order->created_at->format('j M H:i')}}</td>
            <td class="action-col">
                <a href="{{url('order/view', ['id'=>$order->id])}}" class="btn btn-sm btn-info">view</a>
                <a href="{{url('order/update', ['id'=>$order->id])}}" class="btn btn-sm btn-success">update</a>
                <a href="{{url('order/delete', ['id'=>$order->id])}}" class="btn btn-sm btn-danger">delete</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection