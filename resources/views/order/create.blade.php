<?php
/**
 * @var $errors \Illuminate\Support\MessageBag
 * @var $items \App\Item[]
 */
?>
@extends('app')

@section('content')
    <div class="page-header">
        <h1>Создание заказа</h1>
    </div>

    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Упс!</strong> Некоторые проблемы.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-horizontal" method="post" id="form-order">
        <div class="form-group">
            <label for="zip-code-from" class="col-lg-3 control-label">Индекс места отправления</label>
            <div class="col-lg-2">
                <input type="text" name="zip_code_from" class="form-control" id="zip-code-from" maxlength="6">
            </div>
        </div>
        <div class="form-group">
            <label for="zip-code-to" class="col-lg-3 control-label">Индекс места прибытия</label>
            <div class="col-lg-2">
                <input type="text" name="zip_code_to" class="form-control" id="zip-code-from"  maxlength="6">
            </div>
        </div>

        <div id="order-items">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <td>Наименование</td>
                    <td>Вес (кг)</td>
                    <td>Ширина (см)</td>
                    <td>Длинна (см)</td>
                    <td>Высота (см)</td>
                    <td style="width: 150px;">Количество (ед)</td>
                    <td style="width: 70px;"></td>
                </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="form-group" id="select-item">
                <div class="input-group col-lg-5">
                    <span class="input-group-btn">
                        <button class="btn btn-default add-item" type="button">Добавить позицию</button>
                    </span>
                    <select class="form-control" name="added-item">

                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
@endsection

@section('pos_end')
    <script src="{{asset('js/order-create.js')}}"></script>
@endsection