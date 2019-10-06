<!doctype html>
<html lang="zh">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width initial-scale=1, shrink-to-fit=yes">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <title>RPKI online validator using Routinator</title>
  <style>#wrapper {width: 1200px; margin-left: auto; margin-right: auto;} 
  </style>
</head>
<body>
<script>
var ajaxrun = false;
var ajaxRequest; 

try {
	ajaxRequest = new XMLHttpRequest();
}catch (e) {
	try {
		ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
	}catch (e) {
		try{
			ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}catch (e){
			alert("Your browser broke!");
		}
	}
}

function RPKIvali() {
	var asn = document.forms["myForm"]["asn"].value;
	var prefix = document.forms["myForm"]["prefix"].value;
	if ((asn.length == 0) || (prefix.length ==0)) { 
		document.getElementById('status').innerHTML = "";
		document.getElementById('detail').innerHTML = "";
		return;
	} 
	if(ajaxrun) 
		ajaxRequest.abort();
	ajaxrun = true;
	document.getElementById('status').innerHTML = "querying";
	document.getElementById('detail').innerHTML = "";
	ajaxRequest.onreadystatechange = function(){
		ajaxrun = false;
		if(ajaxRequest.readyState == 4){
			var obj;
			if(ajaxRequest.responseText == "Initial validation ongoing. Please wait.") {
				document.getElementById('status').innerHTML = "<font color=red>server starting, wait a while</font>";
				return;
			}
			try{
				obj = JSON.parse(ajaxRequest.responseText);
			}catch (e){
				document.getElementById('status').innerHTML = "<font color=red>Bad Request</font>";
				return;
			}
			var str;
			if(obj.validated_route.validity.state == "Valid")
				str = "<font color=green>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " Valid</font>";
			else if(obj.validated_route.validity.state == "Invalid")
				str = "<font color=red>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " Invalid</font>";
			else if(obj.validated_route.validity.state == "NotFound")
				str = "<font color=blue>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " NotFound</font>";
			else str = "<font color=red>ERROR</font>";
			document.getElementById('status').innerHTML= str;
			document.getElementById('detail').innerHTML= "<pre>" + ajaxRequest.responseText + "</pre>";;
		}
	}

	document.getElementById('status').innerHTML = "<p><font>Querying ...</font>";
	var queryString =  "asn=" + asn + "&prefix=" + prefix; 
	ajaxRequest.open("GET", "validity.php?" + queryString, true);
	ajaxRequest.send(null); 
}
</script>
<div id="wrapper">
  <div class="container-fluid">
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
      <h2 class="display-6">RPKI online Validator using <a href=https://www.nlnetlabs.nl/projects/rpki/routinator/ target=_blank>Routinator</a>
(Routinator <a href=routinator.php?url=/version&addpre=1>version</a>/<a href=routinator.php?url=/status&addpre=1>status</a>):</h2>

<p class="lead"><a href=http://github.com/bg6cq/rpki-validator>http://github.com/bg6cq/rpki-validator</a></p>

<p class="lead">Please input ASN & prefix:</p>
<form name=myForm> 
<table>
<tr><td align=right>ASN:</td><td> <input name=asn type="text" value=4134 onkeyup="RPKIvali()"></td></tr>
<tr><td align=right>Prefix:</td><td><input name=prefix type="text" value=202.141.160.0/20 onkeyup="RPKIvali()"></td></tr>
</table>
</form>
      </div>
    </div>

    <div class="alert alert-info" role="alert">
  Validate result: <span id="status"></span>
    </div>

    <div class="jumbotron jumbotron-fluid">
      <div class="container">Detailed output of Routinator:
      </div>
      <div class="container"><span id="detail"></span>
      </div>
    </div>
  </div>
</div>
<script>
RPKIvali();
</script>
</body>
</html>
