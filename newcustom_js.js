
function getPhonecountry(){
	
	var input = document.querySelector(".mobile_number");
	window.intlTelInput(input, {
		autoPlaceholder: "off",
		nationalMode: false,
		placeholderNumberType: "MOBILE",
		utilsScript: "assets/build/js/utils.js",
	});

}

$( "#reg-form" ).submit(function( event ) {

	var form_val_true = false;
	$("#captcha_err").empty(); 

	if(grecaptcha.getResponse() == ""){

		$("#captcha_err").empty();
		$("#captcha_err").append("Please confirm you are not a robot");
		form_val_true = true;
	} 

	if(form_val_true == true){
		event.preventDefault();
		//  $('html, body').animate({ scrollTop: $('#reg-form').offset().top }, 'slow');
		return false;
	}
});

/////////////////////////////////////////////////////////////////////////////////
$(document).ready(function(){

	$(".dropdown-item-nav").click(function(){
		window.location.href = $(this).attr('href');
	});

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	get_rates_countries_list();
	get_currency_countries_list();

	$("#wizard").steps({
	    headerTag: "h3",
	    bodyTag: "section",
	    transitionEffect: "slideLeft",
	    enableFinishButton: false,
	    enablePagination: false,
	    enableAllSteps: true,
	    titleTemplate: "#title#",
	    cssClass: "tabcontrol"
	});

	$("#Exdate").datepicker({
      showOtherMonths: true,
      selectOtherMonths: true
    });

    $('#smartwizard').smartWizard({
	 	autoAdjustHeight:true,
	 	theme: 'default',
	 	 toolbarSettings: {
            showNextButton: false, // show/hide a Next button
            showPreviousButton: false, // show/hide a Previous button
        },
        anchorSettings: {
            anchorClickable: true, // Enable/Disable anchor navigation
            enableAllAnchors: true // Activates all anchors clickable all times
            
        },
        transitionEffect: 'fade', // Effect on navigation, none/slide/fade
        transitionSpeed: '400'
	});
    
	
   
    $.fn.inputFilter = function(inputFilter) {
	    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
	      if (inputFilter(this.value)) {
	        this.oldValue = this.value;
	        this.oldSelectionStart = this.selectionStart;
	        this.oldSelectionEnd = this.selectionEnd;
	      } else if (this.hasOwnProperty("oldValue")) {
	        this.value = this.oldValue;
	        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      }
	    });
	};

//////////////////////////   Rate Calculator /////////////////////////////////////
	
	$("#weightShip").inputFilter(function(value){return /^-?\d*[.,]?\d*$/.test(value); });

    $("#weightShip").keyup(function(){
    
		let weightShip = $(this).val();
		let fromShip = $("#fromShip").val();
		let toShip = $("#toShip").val();

		if (weightShip == '' && weightShip == 0 || fromShip =='' || toShip ==''){
			
			$("#check_rates").attr('disabled','disabled');
			$("#check_rates").css('background-color','#bdbfc2');
		}
		if (weightShip != '' && weightShip > 0 && fromShip !='' && toShip !=''){
			
			$("#check_rates").css('background-color','#00eb8c');
			$("#check_rates").removeAttr('disabled');
		}
    });

    $(".shipping-dropdown").change(function(){
    
		let weightShip = $("#weightShip").val();
		let fromShip = $("#fromShip").val();
		let toShip = $("#toShip").val();

		if (weightShip == '' && weightShip == 0 || fromShip =='' || toShip ==''){
			
			$("#check_rates").attr('disabled','disabled');
			$("#check_rates").css('background-color','#bdbfc2');
		}
		if (weightShip != '' && weightShip > 0 && fromShip !='' && toShip !=''){
			
			$("#check_rates").css('background-color','#00eb8c');
			$("#check_rates").removeAttr('disabled');
		}
    });

    $(document).on('click','#check_rates',function(){
    	event.preventDefault();
    	
    	let weight = 0;
    	let fromShip = $("#fromShip").val();
		let toShip = $("#toShip").val();
		let weightShip = $("#weightShip").val();
		let unit = $("#unit").val();

		if (unit=='LB'){weight = (parseFloat(weightShip) * 0.45359237);}
		else{weight = weightShip;}

		if (fromShip =='' || fromShip == null || toShip =='' || toShip == null ){
			
			$("body > div > section.content > div > div.row.hp-widgets > div:nth-child(1) > div > div > div.widget__content").css('height','540px');
			$(".ShippingfeesDiv").hide();
			$("#ShippingfeesErrorDiv > p").html("Shipping from and Shipping to are required");
			$("#ShippingfeesErrorDiv").show();
		}
		else
		{	
			$(".ShippingfeesDiv").hide();
			$("body > div > section.content > div > div.row.hp-widgets > div:nth-child(1) > div > div > div.widget__content").css('height','580px');		
			$(".spinner-rc").css('display','block');

			$.ajax({
				type:'post',
				url:'/ajaxcall-rate-calculate',
				data:{fromShip:fromShip, toShip:toShip, weightToship:weight, unit:unit},
				success:function(data){
					
					$("body > div > section.content > div > div.row.hp-widgets > div:nth-child(1) > div > div > div.widget__content").css('height','580px');
					$(".spinner-rc").css('display','none');
					$("#ShippingfeesErrorDiv").hide();
					$(".Shippingfees").html(data+ " USD");
					$(".ShippingfeesDiv").show();
				}
			});
		}
	});
		
	function get_rates_countries_list()
	{	
		let from = new Array();
		let to = new Array();
		from.push('<option value=""></option>');
		to.push('<option value=""></option>');
		$.ajax({
			type:'get',
			url:'/ajaxcall-rate-countries-list',
			success:function(data){
				for (var i = 0; i < data.length; i++) {

					from.push('<option value="'+data[i]['country_from']+'">'+data[i]['country_from']+'</option>');
					to.push('<option value="'+data[i]['country_to']+'">'+data[i]['country_to']+'</option>');
				}
				$("#fromShip").html($.unique(from));
				$("#toShip").html($.unique(to));
			}
		});
	}
//////////////////////////   Rate Calculator /////////////////////////////////////

//////////////////////////   Currency Converter //////////////////////////////////
	function get_currency_countries_list()
	{	
		let curr_from = new Array();
		let curr_to = new Array();
		curr_from.push('<option value=""></option>');
		curr_to.push('<option value=""></option>');
		$.ajax({
			type:'get',
			url:'/ajaxcall-currency-country-list',
			success:function(data){
				for (var i = 0; i < data.length; i++) {

					curr_from.push('<option value="'+data[i]['code']+'">'+data[i]['country']+' ('+data[i]['code']+')</option>');
					curr_to.push('<option value="'+data[i]['code']+'">'+data[i]['country']+' ('+data[i]['code']+')</option>');
				}
				$("#fromCurr").html($.unique(curr_from));
				$("#toCurr").html($.unique(curr_to));
			}
		});
	}

	$("#amount").inputFilter(function(value) {return /^\d*$/.test(value); });
	$("#amount").keyup(function(){
    
		let amount_curr = $(this).val();

		if (amount_curr == '' && amount_curr == 0){
			
			$("#exchange").attr('disabled','disabled');
			$("#exchange").css('background-color','#bdbfc2');
		}
		if (amount_curr != '' && amount_curr > 0){
			
			console.log(amount_curr);
			$("#exchange").css('background-color','#00eb8c');
			$("#exchange").removeAttr('disabled');
		}
    });

	$(document).on('click', '#exchange', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	let fromCurr = $("#fromCurr").val();
		let toCurr	=$("#toCurr").val();
		let amount = $("#amount").val();

		if (fromCurr =='' || fromCurr == null || toCurr =='' || toCurr == null ){
			
			$("#ExchangeAmoutDiv").hide();
			$("#ExchangeAmoutErrorDiv").show();
			$("#ExchangeAmoutErrorDiv > p").html("From currency and To currency are required");
		}
		else
		{	
			$("#ExchangeAmoutDiv").hide();
			$(".spinner-cc").css('display','block');

			$.ajax({
				type:'post',
				url:'/ajaxcall-exchange-currency',
				data:{fromCurr:fromCurr, toCurr:toCurr, amount:amount},
				success:function(data){
					
					$(".spinner-cc").css('display','none');
					$("#ExchangeAmoutErrorDiv").hide();
					$("#ExchangeAmoutDiv").show();
					$("#ExchangeAmout").html(data+" "+toCurr);
					
				}
			});
		}
	});

//////////////////////////   Currency Converter //////////////////////////////////

/////////////////////////// Update Profile ///////////////////////////////////////
	$.validate({
		form : '.wizard-form',
		modules : 'toggleDisabled',
  		disabledFormFilter : '.wizard-form',
	});
	$('#address').restrictLength( $('#max-length-element') );

///////////////////////// For Contact Page ////////////////////////////////////////

	$.validate({
		form : '.change-password-form',
		modules : 'security, toggleDisabled',
  		disabledFormFilter : '.change-password-form',
	});

	$.validate({
		form : '#forgot_form',
		modules : 'toggleDisabled',
  		disabledFormFilter : '#forgot_form',
	});
});


///////////////////////// Worked by Arshad      /////////////////////////

/*shopping directory ajax call*/
 $("#sel_country").change(function() {
 var county_code = $(this).val();
 $(".spinner-rc").css('display','block');

 $.ajax({
 		type:'get',
		url:'/ajaxcall-shopping-directory/'+county_code,
		success:function(data){
				$(".spinner-rc").css('display','none');
				$('#app_ajx_data').html('');
				$('#app_ajx_data').html(data);	
			}
		});
	});


// $(document).on('click', '.read_more_tag', function(event) {	
	
// 	event.preventDefault();
// 	var $class = $(this).closest('.body_row').children('.announcement_body').css('white-space');
	
// 	if ($class == 'nowrap') {
// 		$(this).closest('.body_row').children('.announcement_body').css('white-space','normal');
// 		$(this).html('less');
// 	}
// 	else
// 	{
// 		$(this).closest('.body_row').children('.announcement_body').css('white-space','nowrap');
// 		$(this).html('more');
// 	}
	
// });	

