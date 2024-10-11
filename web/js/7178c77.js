function loadChildren(el) {
  clearChild(el);
  val = el.val();

  var child = $('#'+el.attr('hasChild'))||null;
  var addNew = child.attr('addNew')||null;
  child.empty();

  if(val>0&&child!=null){
     loadSelectData(child, { 'parent': val, 'addNew': addNew });
  } else {
    $(child).prop("disabled", true);
  }
}

function loadSelectData(el, props) {
    var criteries = {
      'parent':props['parent'],
      'extraFields':'extra'
    };
    if (el.attr('type') != '')
      criteries['type'] = el.attr('type');
    $.ajax({method:'post', url:urls.basenom_load_child1, data: {'data': {'criteries':criteries}}, datatype:'json',
      success: function(result) {
        el.prop('disabled',false);
        if(el.attr('placeholder')){
          el.append('<option disabled selected value="0">'+el.attr('placeholder')+'</option>')
        }else{
          el.append($("<option></option>").attr("value", 0).text('--Select--'));
        }
     
        if('addNew' in props && props['addNew']!=null){
          el.append($("<option></option>").attr("value", "new").text('--'+props['addNew']+'--'));
        }
        if('data' in result) {
            $.each(result['data'], function(key,value) {
              var cel = $("<option></option>").attr("value", value['id']).attr('type',value['type']).attr('data-key',value['bnomkey']).text(value['value']);
              if ('selected' in props && props['selected'] == value['id'])
                cel.attr('selected', 'selected');
              el.append(cel);
            });
        }
      }
    });
}

function clearChild(element){
    child = $(element).attr('haschild')||false;
    while(child){
            $('#'+child).empty();
            if($('#'+child).attr('placeholder')){
              $('#'+child).append('<option disabled selected value="0">'+$('#'+child).attr('placeholder')+'</option>')
            }
            $('#'+child).prop('disabled',true);
            child = $('#'+child).attr('haschild')||false;
    }
    if($(element).attr('id')=='aircraft_classtype'&&$(element).val()==149){
       $('#aircraft_subCategory').empty().prop("disabled", true);
    }

 }

function addBaseNom(obj,label){
    var newElement = prompt("Please enter new "+label,'');
    newElement = $.trim(newElement);
    if (newElement != null&&newElement != '') {
        parentId = obj.attr('hasParent')||null;
        if(parentId!=null){
            parentId = $('#'+parentId).val();
        }
        objType = obj.attr('type');
        insertData = {'data':{'p':parentId,'t':objType,'val':newElement, 'validateCustomAjax': true}};

        sendQeury(urls.basenom_add_new, insertData).done(function(data){
            if(data instanceof Object == true) {
              if (undefined !== data['success'] && true == data['success'] && parseInt(data['id'])>0) {
                  obj.append($("<option></option>").attr("data-key", data['bnomkey']).attr("value", data['id']).attr('type',objType).attr('selected','selected').text(newElement)).change();
                  obj.prop('disabled',false);
              } else {
                obj.val(0);
              }
            } else {
              obj.val(0);
            }
        })
        .fail(function() {
          obj.val(0);
        })
    }else{
      obj.find('option:eq(0)').prop('selected', true);
    }
}