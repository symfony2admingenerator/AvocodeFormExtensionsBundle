/*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for SingleUpload form widget
 *  Author:         loostro <loostro@gmail.com>
 *  License:        MIT
 *  Dependencies: 
 *                  - blueimp / JavaScript Load Image 1.2.3
 *                    http://github.com/blueimp/JavaScript-Load-Image
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
    var pluginName = 'singleUpload',
        document = window.document,
        defaults = {
            maxWidth:   320,
            maxHeight:  180,
            minWidth:   16,
            minHeight:  16,
            previewImages:    true,
            previewAsCanvas:  true,
            isEmpty:          true,
            nameable:         false,
            deleteable:       false,
            widget_name:      null,
            filetypes:  {
                'audio':            "Audio",
                'archive':          "Archive",
                'html':             "HTML",
                'image':            "Image",
                'pdf-document':     "PDF<br />Document",
                'plain-text':       "Plain<br />Text",
                'presentation':     "Presentation",
                'spreadsheet':      "Spreadsheet",
                'text-document':    "Text<br />Document",
                'unknown':          "Unknown<br />Filetype",
                'video':            "Video"
            }
        };

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
            this.$widgetContainer = $('#'+this.element.id+'_widget_container');
            this.$addButton       = this.$widgetContainer.find('.singleupload-buttonbar .add');
            this.$replaceButton   = this.$widgetContainer.find('.singleupload-buttonbar .replace');
            this.$cancelButton    = this.$widgetContainer.find('.singleupload-buttonbar .cancel');
            this.$deleteButton    = this.$widgetContainer.find('.singleupload-buttonbar .delete');
                        
            // Set isDeletable
            this.isDeletable = (!this.options.isEmpty && this.options.deleteable);
            
            // Add deletable behaviour
            if (this.options.deleteable) {
                this.$deleteFlag = $('#'+this.element.id+'_delete');
            }
            
            // Show delete button
            if (this.isDeletable) {
                this.$deleteButton.show();
            }            
            
            // Make sure upload input is empty (prevent cached form data)
            this._resetInput();
            
            // bind onChange to file input change event
            $(this.element).on('change', function(){
                that._onChange();
            });
            
            // bind onCancel to button click event
            this.$cancelButton.click(function(){
                that._onCancel();
            });
            
            // bind onDelete to button click event
            this.$deleteButton.click(function(){
                that._onDelete();
            });
        },

        _onChange: function() {
            var file = this.element.files[0]; 
            
            // show cancel button
            this.$cancelButton.removeClass('disabled').show(
                'slide', { direction: 'left' }, 'slow'
            );
            
            if (this.isEmpty) {
                this.$addButton.hide();
                this.$replaceButton.show();
            }
              
            // hide delete button
            if (this.isDeletable) {
                this.$deleteButton.addClass('disabled').hide(
                  'slide', { direction: 'right' }, 'slow'
                );
            }
            
            // trigger preview
            (this.options.previewImages && this._isImage(file))
            ?   this._onPreviewImage()
            :   this._onPreviewFile();
        },
        
        _onCancel: function() {
            // hide cancel button
            this.$cancelButton.addClass('disabled').hide(
                'slide', { direction: 'left' }, 'slow'
            );
            
            if (this.isEmpty) {
                // show add button/hide replace button
                this.$replaceButton.hide();
                this.$addButton.show();
            }
            
            // show delete button
            if (this.isDeletable && !this.isEmpty) {
                this.$deleteButton.removeClass('disabled').show(
                    'slide', { direction: 'right' }, 'slow'
                ); 
            }
            
            var $activePreview    = $('.'+this.element.id+'_preview_image.active');
            var $previewDownload  = $('.'+this.element.id+'_preview_image.download');
            
            // sanity-check
            if ($activePreview.hasClass('download')) return;
            
            // unlock original name
            if (this.options.nameable) {
                $activePreview.find('.nameable').removeAttr('name');
                $previewDownload.find('.nameable').removeAttr('disabled');
            }
            
            // reset input
            this._resetInput();
            
            // animate preview toggle
            $previewDownload.slideDown({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).addClass('active'); }
            });
            
            $activePreview.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).remove(); }
            });
        },

        _onDelete: function() {
            var that = this; // plugin-scope helper
            var $previewDownload  = $('.'+this.element.id+'_preview_image.download');
            
            // sanity check
            if (!this.isDeletable) return;
            
            this.$replaceButton.hide();
            this.$addButton.show();
            
            // hide and remove delete button
            this.$deleteButton.parent().addClass('removed').end()
            this.$deleteButton.addClass('disabled').hide('slide', {
                direction:  'left', 
                queue:      false,
                complete:   function(){ 
                    that.$deleteButton.parent().remove(); 
                }
            }, 'slow');
            
            // hide previewDownload and remove the image
            $previewDownload.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).empty(); }
            });
            
            // Set deletable flag
            this.$deleteFlag.val(1);
            
            // Disable delete button animation
            this.isDeletable = false;
            // Clear the widget
            this.isEmpty = true;
        },
        
        _onPreviewImage: function() {
            var that = this; // plugin-scope helper
            var file = this.element.files[0];   
            
            // load preview image
            window.loadImage(
                file,
                function (img) {
                    var $activePreview = $('.'+that.element.id+'_preview_image.active');
                    var $previewUpload = $activePreview.clone().empty().hide()
                        .removeClass('download').addClass('upload');

                    if (that.options.nameable) {
                        $activePreview.find('.nameable').attr('disabled', 'disabled');
                        var $filelabel = $('<div/>').addClass('row-fluid').html(
                            $('<input/>').attr('type', 'text').addClass('nameable')
                                .attr('name', that.options.widget_name+'[name]').val(file.name)
                        );
                    } else {
                        var $filelabel = $('<div/>').addClass('row-fluid').text(file.name);
                    }
                    var $filesize = $('<div/>').addClass('row-fluid').text(that._bytesToSize(file.size));
                    
                    // create and insert new preview node
                    $previewUpload.appendTo($activePreview.parent()).html(
                        $(img).addClass('img-polaroid')
                    ).append($filelabel).append($filesize);
                      
                    // animate preview toggle
                    $previewUpload.slideDown({
                        duration: 'slow', 
                        queue:    false,
                        complete: function() { $(this).addClass('active'); }
                    });
                    
                    $activePreview.slideUp({ 
                        duration: 'slow', 
                        queue:    false,
                        complete: function() { 
                            ($(this).hasClass('download'))
                            ?   $(this).removeClass('active')
                            :   $(this).remove(); 
                        }
                    });
                }, {
                    maxWidth:   that.options.maxWidth,
                    maxHeight:  that.options.maxHeight,
                    minWidth:   that.options.minWidth,
                    minHeight:  that.options.minHeight,
                    canvas:     that.options.previewAsCanvas,
                    noRevoke:   true
                }
            );
        },
        
        _onPreviewFile: function() {
            var $activePreview = $('.'+this.element.id+'_preview_image.active');
            var $previewUpload = $activePreview.clone().empty().hide()
                .removeClass('download').addClass('upload');
            
            var file = this.element.files[0];
            var filetype = this._checkFileType(file);
            var $fileicon = $('<div/>').addClass('fileicon').addClass(filetype)
                                       .html(this.options.filetypes[filetype]);
            
            if (this.options.nameable) {
                $activePreview.find('.nameable').attr('disabled', 'disabled');
                var $filelabel = $('<div/>').addClass('row-fluid').html(
                    $('<input/>').attr('type', 'text').addClass('nameable')
                        .attr('name', this.options.widget_name+'[name]').val(file.name)
                );
            } else {
                var $filelabel = $('<div/>').addClass('row-fluid').text(file.name);
            }
            
            var $filesize = $('<div/>').addClass('row-fluid').text(this._bytesToSize(file.size));
                                   
            // create and insert new preview node
            $previewUpload.appendTo($activePreview.parent())
                .html($fileicon).append($filelabel).append($filesize);
        
            // animate preview toggle
            $previewUpload.slideDown({
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).addClass('active'); }
            });
            
            $activePreview.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { 
                    ($(this).hasClass('download'))
                    ?   $(this).removeClass('active')
                    :   $(this).remove(); 
                }
            });
        },
        
        _bytesToSize: function(bytes) {
            var kilobyte = 1024;
            var megabyte = kilobyte * 1024;
            var gigabyte = megabyte * 1024;
            var terabyte = gigabyte * 1024;

            if (bytes < kilobyte)         return bytes + ' B'; 
            else if (bytes < megabyte)    return (bytes / kilobyte).toFixed(2) + ' KB';
            else if (bytes < gigabyte)    return (bytes / megabyte).toFixed(2) + ' MB';
            else if (bytes < terabyte)    return (bytes / gigabyte).toFixed(2) + ' GB';
            else                          return (bytes / terabyte).toFixed(2) + ' TB';
        },
        
        _checkFileType: function(file) {            
            if (this._isAudio(file))        return 'audio';
            if (this._isArchive(file))      return 'archive';
            if (this._isHTML(file))         return 'html';
            if (this._isImage(file))        return 'image';
            if (this._isPDFDocument(file))  return 'pdf-document';
            if (this._isPlainText(file))    return 'plain-text';
            if (this._isPresentation(file)) return 'presentation';
            if (this._isSpreadsheet(file))  return 'spreadsheet';
            if (this._isTextDocument(file)) return 'text-document';
            if (this._isVideo(file))        return 'video';
            // else
            return 'unknown';
        },
        
        _isAudio: function(file) {
            return (file.type.match('audio/.*'));
        },
        
        _isArchive: function(file) {
            return (
                file.type.match('application/.*compress.*') || 
                file.type.match('application/.*archive.*') || 
                file.type.match('application/.*zip.*') || 
                file.type.match('application/.*tar.*') || 
                file.type.match('application/x\-ace') || 
                file.type.match('application/x\-bz2') || 
                file.type.match('gzip/document')
            );
        },
        
        _isHTML: function(file) {
            return (file.type.match('text/html'));
        }, 
        
        _isImage: function(file) {
            return (file.type.match('image/.*'));
        },
        
        _isPDFDocument: function(file) {
            return (
                file.type.match('application/acrobat') || 
                file.type.match('applications?/.*pdf.*') || 
                file.type.match('text/.*pdf.*')
            );
        }, 
        
        _isPlainText: function(file) {
            return (file.type.match('text/plain'));
        },
        
        _isPresentation: function(file) {
            return (
                file.type.match('application/.*ms\-powerpoint.*') || 
                file.type.match('application/.*officedocument\.presentationml.*') || 
                file.type.match('application/.*opendocument\.presentation.*')
            );
        },
        
        _isSpreadsheet: function(file) {
            return (
                file.type.match('application/.*ms\-excel.*') || 
                file.type.match('application/.*officedocument\.spreadsheetml.*') || 
                file.type.match('application/.*opendocument\.spreadsheet.*')
            );
        },
        
        _isTextDocument: function(file) {
            return (
                file.type.match('application/.*ms\-?word.*') || 
                file.type.match('application/.*officedocument\.wordprocessingml.*') || 
                file.type.match('application/.*opendocument\.text.*')
            );
        },
        
        _isVideo: function(file) {
            return (file.type.match('video/.*'));
        },
        
        _resetInput: function() {
            // create replacement input
            var $replacement = $(this.element).val('').clone(true);
            
            // replace inputs
            $(this.element).replaceWith( $replacement );
            
            // point plugin to new element
            this.element = $replacement[0];
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