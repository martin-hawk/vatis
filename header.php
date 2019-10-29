<html>
<head>
<title>VATI IS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" />
<link href="./js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/customCSS.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/calendar/calendar.js"></script>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Tooltip -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$(document).ready(function(){
	
	$('#newProduct').autocomplete({
	source: function( request, response ) {
  		$.ajax({
			url: 'phpGetProduct.php',
			type: 'post',
  			dataType: "json",
			data: {
			   name_startsWith: request.term
			   //type: 'product',
			   //row_num : 1
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[0] + ' | ' + code[1],
						value: code[0],
						data : item
					}
				}));
			}
  		});
  	},
  	autoFocus: true,	      	
  	minLength: 0,
  	select: function( event, ui ) {
		var prods = ui.item.data.split("|");						
		$('#newQuantity').val(prods[2]);
		$('#newUOM').val(prods[3]);
		$('#newPrice').val(prods[4]);
	}		      	
});

$('#customer_selection').autocomplete({
	source: function( request, response ) {
  		$.ajax({
			url: 'phpGetCustomer.php',
			type: 'post',
  			dataType: "json",
			data: {
			   name_startsWith: request.term
			},
			 success: function( data ) {
				 response( $.map( data, function( item ) {
				 	var code = item.split("|");
					return {
						label: code[1] + ' | ' + code[2],
						value: code[0],
						data : item
					}
				}));
			}
  		});
  	},
  	autoFocus: true,	      	
  	minLength: 0
});	
});

	function calculateRateF(rate) {
        var currency1 = document.getElementById("currency1").value;	
		var result = document.getElementById("currency2");
        var myResult = currency1 * rate;
		result.value = Math.round(myResult * 100) / 100;
	}
    function calculateRateB(rate) {
		var result = document.getElementById("currency1");	
		var currency2 = document.getElementById("currency2").value;
		var myResult = currency2 / rate;
		result.value = Math.round(myResult * 100) / 100;
	}

  $( function() {
    $( document ).tooltip();
  } );
</script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" style="background-color: DimGray;">
  <table align="center" width="100%">
    <tr>
      <td width="33%"></td>
      <td style="text-align:center" width="34%"><div style="color: White;"><i class="fa fa-eur fa-3x" aria-hidden="true"></i></div></td>
      <td width="33%">
      <?php if(isset($_SESSION['user_company'])) {
		  echo '<div align="right" style="color: White;"><a style="color: White; margin:10px;" href="mySettings.php"><i class="fa fa-user-circle-o" aria-hidden="true"></i> ' . $_SESSION['f_name'] . ' ' . $_SESSION['l_name'] . '</a></div>';
	  }
	?>
	</td>
    </tr>
    <tr>
      <td></td>
      <td style="text-align:center"><div style="color: White;">VAT Invoice System</div></td>
      <td><div align="right">
      <?php if(isset($_SESSION['user_company'])) {
      echo '<a style="color: White;" href="main.php" title="Pagrindinis puslapis"><i class="fa fa-home fa-2x" aria-hidden="true"></i></a> <a title="Įmonės duomenys" style="color: White; margin:10px;" href="company.php"><i class="fa fa-building fa-2x" aria-hidden="true"></i></a> <a style="color: White; margin:10px;" href="help/help.php" title="Pagalba" target="_blank"><i class="fa fa-question-circle fa-2x" aria-hidden="true"></i></a> <a style="color: White; margin:10px;" title="Atsijungti" href="logout.php"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></a>';
	  }
	  ?></div>
	  </td>
    </tr>
  </table>
</nav>
