
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
        pageSizeControl: '[data-role="page-size"]'
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

        // this.$parent.find('.dropdown-checkbox-content a').on('click', $.proxy(function(e){
        //     e.preventDefault();
        //     var $target = $(e.currentTarget);
        //     if(!$target.is('input')){
        //         var $input = $target.find('input');
        //         $input.prop( 'checked', !$input.prop('checked') );
        //         inputChange = true;
        //     }
        // },this));

        // behavior export
        this.$parent.on('click', selectors.exportLinkFormat, $.proxy(function (e) {
            var el = $(e.currentTarget);
            this.exportTableProcess('f'+ el.data('hash'), el.data('url'), el.data('type'));
            e.preventDefault();
        }, this))

    };


    kakGrid.prototype = {
        constructor: kakGrid,
        createHideFrame: function(hash){
            if (!$('#' + hash).length) {
                $('<iframe/>', {name: hash, css: {'display': 'none'}}).appendTo('body');
            }
        },
        exportTableProcess: function(hash, url, type){
            this.createHideFrame(hash);
            $('<form/>', {'action': url, 'target': hash, 'method': 'post', css: {'display': 'none'}})
                .append($('<input/>', {'name': 'type', 'value': type, 'type': 'hidden'}))
                .append($('<input/>', {'name': 'export', 'value': 1, 'type': 'hidden'}))
                .append($('<input/>', { 'name': yii.getCsrfParam() || '_csrf', 'value': yii.getCsrfToken(), 'type': 'hidden'}))
                .appendTo('body')
                .submit()
                .remove();
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
