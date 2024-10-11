"use strict";function DT(tableSelector,options){if(!this||this===window){return new DT(tableSelector,options)}else{this.table=$(tableSelector);this.options=$.extend({},this.options,options||{});this.init()}}DT.prototype={options:{responsive:false,searchDelay:500,processing:true,serverSide:true,pagingType:"full_numbers",reorderColumn:false},table:null,searchForm:null,exportButtons:null,api:null,headerReorderSaveButton:null,reorderOldCreatedRowCallback:undefined,init:function(){var that=this,temp=$.extend({},this.options);if(this.table.data("search-form-selector")){this.searchForm=$(this.table.data("search-form-selector"))}if(this.options.searchFields){temp.initComplete=function(){that.api=that.api||this.api();that.searchFieldsInitCompleteWrapper();if(that.options.initComplete){that.options.initComplete.apply(this,arguments)}}}if(!!this.options.reorderColumn){this.reorderOldCreatedRowCallback=temp.createdRow;temp.createdRow=function(row,data,index){$("td."+that.options.reorderColumn.className,row).prepend('<i class="la la-arrows-v"></i>');if(that.reorderOldCreatedRowCallback){that.reorderOldCreatedRowCallback.apply(this,arguments)}};temp.rowReorder={selector:"."+this.options.reorderColumn.className+" > i.la",update:false}}delete temp.reorderColumn;this.api=this.table.DataTable(temp);this.prepareExportButtons();this.prepareSearchFormControls();this.prepareReorderControls()},prepareExportButtons:function(){var that=this;this.exportButtons=this.table.parents(".k-portlet:first").find("a[data-table-export]").attr("data-table",this.table.attr("id")).unbind("click").click((function(){var url=that.api.ajax.url(),params=$.extend({},that.api.ajax.params()||that.api.draw().ajax.params(),{_:Date.now()});params.export="csv";url+=url.indexOf("?")==-1?"?":url[url.length-1]=="?"||url[url.length-1]=="&"?"":"&";url+=that.toURL(params);window.location=url;return false}))},prepareSearchFormControls:function(){if(!this.searchForm){return}var that=this;this.searchForm.on("click","button",(function(){if($(this).is("#reset")){that.searchForm.trigger("reset").find("input, select, textarea").val("").change()}that.searchForm.serializeArray().map((function(param){that.changeAJAXParemeter(param.name,param.value,false,false)}));that.reload()}))},searchFormApply:function(){this.searchForm.find("button[name*=apply]:first").click()},searchFormReset:function(){this.searchForm.trigger("reset").find("input, select, textarea").val("").change();this.searchFormApply()},prepareReorderControls:function(){if(!this.options.reorderColumn){return}var that=this;this.bind("row-reorder",(function(event,diff,edit){if(diff.length>1){var data={},i=0,temp;for(;i<diff.length;i++){temp=that.api.row(diff[i].node).data();data["data["+i+"][id]"]=temp.id;data["data["+i+"][order]"]=temp[that.options.reorderColumn.field]}temp=data["data[0][order]"];for(i=1;i<diff.length;i++){data["data["+(i-1)+"][order]"]=data["data["+i+"][order]"]}data["data["+(i-1)+"][order]"]=temp;sendQeury(that.options.reorderColumn.url,data).done((function(){that.reload()}))}}));this.table.on("click","td.reorder",(function(){that.table.find("td.reorder i").remove();that.api.rowReorder.disable();if(!that.headerReorderSaveButton){that.headerReorderSaveButton=$('<a class="btn mar k-margin-l-5 reordeBasenoms btn-sm btn-elevate btn-brand saveOrder" title="Save order"><span></span></a>');that.table.find("th."+that.options.reorderColumn.className).append(that.headerReorderSaveButton);that.headerReorderSaveButton.click((function(){var data={};that.table.find(".orderNumber").each((function(i){data["data["+i+"][id]"]=$(this).data("id");data["data["+i+"][order]"]=parseInt(this.value)}));sendQeury(that.options.reorderColumn.url,data).done((function(){that.reload();that.headerReorderSaveButton.remove();that.headerReorderSaveButton=null;that.api.rowReorder.enable()}))}))}if($(".orderNumber",this).length==0){var tmp=that.api.row(this.parentNode).data();$('<span> => <input type="number" class="orderNumber" min="0" max="100000" data-id="'+tmp.id+'" value="'+tmp.order+'"></span>').appendTo(this).find("input").focus()}}))},changeAJAXParemeter:function(parameter,value,instantReload,onlyReturnAddress){var url=this.api.ajax.url();url+=url.indexOf("?")==-1?"?":url[url.length-1]=="?"||url[url.length-1]=="&"?"":"&";url=url.replace(new RegExp("([&?])"+encodeURIComponent(parameter)+"=[^&$]*&?","i"),"$1");url+=encodeURIComponent(parameter)+"="+encodeURIComponent(value);if(instantReload){this.api.ajax.url(url);this.reload()}else if(!onlyReturnAddress){this.api.ajax.url(url)}return url},reload:function(){this.api.ajax.reload()},bind:function(event,callback){this.api.on(event,callback);return this},unbind:function(event,callback){this.api.off(event,callback);return this},one:function(event,callback){this.api.one(event,callback);return this},searchFieldsInitCompleteWrapper:function(){var that=this,filtersRow=$('<tr class="filter"></tr>').appendTo(this.table.find("thead")),searchFields=this.options.searchFields;if(this&&this.api&&this.api.columns){this.api.columns().every((function(colIndex,e){if(!that.api.column(colIndex).visible()){return}var column=searchFields[colIndex]||{},input="&nbsp;";switch(column["type"]){case"input":input=$('<input type="search" class="form-control form-control-sm form-filter k-input" data-col-index="'+colIndex+'"/>');break;case"select":input=$('<select class="form-control form-control-sm form-filter k-input" title="Select" data-col-index="'+colIndex+'"><option value="">Select</option></select>');if(column["value"]!=null){$.each(column["value"],(function(val,txt){input.append('<option value="'+val+'">'+txt+"</option>")}))}break;case"date":input=$('<input type="text" readonly="readonly" id="date_'+colIndex+'" class="form-control form-control-sm form-filter k-input datepick" title="Date" placeholder="Select date" data-col-index="'+colIndex+'" />');input.datepicker({format:"dd.mm.yyyy",autoclose:true,todayBtn:"linked",clearBtn:true,enableOnReadonly:true});break;case"date_range":input=$('<input  class="form-control form-control-sm form-filter datepick k-input" data-col-index="'+colIndex+'"  readonly="readonly"  placeholder="Select date" type="text"/>');input.daterangepicker({autoUpdateInput:false,locale:{cancelLabel:"Clear"},opens:["right","center","left","left"][Math.floor((colIndex+1)/searchFields.length*3)]}).on("apply.daterangepicker",(function(ev,picker){var col=that.api.columns($(this).data("col-index"));this.value=picker.startDate.format("DD.MM.YYYY")+" - "+picker.endDate.format("DD.MM.YYYY");if(col.search()!==this.value){col.search(this.value).draw()}})).on("cancel.daterangepicker",(function(ev,picker){var col=that.api.columns($(this).data("col-index"));this.value="";if(col.search()!==this.value){col.search(this.value).draw()}}));break}filtersRow.append($("<th>").append(input))}))}filtersRow.find(".k-input").bind("change keyup",(function(){var column=that.api.columns($(this).data("col-index"));if(column.search()!==this.value){column.search(this.value).draw()}}))},toURL:function(obj,prefix){var output=[],key,k,v;for(key in obj){if(obj.hasOwnProperty(key)){k=prefix?prefix+"["+key+"]":key;v=obj[key];if(v!==null&&typeof v==="object"){output[output.length]=this.toURL(v,k)}else{output[output.length]=encodeURIComponent(k)+"="+encodeURIComponent(v)}}}return output.join("&")}};function DatatablesDataSourceAjaxServer(tableId,opts){var table=$("#id_"+tableId);var dtOpts={};if("searchFields"in opts){dtOpts["initComplete"]=function(){if(arguments.length==1){var oThis=arguments[0]}else var oThis=this;var rowFilter=$('<tr class="filter"></tr>').appendTo($("#id_"+tableId+" thead"));var searchFields=opts.searchFields;var input;oThis.api().columns().every((function(i,e){if(searchFields[i]){var column=searchFields[i];if(column["type"]==null){input=""}if(column["type"]=="input"){input=$('<input type="search" class="form-control form-control-sm form-filter k-input" data-col-index="'+i+'"/>')}if(column["type"]=="select"){input=$('<select class="form-control form-control-sm form-filter k-input" title="Select" data-col-index="'+i+'"><option value="">Select</option></select>');if(column["value"]!=null){$.each(column["value"],(function(d,j){$(input).append('<option value="'+d+'">'+j+"</option>")}))}}if(column["type"]=="date"){input=$('<input type="text" readonly="readonly" id="date_'+i+'" class="form-control form-control-sm form-filter k-input datepick" title="Date" placeholder="Select date" data-col-index="'+i+'" />');$(input).datepicker({format:"dd.mm.yyyy",autoclose:true,todayBtn:"linked",clearBtn:true,enableOnReadonly:true})}if(column["type"]=="date_range"){input=$('<input  class="form-control form-filter datepick k-input" data-col-index="'+i+'"  readonly="readonly"  placeholder="Select date" type="text"/>');$(input).daterangepicker({autoUpdateInput:false,locale:{cancelLabel:"Clear"},opens:"left"});$(input).on("apply.daterangepicker",(function(ev,picker){$(this).val(picker.startDate.format("DD.MM.YYYY")+" - "+picker.endDate.format("DD.MM.YYYY"));var t=$(table).DataTable();var that=t.columns($(this).data("col-index"));if(that.search()!==this.value){that.search(this.value).draw()}}));$(input).on("cancel.daterangepicker",(function(ev,picker){$(this).val("");var t=$(table).DataTable();var that=t.columns($(this).data("col-index"));if(that.search()!==this.value){that.search(this.value).draw()}}))}}else{input=""}$(input).appendTo($("<th>").appendTo(rowFilter))}))}}var defaultOpts={responsive:true,searchDelay:500,processing:true,serverSide:true,pagingType:"full_numbers"};for(var okey in defaultOpts){if(okey in opts){dtOpts[okey]=opts[okey]}else{dtOpts[okey]=defaultOpts[okey]}}if("removeSearching"in opts){dtOpts["searching"]=false}if("order"in opts){dtOpts["order"]=opts["order"]}if("dom"in opts){dtOpts["dom"]=opts["dom"]}if("ajaxUrl"in opts)dtOpts["ajax"]=opts["ajaxUrl"];if("ajax"in opts)dtOpts["ajax"]=opts["ajax"];dtOpts["columns"]=opts["columns"];dtOpts["columnDefs"]=opts["columnDefs"];if("fnRowCallback"in opts)dtOpts["fnRowCallback"]=opts["fnRowCallback"];if("initComplete"in opts){if("initComplete"in dtOpts){var old_init=dtOpts["initComplete"];dtOpts["initComplete"]=function(){old_init(this);var cinit=opts["initComplete"];cinit(this)}}else{dtOpts["initComplete"]=opts["initComplete"]}}if("scrollY"in opts){dtOpts["scrollY"]=opts["scrollY"]}if("scrollCollapse"in opts){dtOpts["scrollCollapse"]=opts["scrollCollapse"]}if("paging"in opts){dtOpts["paging"]=opts["paging"]}if("lengthMenu"in opts){dtOpts["lengthMenu"]=opts["lengthMenu"]}if("rowReorder"in opts){dtOpts["rowReorder"]=opts["rowReorder"]}if(opts.callbacks!==undefined){for(var i in opts.callbacks){dtOpts[i]=opts.callbacks[i]}}table.DataTable(dtOpts);if("searchFields"in opts){filters("#id_"+tableId)}}function filters(table){var table=$(table).DataTable();$(document).on("change keyup",".k-input",(function(){var that=table.columns($(this).data("col-index"));if(that.search()!==this.value){that.search(this.value).draw()}}))}function gridApiCustomParam(grid,param,val,reload){var url=grid.dataTable().api().ajax.url();var sep="&";var sstr=param+"=";if(url.indexOf("?")<0)sep="?";if(url.indexOf(param+"=")<0)url=url+sep+sstr+val;else{var replaceStr=new RegExp("("+param+")=([\\w])(&{0,1})");url=url.replace(replaceStr,"$1="+val+"$3")}if(reload){grid.dataTable().api().ajax.url(url);grid.dataTable().api().ajax.reload()}return url}$("a[data-table-export]").click((function(){var $this=$(this),$table=false,api=false,params={},url;$this.parents().each((function(){var tmp=$(this).find("table.dataTable[id]");if(tmp.length==1&&tmp.dataTable().api()){$table=tmp;api=tmp.dataTable().api();return false}}));if(api){url=api.ajax.url().split("?");if(url[1]){url[1].replace(/([^=]+)=([^&]+)(&|$)/g,(function(found,key,val){params[decodeURIComponent(key)]=decodeURIComponent(val)}))}params.export=$this.data("table-export");params=$.extend(params,api.ajax.params());$table.trigger("export.prepare",params);window.location=url[0]+"?"+$.param(params)}else if(window.lastFlightSearch){window.location=window.lastFlightSearch+"&export="+$this.data("table-export")}return false}));