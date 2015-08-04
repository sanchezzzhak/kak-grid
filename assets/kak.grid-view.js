
(function(root, factory) {
    // CommonJS support
    if (typeof exports === 'object') {
        module.exports = factory();
    }
    // AMD
    else if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    }
    // Browser globals
    else {
        factory(root.jQuery);
    }
}(this, function($) {
    'use strict';

    // **********************************
    // Constructor
    // **********************************
    var kakGrid = function(element, options) {
        this.$parent = $(element)
        var defaultOptions = {};
        this.options  = $.extend(defaultOptions,options);


        this.$parent.find('.dropdown-checkbox-content').on('click.dropdown.data-api', function(e) {
            e.stopPropagation();
        });
        this.$parent.find('.dropdown-checkbox').on('hide.bs.dropdown', $.proxy(function(e){
            this._saveData();
            location.reload();
        },this));


        this.$parent.find('.dropdown-checkbox-content a').on('click', $.proxy(function(e){
            e.preventDefault();
            var $target = $(e.currentTarget);
            if(!$target.is('input')){
                var $input = $target.find('input');
                $input.prop( 'checked', !$input.prop('checked') );
            }
        },this));

        this.$parent.find('.pagination-size').on('change', $.proxy(function(e){
            this._saveData();
            location.reload();
        },this));


    };


    kakGrid.prototype = {
        constructor: kakGrid,
        // ----------------------------------
        // Methods to override
        // ----------------------------------
        _setCookie: function(key, value, days) {
            days  = days || 1;
            var e = new Date();
            e.setTime(e.getTime() + (days * 864e5 ));
            document.cookie = key + '=' + value +';path=/'+ ';expires=' + e.toUTCString();
        },
        _saveData: function(){
            var $columns = [];
            this.$parent.find('.dropdown-checkbox-content input').each(function(k,input){
                $columns.push($(input).prop('checked')? 1 : 0);
            });
            var data = {
                'paginationSize' : this.$parent.find('.pagination-size').val(),
                'columns': $columns,
            };
            var key = 'kak-grid.' + (this.$parent.find('.grid-view').attr('id'));
            this._setCookie(key, JSON.stringify(data),1);
        }
    };

    $.fn.kakGrid = function(option) {
        var options = typeof option == 'object' && option;
        new kakGrid(this, options);
        return this;
    };
    $.fn.kakGrid.Constructor = kakGrid;

    // auto init
    $('.kak-grid').each(function(k,i){
        $(i).kakGrid();
    });


}));
