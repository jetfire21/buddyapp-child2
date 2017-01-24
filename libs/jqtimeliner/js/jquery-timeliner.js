/*-------------------------------------------------------------------- */
/* $ timeliner plugin v0.1.0
/* Author: Furqan Aziz <furqan.dvlcloud@gmail.com>
/* Licensed under the MIT license
/*-------------------------------------------------------------------- */

// TODO List:
// Need date format config for pointer
// Need date format config for item date label
// Need bootbox confirm for delete confimation
// Need bootstrap notification for any success or danger messages
// Need waypoints for long page loading
// Pass get/put/post/delete urls and plugin must do rest of work by default alongwith hooks
// Need animate.css for scrolling animation
// Tests need to be added
// minified and dist version needed to be added
// Code improvements and refactoring

;(function ( $, window, document, undefined ) {

    /**
     * Plugin name
     */
    var pluginName = 'timeliner';

    /**
     * The plugin constructor
     * @param {DOM Element} element The DOM element where plugin is applied
     * @param {Object} options Options passed to the constructor
     */
    function Timeliner(_element, _options) {
        // Contain raw items 
        this.items = [];

        // Store a reference to the source element
        this.el = _element;

        // Store a $ reference  to the source element
        this.$el = $(_element);

        // Set a random (and normally unique) id for the object
        this.instanceId = Math.round(new Date().getTime() + (Math.random() * 100));

        // Initialize the plugin instance
        this.options(_options);
        this.config.addBtnId = this.config.addBtnId || pluginName + "-add-btn-" + this.instanceId;
        this.config.addFrmId = this.config.addFrmId || pluginName + "-add-frm-" + this.instanceId;
        this.init();
    };

    /**
     * Set up your Plugin protptype with desired methods.
     * It is a good practice to implement 'init' and 'destroy' methods.
     */
    Timeliner.prototype = {
        // Set the instance options extending the plugin defaults and
        // the options passed by the user
        options: function( _options){
            this.config = $.extend({}, $.fn[pluginName].defaults, _options);
            return this;
        },
        /**
         * Initialize the plugin instance.
         * Set any other attribtes, store any other element reference, register
         * listeners, etc
         */
        init: function() {
            this.add(this.$el.find(this.config.itemSelector)).render();
            if($.type(this.config.onInit) === "function"){
                this.config.onInit.call(this, this);
            }
            return this;
        },
        add: function(_item){
            var self = this;
            if($.type(_item) === "string"){
                var el = $(_item);
                if(el instanceof $ && el.hasOwnProperty('selector')){
                    self.add(el);
                }else{
                    self.add(el.find(this.config.itemSelector));
                }
            }else if(_item instanceof $ && _item.hasOwnProperty('selector')){
                _item.each(function(_key, _value){
                    self.add($(_value));
                });
            }else if(_item instanceof $ && _item.hasOwnProperty('context')){
                self.add(fetchHtmlItem.call(this, _item, this.config));
            }else if($.type(_item) === "array"){
                $.each(_item, function(_key, _value){
                    self.add($(_value));
                });
            }else if($.type(_item) === "object"){
                pushItem.call(this, _item);
            }

            return this;
        },
        render: function(){
            var html = '',
                script = '',
                self = this;

            // Adding add new button and form
            if(self.config.addBtnTpl){
                var btn = getHtml.call(self, 'addBtnTpl');
                html += addAttr.call(self, btn, 'id', self.config.addBtnId);
                // html += addAttr.call(self, btn, 'id', self.config.addBtnId);
            }

            // Adding add new form
            if(self.config.formTpl){
                var frm = getHtml.call(self, 'formTpl');
                frm = addAttr.call(self, frm, 'id', self.config.addFrmId);
                frm = addCss.call(self, frm, 'display', 'none');
                html += getHtml.call(self,'sectionTpl', frm);
            }

            // Sorting items in original array and Dividing into sections
            var sorted = {}
            self.items.sort(self.config.sortComparer);
            $.each(self.items, function( _index, _item ) {
                var point = self.config.pointFormater.call(self, _item);
                sorted[point] = sorted[point] || [];
                sorted[point].push(_item);
            });

            // Preparing html and attaching into html
            for(var _point in sorted){
                if(!sorted.hasOwnProperty(_point)) continue;
                var items = "";
                html += getHtml.call(self, 'pointTpl', _point);
                $.each(sorted[_point], function(_index, _item){
                    items += _item.$html;
                });
                html += getHtml.call(self,'sectionTpl', items);
            }
            
            // Add html into Spin and then render
            this.$el.empty().html(getHtml.call(self, 'spineTpl', html));

            // Adding javascript events for add form
            if(self.config.formTpl){

                // Binding add form and add button together
                if(self.config.addBtnTpl){
                    $("#" + self.config.addBtnId).click(function(){
                        $("#" + self.config.addFrmId).toggle();
                    });
                }

                // Submit button event handling
                $("#" +  self.config.addFrmId).submit(function(e){
                    e.preventDefault();
                    var item = fetchFormItem(e, this, self.config);
                    self.config.onAdd(item, function(_item){
                        // console.log("onadd..submit event");
                        self.add(_item).render();
                    });
                });

                // Cancel button event handling
                $("#" +  self.config.addFrmId).find('[type="reset"]').click(function(e){
                    e.preventDefault();
                    self.render();
                });

                // Add calender to the date field
                $("#" +  self.config.addFrmId).find('.datepicker').datepicker();
            }

            // Adding edit and delete actions
            for(var _point in sorted){
                if(!sorted.hasOwnProperty(_point)) continue;
                $.each(sorted[_point], function(_index, _item){
                    if(self.config.editBtnTpl && self.config.formTpl){
                        $("#" + _item.$edtBtnId).click(function(e){

                            // Making form for the item clicked
                            var frm = getHtml.call(self, 'formTpl');
                            // console.log(frm);
                            frm = addAttr.call(self, frm, 'id', _item.$edtFrmId);
                             $(this).parents('li:first').replaceWith(frm);

                            // Filling form inputs data
                            frm = $("#" + _item.$edtFrmId);

                            /* ***** alex code ******* */

                           // console.log(frm[0].getElementById("alex_get_elem"));
                            // var get_select = $("#cccccccccccc-11-jan-2017-edit-frm h3");

                            // ======== WORK ===========
                            // var h3 = $("#" + _item.$edtFrmId+" h3");
                            // h3.html("<div class='zzz'>"+_item.alex_tl_grp_id+"</div>");
                            // h3.html("<div class='zzz'>"+_item.alex_gr_name_select+"</div>");
                            // var sel_gr = $("#" + _item.$edtFrmId+" .select-group [value='19']");
                            // console.log("sel_gr= "+sel_gr.html());
                            // sel_gr.attr("selected", "selected");
                            // sel_gr.prop("selected", true);
                            // $("#" + _item.$edtFrmId+" .select-group").prepend('<option value="0">zero</option>');
                              // var my = '<div class="form-group">\
                              //           <label class="col-sm-2 control-label" for="form-field-12"> Background </label>\
                              //           <div class="col-sm-9">\
                              //               <select class="form-control" name="alex_gr_name_select" >\
                              //                   <option value="" seleted="selected">None</option>\
                              //                   <option value="1">1</option>\
                              //                   <option value="3">3</option>\
                              //                   <option value="19">19</option>\
                              //                   <option value="Linux">aaa</option>\
                              //               </select>\
                              //           </div>\
                              //       </div>';
                            // h3.html(my);
                            // console.log(h3.html());
                            // ======== WORK ===========

                            // get_select.attr("selected", "selected");
                            // get_select.text("ccccccccccccccccc");
                            // console.log("EDIT======== html---------\r\n ");
                            // весь html плагина от #timeliner
                            // console.log(html);
                            // console.log("----------frm---------\r\n");
                            // весь html плагина от #timeliner
                            // console.log(frm);
                            // console.log("----------this---------\r\n");
                            // html кнопки
                            // console.log(this);
                            // console.log("----------self---------\r\n");
                            // прототип обьект Timeliner
                            // console.log(self);
                            // console.log("----------self.config---------\r\n");
                            // обьект с конфиг данными
                            // console.log(self.config);
                            // console.log("----------item---------\r\n");
                            // важный обьект со всеми данными сгенерирова плагином
                            // console.log(_item);
                            // html переменной itemTpl
                            // html.$html = alex_t;
                            // frm.$html = alex_t;
                             // console.log("----------_item.$edtFrmId--------\r\n"+_item.$edtFrmId);
                            // alex_t = addAttr.call(self, alex_t, 'id', "alex_777");
                            // var alex_t = getHtml.call(self, 'formTpl');
                            // console.log("----------alex_t---------\r\n"+alex_t);
                            // alex_t.replace('{{test_text}}', "Привет ВАся");
                           // var a = html.html();
                            // alex_t.replace('{{test_text}}', 'edtBtn');
                            // _item.$html = alex_t;

                            // html = html.replace('{{edit-button}}', edtBtn);
                            // html = html.replace('{{delete-button}}', delBtn);
                            // _obj.$html = html;

                            /* ***** alex code ******* */
 
                            $.each(_item, function(_key, _value){
                                if(_key[0] !== "$"){
                                    frm.find('[name="'+_key+'"]').val(_value);
                                    // alex_code
                                    // console.log(key + " == "+_value);
                                }
                                //alex code
                                // $("#" + _item.$edtBtnId).click(function(e){
                                // console.log("method render="+ $(this));
                                // console.log("method render="+ self);
                            });

                            // Submit button event handling
                            $("#" +  _item.$edtFrmId).submit(function(e){
                                e.preventDefault();
                                var item = fetchFormItem(e, this, self.config);
                                self.config.onEdit(item, function(_item){
                                    self.add(_item).render();
                                });
                            });

                            // Cancel button event handling
                            $("#" +  _item.$edtFrmId).find('[type="reset"]').click(function(e){
                                e.preventDefault();
                                self.render();
                            });

                            // Add calender to the date field
                            $("#" +  _item.$edtFrmId).find('.datepicker').datepicker();
                        });
                    }

                    if(self.config.deleteBtnTpl){
                        $("#" + _item.$delBtnId).click(function(e){
                            self.config.onDelete(_item, function(_item){
                                self.delete(_item.$pk).render();
                            });
                        });
                    }
                });
            }

            //alex code 
            // console.log(this);
            // console.log(self.config.formTpl);

            return this;
        },
        delete: function(_id){
            var index = findWhere.call(this, _id);
            if(index > -1){
                this.items.splice(index, 1);
            }
            return this;
        },
        destroy: function(){
            // Remove child nodes
            this.$el.empty();
            // Remove any attached data from your plugin
            this.$el.removeData();
        }
    };

    /* -------------------------------------------------- */
    /* Helper functions for prototype
    /* -------------------------------------------------- */
    // fetching data from rendered html item
    var fetchHtmlItem = function(_obj, _options){
        
        // initializing item and self
        var item = {}, self = this;

        // Fetching default item
        var fetchDefault = function(_obj, _selector, _options){
            var el = $(_obj).find(_selector);
            return $.trim(el.val() || el.text());
        };
        // alex code
        // var fetchAttr = function(_obj, _attr, _options){
        //     var el = $(_obj).attr(_attr);
        //     console.log("el="+el);
        //     return $.trim(el);
        // };

        // Fetching ID function
        var fetchId = function(_obj, _options){
            var date  = fetchDefault(_obj, ".timeliner_date", _options);
            var title =  fetchDefault(_obj, ".timeliner_label", _options);
            var alex_item_id =  fetchDefault(_obj, ".alex_item_id", _options);
            // var alex_show_group =  fetchDefault(_obj, ".alex_show_group", _options);

            return title.concat("-")
                        .concat(date)
                        .replace(/[^a-z0-9-]/gi, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '')
                        .toLowerCase();
        };

        var fetchAlexId = function(_obj, _options){
            var id  = fetchDefault(_obj, ".alex_item_id", _options);
            return id;
        };


        // Fetching Class function
        var fetchClass = function(_obj, _options){
            // console.log($(_obj).attr("class"));
            var klass = $(_obj).attr("class").replace("timeliner_element", "");
            // console.log("klass="+klass);
            return $.trim(klass);
        };

        // var fetchClass2 = function(_obj, _options){
        //     var klass = $(_obj).attr("class2").replace("timeliner_element2", "");
        //     console.log("klass="+klass);
        //     return $.trim(klass);
        // };

        var fetchAlexShowGroup = function(_obj, _options){
            // var gr = $(_obj).attr("alex_show_group");
            // // alex code 
            // console.log("obj=");
            // var html_each = _obj.context;
            // console.log(html_each);
            // // var title = html_each.match(/alex_show_group[^a]+/i);
            // var g = $(_obj).attr("gr_id");
            // console.log(g);
            var user_group =  fetchDefault(_obj, "#alex_gr_avatar", _options);
            var gr_link =  fetchDefault(_obj, "#alex_gr_link", _options);
            var gr_name =  fetchDefault(_obj, "#alex_gr_name_select", _options);
            // var gr_link =  fetchDefault(_obj, "data-link", _options);
            // var gr_link =  fetchAttr(_obj, "data-link", _options);
            if(user_group != "") user_group = '<a href="'+gr_link+'"><img src="'+user_group+'" /></a><span>'+gr_name+'</span>';
            // console.log("user-group "+user_group);
            // console.log(gr_link);
            // console.log(gr);
            // return false;
            return $.trim(user_group);
            // return alex.replace(/http/i,'----------');
        };

        // mapping for html to object
        var mapping = {
            'id'      : fetchId,
            'class'   : fetchClass,
            'id_alex': fetchAlexId,
            'date'    : ".timeliner_date",
            'title'   : ".timeliner_label",
            'content' : ".content",
            'alex_item_id' : ".alex_item_id",
            'alex_tl_grp_id' : fetchAlexShowGroup,
            'alex_gr_name_select': '#alex_gr_name_select'
            // 'gr_link' : "#alex_gr_link",
            // 'gr_name' : "#alex_gr_name"
            // 'gr_id' : "#alex_show_group",
        };

        // ietrate mapping and fetch things from html
        $.each(mapping, function(_key, _value){
            if($.type(_value) === "string" ){
                item[_key] = fetchDefault(_obj, _value, self.config);
            }else if($.type(_value) === "function" ){
                item[_key] = _value(_obj, self.config);
            }
        });
        
        return item;
    }

    // fetching data from add/edit form
    var fetchFormItem = function(_event, _form, _options){
        var item = {}, self = this;
        var inputs = $(_form).find(".form-control");

        // ietrate mapping and fetch things from html
        $.each(inputs, function(_key, _value){
            var el = $(_value);
            item[el.attr('name')] = $.trim(el.val() || el.text());
            // alex code
            // item[el.attr('gr_id')] = $.trim(el.val() || el.text());

        });
        return item;
    }

    var prepareForm = function(_id, _data){

    }

    // Find item if already exist
    var findWhere = function(_id){
        if(!_id) return -1;
        var self = this, index = -1;
        $.each(self.items, function(_index, _item){
            if(_item[self.config.pk] == _id){
                index = _index;
            }
        });
        return index;
    }

    // Adding a new or replacing edited data of any item
    var pushItem = function(_obj){
        if(_obj.hasOwnProperty(this.config.pk) && _obj[this.config.pk]){
            _obj.$pk = _obj[this.config.pk];
        }

        if(_obj.hasOwnProperty(this.config.dk) && _obj[this.config.dk]){
            _obj.$dk = new Date(_obj[this.config.dk]);
        }

        _obj.$edtFrmId = _obj.$pk + '-edit-frm';
        _obj.$edtBtnId = _obj.$pk + '-edit-btn';
        _obj.$delBtnId = _obj.$pk + '-delete-btn';

        var edtBtn = getHtml.call(this,'editBtnTpl');
        var delBtn = getHtml.call(this,'deleteBtnTpl');
        edtBtn = addAttr.call(this, edtBtn, 'id', _obj.$edtBtnId);
        delBtn = addAttr.call(this, delBtn, 'id', _obj.$delBtnId);

        var html = getHtml.call(this, 'itemTpl', _obj);
        html = html.replace('{{edit-button}}', edtBtn);
        html = html.replace('{{delete-button}}', delBtn);
        _obj.$html = html;


        if(_obj.$pk && _obj.$dk){
            var index = findWhere.call(this, _obj.$pk)
            if(index !== -1){
                this.items[index] = _obj;
            }else{
                this.items.unshift(_obj);
            }
        }
    }

    var elToHtml = function(_el){
        return $(_el).wrap('<p>').parent().html();
    }

    var addCss = function(_html, _attr, _val){
        return elToHtml($(_html).css(_attr, _val));
    }

    var addAttr = function(_html, _attr, _val){
        return elToHtml($(_html).attr(_attr, _val));
    }

    var getHtml = function(_tpl, _data){

        var html = this.config[_tpl];
        _data = _data || "";
        html = html || "";

        if($.type(_data) === "string"){
            html = html.replace("{{data}}", _data);
        }else if($.type(_data) === "object"){
            $.each(_data, function(_key, _value){
                if(_key[0] !== "$"){
                    html = html.replace('{{'+_key+'}}', _value);
                }
            });
        }

        return html;
    }

    // ------- TRASHED: start ------- //
    var getScript = function(_selector, _events){
        var script = '',
            self = this;
        if(this.config[_events] && $.type(this.config[_events]) === "object"){
            for(e in this.config[_events]){
                var func = function(e){ this.config[_events][e].call(this, e, this.config); }
                script += '$("#' + this.config[_selector] + '").on("' + e + '", ' + func + ')';
            }
        }
        return script;
    }

    // loading template file html via ajax
    var ajax = function(url) {
        var response, success, error;
        $.ajax({
            url: url,
            success: function (_result) {
                response = _result;
            },
            error: function(_result){
                response = _result;
            },
            async: false
        });
        return response;
    }
    // ------- TRASHED: end ------- //

    /* -------------------------------------------------- */
    /* Events and call backs
    /* -------------------------------------------------- */
    var onAdd = function(_data, _callback){
        // console.log("onadd");
        // ();

        _data['id'] = _data.title.concat("-")
                    .concat(_data.date)
                    .replace(/[^a-z0-9-]/gi, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '')
                    .toLowerCase();

        _callback(_data);
    }

    var onEdit = function(_data, _callback){
        _callback(_data);
    }

    var onDelete = function(_data, _callback){
        if(confirm("Are you sure to delete ?")){
            _callback(_data);
        }
    }

    /* -------------------------------------------------- */
    /* Bootstrap fetch item mapping functions
    /* -------------------------------------------------- */
    var sortComparer = function(_a, _b){
        return _b.$dk.getTime() - _a.$dk.getTime();
    };

    var dateFormater = function(){

    }

    var pointFormater = function(_obj){
        var months = ["January", "February", "March", "April", 
                    "May", "June", "July", "August", "September",
                    "October", "November", "December"];

        return months[_obj.$dk.getMonth()] + " " + _obj.$dk.getFullYear();
    };

    /* -------------------------------------------------- */
    /* Bootstrap Tpls 
    /* -------------------------------------------------- */

    /* ***** alex code ******* */

    // console.log("grs"+ JSON.parse(grs) );
    var grs_html = "";
    // var cur_group  = fetchDefault(_obj, "#alex_show_group", _options);
    // console.log(_element);
    // console.log("form=");
    // console.log(_options);
    // var gr_name = '{{alex_tl_grp_id}}';
    // console.log("gr_name= "+gr_name);

   // grs from php
   if(grs){
        for (var key in grs) {
            // value должен быть обязательно названием группы,иначе select не будет работать 
             // grs_html = grs_html + '<option selected="selected" value="'+key+'">'+grs[key]+'</option>\
             grs_html = grs_html + '<option value="'+grs[key]+'">'+grs[key]+'</option>';
        }
    }
    // console.log(grs_html);
    // console.log(alex_show_group);

    /* ***** alex code ******* */

    var spineTpl = '<div class="timeliner"><div class="spine"></div>{{data}}</div>';
    var pointTpl = '<div class="date_separator"><span>{{data}}</span></div>';
    var sectionTpl = '<ul class="columns">{{data}}</ul>';
    var itemTpl = 
        '<li >\
            <div class="timeliner_element {{class}}">\
                <div class="timeliner_title">\
                    <span class="timeliner_label">{{title}}</span><span class="timeliner_date">{{date}}</span>\
                </div>\
                <div class="content">{{content}}</div>\
                <div class="readmore">\
                <div id="alex_tl_grp_id">{{alex_tl_grp_id}}</div>\
                <span class="alex_item_id" style="display: none;">{{alex_item_id}}</span>\
                    {{delete-button}} {{edit-button}}\
                    </a>\
                </div>\
            </div>\
        </li>';
    var formTpl = 
        '<li>\
            <div class="timeliner_element" style="float: none;">\
                <form role="form" class="form-horizontal timeline_element" >\
                <input type="hidden" name="id" class="form-control" >\
                <div class="timeline_title"> &nbsp; </div>\
                <div class="content">\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-2"> Date </label>\
                        <div class="col-sm-9">\
                            <input type="text" placeholder="Post Date" name="date" class="form-control datepicker" required="required" data-date-format="dd M yyyy">\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-1"> Title </label>\
                        <div class="col-sm-9">\
                            <input type="text" placeholder="News Title" name="title" class="form-control" required="required">\
                            <input type="hidden" placeholder="News Title" name="id_alex" class="form-control" required="required">\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Content </label>\
                        <div class="col-sm-9">\
                            <textarea placeholder="Description Content" name="content" class="form-control" required="required"></textarea>\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Background </label>\
                        <div class="col-sm-9">\
                            <select class="form-control" name="class" >\
                                <option value="" seleted="selected">None</option>\
                                <option value="bricky">Red</option>\
                                <option value="green">Green</option>\
                                <option value="purple">Purple</option>\
                                <option value="teal">Teal</option>\
                            </select>\
                        </div>\
                    </div>\
                    <div class="form-group">\
                        <label class="col-sm-2 control-label" for="form-field-12"> Group </label>\
                        <div class="col-sm-9">\
                            <select class="form-control select-group" name="alex_gr_name_select">\
                                <option value="" seleted="selected">None</option>\
                                '+grs_html+
                            '</select>\
                        </div>\
                    </div>\
                </div>\
                <div class="readmore">\
                    <button class="btn" type="reset"><i class="fa fa-times"></i> Cancel</button>\
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Save</button>\
                </div>\
                </form>\
            </div>\
        </li>';
    // Buttons Templates
    var addBtnTpl = 
        '<div class="date_separator alex_btn_add_new">\
            <button class="btn btn-danger">\
                <i class="fa fa-plus"></i> New\
            </button>\
        </div>';
    var editBtnTpl = '<button class="btn btn-primary" ><i class="fa fa-pencil"></i> </button>';
    var deleteBtnTpl = '<button class="btn btn-danger" ><i class="fa fa-trash"></i> </button>';

    /* -------------------------------------------------- */
    /* Registering plugin with jquery function
    /* -------------------------------------------------- */
    $.fn[pluginName] = function(_options) {

        if (_options === undefined || typeof _options === 'object') {
            // Creates a new plugin instance, for each selected element, and
            // stores a reference withint the element's data
            var self = this[0];
            if (!$.data(self, 'plugin_' + pluginName)) {
                $.data(self, 'plugin_' + pluginName, new Timeliner(self, _options));
            }
            return $.data(self, 'plugin_' + pluginName);
        }
    };

    /* -------------------------------------------------- */
    /* Default options of timeliner
    /* -------------------------------------------------- */
    $.fn[pluginName].defaults = {
        // Primary key and dates are coming here
        pk                      : 'id',
        dk                      : 'date',
        itemSelector            : 'div.timeliner_element',
        // Add new button form and handling
        itemTpl                 : itemTpl,
        formTpl                 : formTpl,
        pointTpl                : pointTpl,
        spineTpl                : spineTpl,
        sectionTpl              : sectionTpl,
        addBtnId                : undefined,
        // addBtnId                : "bbbbbbbbbbb",
        addBtnTpl               : addBtnTpl,
        addFrmId                : undefined,
        editBtnTpl              : editBtnTpl,
        deleteBtnTpl            : deleteBtnTpl,
        // Callbacks events for server side handling
        onAdd                   : onAdd,
        onEdit                  : onEdit,
        onDelete                : onDelete,
        // Some functions to help the customization
        sortComparer            : sortComparer,
        dateFormater            : dateFormater,
        pointFormater           : pointFormater,
    };

})( jQuery, window, document );