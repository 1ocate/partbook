<?php 
	session_start();
	require_once 'config.php';
	require_once 'dbh.php';
	require_once 'functions.php';

	if (empty($_SESSION)){
		//header("location:login.php");
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel Collapse Demo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

	
</head>
<body>
<?php

// check submit line value
if (!empty($_POST['code']) || $_POST['machine'] || $_POST['quality'] || $_POST['qty'])	{ 
	$code = $_POST['code'];
	$machine = $_POST['machine'];
	$quality = $_POST['quality'];
	$qty = $_POST['qty'];
}

// user information
if (!empty($code) )	{
	$sql = "INSERT INTO require_part (fk_user)
	VALUES ('12')";

	// find last insert require_part id
	if ($conn->query($sql) === TRUE) {
		$require_id = $conn->insert_id;
	}

	foreach( $code as $key => $n ) {
		$sql = "INSERT INTO require_part_line (fk_require,partname,machine,quality,qty)
		VALUES ('$require_id','$code[$key]','$machine[$key]','$quality[$key]','$qty[$key]')";
		$conn->query($sql);
	}	

	unset($code);
	unset($machine);
	unset($qty);
	unset($require_id);
	unset($_POST['code']);
	unset($_POST['machine']);
	unset($_POST['qty']);
	header('Location: '.$_SERVER['PHP_SELF']);
}



?>

	<style>
		.ui-combobox {
			position: relative;
			display: inline-block;
			width:100%;
			
		}
		.ui-combobox-toggle {
			position: absolute;
			top: 0;
			bottom: 0;
			margin-left: -1px;
			padding: 0;
			/* adjust styles for IE 6/7 */
			*height: 1.7em;
			*top: 0.1em;
		}
		.ui-combobox-input {
			margin: 0;
			padding: 0.3em;
		}
	</style>

	<script>
		$(document).ready(function() {
			
			// if no input submit disable
			//$("#btnSubmit").attr("disabled", true);

			// button function "add"
			$('#plus5').click(function() {

				// reading value from form
				var code = $('#code').val();
				var machine = $('#machine').val();
				var qty = $('#qty').val();
				var quality = $('#quality').val();

				// require form value				
				if(code==''){
					alert("Please input code");
					$( "#code" ).focus();
					return false;
				
				} else if((quality==null)){
					alert("Please input "+quality+".");
					$( "#quality" ).focus();
					return false;
				}
				// qty default 1
				if((qty=='')){
					qty = 1;
					
				}

				// add to part list when click add button
				$('#dynamic_form ').append(
					'<div class="row">'
					+ '<div class="col-9 mr-auto">'+code+' '+machine+' '+qty+'</div><div class="col-2"><a href="javascript:void(0)" class="btn btn-danger removeRow" id="minus5">X</a></div>'
					+ '<input type="hidden" name="code[]" value="'+code+'" />'
					+ '<input type="hidden" name="machine[]" value="'+machine+'" />'
					+ '<input type="hidden" name="quality[]" value="'+quality+'" />'
					+ '<input type="hidden" name="qty[]" value="'+qty+'" />'
					+ '</div>'
				);

				// when click add input type "text" value reset	
				var input = $('input[type=text],textarea');
				input.val("");
				
				// when click add reset quality select 
				quality = '';

			});
			
			// when click button "remove" will remove row
			$(document).on('click', '#minus5', function() {
  				$(this).closest('.row').remove();

			});
	
			// reading code number	
			$( "#code" ).autocomplete({
				minLength: 4,
				source: 'data.php'
				/*select: function( event, ui ) {
				
				$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );
				return false; }*/
			});
			
			// for machine input and select combobox
			(function ($) {
			$.widget("ui.combobox", {
				_create: function() {
					var wrapper = this.wrapper = $("<span />").addClass("ui-combobox")
						, self = this;
					
					this.element.wrap(wrapper);

					this.element
						.addClass("ui-state-default ui-combobox-input ui-widget ui-widget-content ui-corner-left")
						.autocomplete($.extend({
							minLength: 0
						}, this.options));

					$("<a />")
						.insertAfter(this.element)
						.button({
							icons: {
								primary: "ui-icon-triangle-1-s"
							},
							text: false
						})
						.removeClass("ui-corner-all")
						.addClass("ui-corner-right ui-combobox-toggle")
						.click(function () {
							if (self.element.autocomplete("widget" ).is(":visible")) {
								self.element.autocomplete("close");
								return;
							}

							$(this).blur();
							
							self.element.autocomplete("search", "");
							self.element.focus();
						});
						$(".ui.combobox").css("width", "100%");
						$(".ui-combobox-input").css("width", "100%");
						$(".ui-combobox-input").width($(".ui-combobox-input").width() - 32);
				},

				destroy: function() {
					this.wrapper.remove();
					$.Widget.prototype.destroy.call(this);
				}
			});
			})(jQuery);

			// for machine input and select combobox
			$("#machine").combobox({
				source: function( request, response ) {
				let partname = $('#code').val();
				const partno = partname.split(" ");

					$.getJSON( "machine.php", {
						term : request.term,
						partno : partno[0]
					}, response );
								
				},
				selectFirst:true,
				autoFocus: true
			})

			

		});
	</script>
    <div class="container">
       
        <form method="POST">
	        <div class="form-group mt-5" id="dynamic_form">
                <div class="row mb-5">
	                <div class="col-lg-4">
	                    <input type="text" name="code" id="code" placeholder="Enter Code" class="form-control" >
	                </div>
	                <div class="col-lg-3">
	                    <input type="text" class="" name="machine" id="machine" placeholder="Enter machine" >
	                </div>
					<div class="col-lg-2">
	                    <select class="form-control" name="quality" id="quality" >
							<option disabled="disabled" selected="selected" value="">Select quality</option>
							<option value="0">Original</option>
							<option value="1">kawe</option>
							<option value="2">Both</option>
						</select>
	                </div>
	                <div class="col-lg-2">
	                    <input type="text" class="form-control"  name="Qty" placeholder="Qty" id="qty" ></textarea>
	                </div>
					<div class="col-lg-1">
	                    <a href="javascript:void(0)" class="btn-block btn btn-primary" id="plus5">Add</a>
	                </div> 
	            </div>
				<div class="row">
	                <div class="col">
						<h3 class="text-center">Part list</h3>
	                </div>
	            </div>
            </div>
			
			<button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
</body>
</html>
