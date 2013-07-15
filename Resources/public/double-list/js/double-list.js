/*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for DoubleList form widget
 *  Authors:        loostro <loostro@gmail.com>, Cedric LOMBARDOT
 *  License:        MIT
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, undefined ) {
    
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.
    
    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'doubleList',
        document = window.document,
        defaults = {};

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( {}, defaults, options) ;
        
        this._defaults = defaults;
        this._name = pluginName;
        
        this._init();
    }
    
    Plugin.prototype = {

        _init: function() {
            // Plugin-scope helper
            var that = this;
            
            // Select container
            this.$widgetContainer   = $('#'+this.element.id+'_widget_container');
            this.$unselectedList    = $('#'+this.element.id+'_unselected');
            this.$selectedList      = $('#'+this.element.id+'_selected');
            this.$unselectedButton  = this.$widgetContainer.find('.list-controls .unselect');
            this.$selectedButton    = this.$widgetContainer.find('.list-controls .select');
            this.$form              = this.$widgetContainer.closest('form');
            
            // bind onUnselect to button click event
            this.$unselectedButton.click(function(){
                that._onUnselect();
            });
            
            // prevent default and toggle selected class
            this.$widgetContainer.find('ul li a').click(function(e){
                e.preventDefault();
                
                $(this).closest('li').toggleClass('selected');
            });
            
            // bind onSelect to button click event
            this.$selectedButton.click(function(){
                that._onSelect();
            });
            
            // bind onSubmit to form submit event
            this.$form.submit(function(){                
                that._onSubmit();
            });
        },

        _onUnselect: function() {
            var that = this;  // Plugin-scope helper
            
            $.each(this.$selectedList.children('li'), function(key, item) {
                if ($(item).hasClass('selected')) {
                    $(item).removeClass('selected');
                    $(item).appendTo(that.$unselectedList);
                }
            });
        },

        _onSelect: function() {
            var that = this;  // Plugin-scope helper
            
            $.each(this.$unselectedList.children('li'), function(key, item) {
                if ($(item).hasClass('selected')) {
                    $(item).removeClass('selected');
                    $(item).appendTo(that.$selectedList);
                }
            });
        },
                
        _onSubmit: function() {
            var that = this;  // Plugin-scope helper
            
            $.each(this.$unselectedList.children('li'), function(key, item) {
                var value = $(item).data('value');
                $(that.element).find('option[value="'+value+'"]').removeAttr('selected');
            });
            
            $.each(this.$selectedList.children('li'), function(key, item) {
                var value = $(item).data('value');
                $(that.element).find('option[value="'+value+'"]').attr('selected', 'selected');
            });
        }
    };

    // You don't need to change something below:
    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations and allowing any
    // public function (ie. a function whose name doesn't start
    // with an underscore) to be called via the jQuery plugin,
    // e.g. $(element).defaultPluginName('functionName', arg1, arg2)
    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {

                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });

        // If the first parameter is a string and it doesn't start
        // with an underscore or "contains" the `init`-function,
        // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                  $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window));