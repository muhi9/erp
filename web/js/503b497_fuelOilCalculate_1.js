(function() {
    var fuelProps = {
        'fuelBeforeFlight':{
            'value':0,
            'fields': {
                'field1':'lastFuel',
                'field2':'fuelUplifted'
            },
            'operator': 'add'
        },
        'fuelUsed':{
            'value':0, 
            'fields':{
                'field1':'fuelBeforeFlight',
                'field2':'fuelAfterFlight'
            },
            'operator': 'sub' 
        },
    },
    oilProps = {
        'oilBeforeFlight':{
            'value':0,
            'fields':{
                'field1':'lastOil',
                'field2':'OilUplifted'
            },
            'operator': 'add'
        },
        'oilUsed':{
            'value':0, 
            'fields':{
                'field1':'oilBeforeFlight',
                'field2':'oilAfterFlight'
            },
            'operator': 'sub' 
        },
    },
	base = siteUrl,
    formid ='flightbundle_flight';// $(document).find('form').attr('name');
    //onload
    //checkTachoHobbs($('#flightbundle_flight_aircraft'));
    //loadProps();
    
    $(document).on('change', '.calculateFuel input', function(){
        $.each(fuelProps, function(target, props){
            let field1 = $('#'+formid+'_'+props.fields.field1).val(),
                field2 = $('#'+formid+'_'+props.fields.field2).val(); 
            if(field1 != '' && field2 != ''){
               targetVal = calculate({'field1':field1, 'field2':field2},props.operator);
               $('#'+formid+'_'+target).val(targetVal);
            }
        })
    })

    function calculate(val, operator){
        result = 0.00;
        if(operator == 'add') {
            result = parseFloat(val.field1) + parseFloat(val.field2); 
        }
        if(operator == 'sub') {
            result = parseFloat(val.field1) - parseFloat(val.field2); 
        }
        return result;
    }
    

    

    function getLastKnow(aircraft){
        var val = $(aircraft).val();
        if(val>0){
            $('.ajax_loading_mask_logs').show();
            data = {'fields':['tacho','hobbs','tachoStart','hobbsStart'],'criteries':{'id':val}};
            $.ajax({method:'post', url:urls.aircraft_read_list, data:{'data':data},datatype:'json',
                success: function(result){
                    if(result!=0){
                        if (result[val].lastFuel) {

                            $('#flightbundle_flight_tachoStart, #flightbundle_flight_tachoStop, #flightbundle_flight_tachoTime').prop('disabled',false);
                            tacho = parseFloat($('#flightbundle_flight_tachoStart').val());
                            if (tacho == '0.00')
                                tacho = parseFloat(result[val].tachoStart);
                            $('#flightbundle_flight_tachoStart').val(tacho.toFixed(2));
                        } else {
                            //$('#flightbundle_flight_tachoStart, #flightbundle_flight_tachoStop, #flightbundle_flight_tachoTime').closest('div.form-group').hide();
                            $('#flightbundle_flight_tachoStart, #flightbundle_flight_tachoStop, #flightbundle_flight_tachoTime').prop('disabled',true);
                        }

                        if (result[val].hobbs) {
                          $('#flightbundle_flight_hobbsStart, #flightbundle_flight_hobbsStop, #flightbundle_flight_hobbsTime').prop('disabled',false);
                          hobbs = parseFloat($('#flightbundle_flight_hobbsStart').val());
                          if (hobbs == '0.00')
                              hobbs = parseFloat(result[val].hobbsStart);
                          $('#flightbundle_flight_hobbsStart').val(hobbs.toFixed(2));
                        } else {

                         $('#flightbundle_flight_hobbsStart, #flightbundle_flight_hobbsStop, #flightbundle_flight_hobbsTime').prop('disabled',true);
                        }
                    }
                },
            })
            $('.ajax_loading_mask_logs').hide();
        }

    }
    function loadProps(){
     //return false;
        $('#tabs_logs .calendar').each(function(evt){
            if($(this).val()!='') {

                $(this).datepicker({
                    'format':i18n.date_format
                })
                date = $(this).datepicker('getDate');
                if (date) {
                    var key = $(this).attr('r');
                    var sub = $(this).attr('t');
                    if(key&&sub) {
                        var date = new Date(date);
                        var time = $('#'+$(this).attr('id')+'_time').val()||'00:00';
                        time = time.split(':');
                        date.setHours(time[0],time[1]);
                        timeProps[key][sub] = date.getTime();
                    }
                }
           }
        })

        calclulate(timeProps);
    }
})();