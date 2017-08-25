
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


    var selectors = {
        exportLinkFormat: '.export-link-format',
        pageSizeControll: '[data-role="page-size"]'
    };

    var defaultOptions = {};

    // **********************************
    // Constructor
    // **********************************
    var kakGrid = function(element, options) {
        this.$parent = $(element)
        this.options  = $.extend(defaultOptions,options);

        /*
        this.$parent.find('.dropdown-checkbox-content').on('click.dropdown.data-api', function(e) {
            e.stopPropagation();
        });

         var inputChange = false;

        this.$parent.find('.dropdown-checkbox').on('hide.bs.dropdown', $.proxy(function(e){
            this._saveData();
            if(inputChange)
                location.reload();
        },this));

        this.$parent.find('.column-filter .btn-container-open').on('click',$.proxy(function(e){
            e.preventDefault();
            $(e.currentTarget).closest('.column-filter').find('.column-filter-container').toggleClass('open');
        },this));

        this.$parent.find('.date-filter-range .btn-apply').on('click',$.proxy(function(e){
            var $target  = $(e.currentTarget);
            var $input   = $target.closest('.column-filter').find('.date-filter-range-input')
            var inputs = $target.closest('.column-filter').find('.datetimepicker');
            $input.val($(inputs[0]).val() + ' - ' + $(inputs[1]).val() );
            $target.closest('.column-filter-container').removeClass('open');
            this.$parent.find('.grid-view').yiiGridView('applyFilter');
        },this));
        */

        this.$parent.find('.dropdown-checkbox-content a').on('click', $.proxy(function(e){
            e.preventDefault();
            var $target = $(e.currentTarget);
            if(!$target.is('input')){
                var $input = $target.find('input');
                $input.prop( 'checked', !$input.prop('checked') );
                inputChange = true;
            }
        },this));

        // behavior export
        this.$parent.on('click', selectors.exportLinkFormat, $.proxy(function (e) {
            e.preventDefault();
            var format = $(e.currentTarget).data('type');

            console.log(format);
        }, this))

    };


    kakGrid.prototype = {
        constructor: kakGrid,
        exportTable: function(url, format) {

        },
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
                'columns': $columns
            };
            var key = 'kak-grid_' + (this.$parent.find('.grid-view').attr('id'));
            this._setCookie(key, JSON.stringify(data),1);

            var paginationSize =  this.$parent.find('.pagination-size').val();
            this._setCookie('kak-grid',JSON.stringify({paginationSize:paginationSize}),1);
        },
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
