/**
 * Created by ps on 07.05.16.
 */
"use strict";

var App = function(el) {
    var form = $(el),                                               // форма
        orderItemsEl = form.find('#order-items'),                   // секция с товарами
        orderItemsTableEl = orderItemsEl.find('table'),             // таблица со списком товаров в заказе
        order = {items: []},                                        // заказ
        itemsList;                                                  // все товары

    // запускаем триггер добавления позиции к заказу
    this.addItem = function() {
        var itemID = orderItemsEl.find('select').val(),         // ID товара
            _token = form.find('input[name=_token]').val(),     // token для csrf
            selectedItem;

        // ищем выбранный товар
        selectedItem = $.grep(itemsList, function(item) {
            return item.id === itemID*1;
        });
        if(selectedItem.length > 0) {
            form.trigger('add-item', [selectedItem[0]]);
            form.trigger('refresh');
        } else {
            console.log('Item not found');
        }
    };

    // добавляем позицию к списку
    this.itemIntoTable = function(e, item) {
        var tr;
        // знаю что можно использовать шаблоны, но так быстрей

        tr = '<tr data-item-id="' + item.id + '">';
        tr += '<td>' + item.name + '</td>';
        tr += '<td>' + item.weight + '</td>';
        tr += '<td>' + item.width + '</td>';
        tr += '<td>' + item.length + '</td>';
        tr += '<td>' + item.height + '</td>';
        tr += '<td><input type="number" value="1"></td>';
        tr += '<td><button class="btn btn-sm btn-danger remove-item" type="button">убрать</button></td>';
        tr += '</tr>';

        orderItemsEl.find('table tbody').append(tr);
    };

    // формирование элемента select со списком всех товаров
    this.refreshSelectItemsList = function() {
        var selectEl = orderItemsEl.find('select'),
            optionsEl = '',
            has, i;

        for(i in itemsList) {
            // в список должны попадать только те товары которых нет в списке заказа
            has = $.grep(order.items, function(item) {
                return itemsList[i].id === item.id;
            });
            if(has.length === 0) {
                optionsEl += '<option value="' + itemsList[i].id + '">' + itemsList[i].name + '</option>';
            }
        }

        // todo если товаров нет, то выводим сообщение

        selectEl.empty().append(optionsEl);
    };

    // Показываем или скрываем таблицу в зависимости от наличия позиций в заказе
    this.tableShowToggle = function() {
        if(order.items.length > 0) {
            orderItemsTableEl.fadeIn();
        } else {
            orderItemsTableEl.hide()
        }
    };

    // добавление товара в заказ
    this.itemIntoOrder = function(e, item) {
        order.items.push(item);
    };

    // удаление позиции из заказа
    this.removeItem = function(e) {
        var trEl = $(e.target).parents('tr'),
            itemID = trEl.data('item-id'),
            i;

        //удаляем из заказа
        for(i in order.items) {
            if(order.items[i].id === itemID) {
                order.items.splice(i, 1);
            }
        }
        // удаляем из таблицы
        trEl.remove();

        // запускаем триггер
        form.trigger('remove-item', [itemID]);
        form.trigger('refresh');
    };

    this.init = function() {
        var that = this;

        // загрузим все товары, благо их немного
        $.get('/item/all', function(response, status, xhr) {
            // todo нужно учесть случаи с ошибками запроса (xhr)
            itemsList = response;
            // создать список всех товаров
            that.refreshSelectItemsList();
        });

        // скрываем таблицу со списком товаров в заказе
        orderItemsTableEl.hide();

        // регистрация обработчиков
        // на добавление позиции в заказ
        orderItemsEl.on('click', 'button.add-item', this.addItem);
        // удаление товара из заказа
        orderItemsEl.on('click', 'button.remove-item', this.removeItem);
        // добавление в заказ
        form.on('add-item', this.itemIntoOrder);
        // добавление в таблицу
        form.on('add-item', this.itemIntoTable);
        // обновить список всех товаров
        form.on('refresh', this.refreshSelectItemsList);
        // показываем таблицу или скрваем
        form.on('refresh', this.tableShowToggle);
    };

    return this;
};

var app = new App('#form-order').init();