var city;
var street;
$(document).ready(function(){

    $('#search').typeahead({
        source: function(query, process) {
            var $url =SITE_URL+ 'http://sport.phpnt.com/ajax/search-city' + query + '.json';
            var $items = new Array;
            $items = [""];
            $.ajax({
                url: $url,
                dataType: "json",
                type: "POST",
                success: function(data) {
                    console.log(data);
                    $.map(data, function(data){
                        var group;
                        group = {
                            id: data.id,
                            name: data.name,
                            toString: function () {
                                return JSON.stringify(this);
                                //return this.app;
                            },
                            toLowerCase: function () {
                                return this.name.toLowerCase();
                            },
                            indexOf: function (string) {
                                return String.prototype.indexOf.apply(this.name, arguments);
                            },
                            replace: function (string) {
                                var value = '';
                                value +=  this.name;
                                if(typeof(this.level) != 'undefined') {
                                    value += ' <span class="pull-right muted">';
                                    value += this.level;
                                    value += '</span>';
                                }
                                return String.prototype.replace.apply('<div style="padding: 10px; font-size: 1.5em;">' + value + '</div>', arguments);
                            }
                        };
                        $items.push(group);
                    });

                    process($items);
                }
            });
        },
        property: 'name',
        items: 10,
        minLength: 2,
        updater: function (item) {
            var item = JSON.parse(item);
            console.log(item.name);
            $('#hiddenID').val(item.id);
            return item.name;
        }
    });


    $(".addComplain").click(function(){
        $("#form-complain").modal("show");
    });

    $("#sendComplain, #complainPlace").click(function(e) {
        e.preventDefault();
        var id = $(".addComplain").data('id');
        var text = $("#complainText").val();
        if (!text)
            text = $("#abuse-text").val();

        var type = $("#complainType").val();
        if(!text){
            alert("Напишите причину");
            return false;
        }
        $.ajax({
            url : "/ajax/sendcomplain",
            method : "POST",
            data : {id : id, text : text, type : type},
            dataType : "html",
            success: function(){
                $("#form-complain, #sendAbuseModal").modal("hide");
                $(".addComplain, #addComplain").hide();
            }
        });
    })


    $('[data-toggle="tooltip"]').tooltip();

    setTimeout(function(){
        if ($("#event-type input[name='Event[type]']:checked").val() == 1) {
            $("#manyEvent").hide();
            $("#oneEvent").show();
        } else {
            $("#manyEvent").show();
            $("#oneEvent").hide();
        }
        showAddress(city+" "+street)
    },300)

    $("#event-type input").change(function(){
        if ($(this).val() == 1) {
            $("#manyEvent").hide();
            $("#oneEvent").show();
        } else {
            $("#manyEvent").show();
            $("#oneEvent").hide();
        }

    })

    $(".acceptRequest").click(function(){
        var request_id = $(this).data("request_id");
        var type = $(this).data("type");
        var curent = $(this).closest(".blockForRequest");
        $.ajax({
            url : "/ajax/requestdo",
            method : "POST",
            data : {request_id : request_id, type : type},
            dataType : "html",
            success: function(){
                if(type == 'accept') {
                    $(curent).html("Статус: Принят");
                    $("#req_tab2").append($(curent).closest(".free-time").get(0).outerHTML + "</br>");
                    $(curent).closest(".free-time").remove()
                }
                else {
                    $(curent).html("Статус: Отклонен");
                    $("#req_tab3").append($(curent).closest(".free-time").get(0).outerHTML + "</br>");
                    $(curent).closest(".free-time").remove()
                }

            }
        });

    })

    var curentEvent;
    $(".addRequest").click(function(){
        curentEvent = $(this).closest(".block-menu");
        $("#eventRules").modal("show");
    })

    $(".IagreeWithRules").click(function(){
        var event_id = $(curentEvent).find('.addRequest').data("event_id");

        $.ajax({
            url : "/ajax/sendrequest",
            method : "POST",
            data : {event_id : event_id},
            dataType : "html",
            success: function(){
                $("#eventRules").modal("hide");
                setTimeout(function(){
                    $(curentEvent).html("<h4><small>Вы подали заявку на это событие</small></h4>");
                },250)
            }
        });
    })


    $("#adminplacefilterform-country, #event-country_id, #place-country_id, #eventfilter-country, #addressprofile-country").change(function(){

        var country_id = $(this).val();
        $.ajax({
            url : "/ajax/getcities",
            method : "POST",
            data : {country_id : country_id},
            dataType : "html",
            success: function(result){
                $("#place_for_city select").html(result);
                $("#eventfilter-city").html(result);
                $("#adminplacefilterform-city, #event-city_id, #place-city_id, #eventfilter-city, #addressprofile-city").val('').trigger('change');
                $("#select2-adminplacefilterform-city-container .select2-selection__clear, #select2-addressprofile-country-container .select2-selection__clear, #select2-event-city_id-container .select2-selection__clear, #select2-place-city_id-container .select2-selection__clear, #select2-eventfilter-city-container .select2-selection__clear").click();
            }
        });
    })

    $("#event-city_id").change(function(){
        var city_id = $(this).val();
        $.ajax({
            url : "/ajax/getplaces",
            method : "POST",
            data : {city_id : city_id},
            dataType : "html",
            success: function(result){
                city =  $("#event-city_id option[value='"+city_id+"']").text();
                $("#place_for_palces select").html(result);
                $("#place_for_palces").show();
                $("#map_canvas").show();
                $('#event-place_id').val('');
                $("#event-place_id").trigger('change');
                showAddress(city)
                $("#select2-event-place_id-container .select2-selection__clear").click();
            }
        });
    })

    $("#event-place_id").change(function(){
        street = $("#event-place_id option[value='"+$(this).val()+"']").data("address");
        if(!city){
            city_id = $("#event-city_id").val();
            city =  $("#event-city_id option[value='"+city_id+"']").text();
        }

        showAddress(city+" "+street)
    })

    $("#addPlace").click(function(){
        $("#new_place").modal("show");
        $("#new-place-form").removeClass("end");
    })

    $("#new-place-form").submit(function(e){
        e.preventDefault();
        var city_id = $("#event-city_id").val();
        var name  = $("#place-name").val();
        var street = $("#place-adress").val();
        setTimeout(function(){
            $err = $("#new-place-form").find('.has-error').length;
            if(!$err && !$("#new-place-form").hasClass("end") ) {
                $("#new-place-form").addClass("end");
                $.ajax({
                    url : "/ajax/addplace",
                    method : "POST",
                    data : {city_id : city_id, name : name, street : street},
                    dataType : "json",
                    success: function(result){
                        $("#new_place").modal("hide");
                        $('#place_for_palces select option').removeAttr("selected")
                        $('#place_for_palces select').append("<option selected='selected' value='"+result.id+"' data-address='"+result.street+"'>"+result.name+" ("+result.street+")</option>");
                        $('#place_for_palces').val(result.id);
                        $("#event-place_id").trigger('change');
                    }
                });
            }
        },200)
    })

    $(".event_rules").click(function(){
        $("#rulesFromBlaSport").modal("show");
    })

    $(".adult_rules").click(function(){
        $("#rulesFromAdults").modal("show");
    })

    $(".showInMap").click(function(){
        $("#show_place").modal("show");
        var address = $(this).data("address");
        showAddress(address);
    })






})

