<html>
<head>
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
		return;
	} 
	if(ajaxrun) 
		ajaxRequest.abort();
	ajaxrun = true;
	ajaxRequest.onreadystatechange = function(){
		ajaxrun = false;
		if(ajaxRequest.readyState == 4){
			var obj;
			if(ajaxRequest.responseText == "Initial validation ongoing. Please wait.") {
				document.getElementById('Status').innerHTML = "<p><font color=red>server starting, wait a while</font>";
				return;
			}
			try{
				obj = JSON.parse(ajaxRequest.responseText);
			}catch (e){
				document.getElementById('Status').innerHTML = "<p><font color=red>Bad Request</font>";
				return;
			}
			var ajaxDisplay = document.getElementById('Status');
			var str;
			if(obj.validated_route.validity.state == "Valid")
				str = "<p><font color=green>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " Valid</font><p>";
			else if(obj.validated_route.validity.state == "Invalid")
				str = "<p><font color=red>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " Invalid</font><p>";
			else if(obj.validated_route.validity.state == "NotFound")
				str = "<p><font color=blue>" + obj.validated_route.route.prefix + " " + obj.validated_route.route.origin_asn + " NotFound</font><p>";
			else str = "<p><font color=red>ERROR</font><p>";
			ajaxDisplay.innerHTML = str + "<pre>" + ajaxRequest.responseText + "</pre>";
		}
	}

	document.getElementById('Status').innerHTML = "<p><font>Querying ...</font>";
	var queryString =  "asn=" + asn + "&prefix=" + prefix; 
	ajaxRequest.open("GET", "validity.php?" + queryString, true);
	ajaxRequest.send(null); 
}
</script>
</head>
<body>
<p>RPKI online validator using <a href=https://www.nlnetlabs.nl/projects/rpki/routinator/ target=_blank>Routinator</a> 
(Routinator <a href=routinator.php?url=/version&addpre=1>version</a>/<a href=routinator.php?url=/status&addpre=1>status</a>):<p>

<p><b>Please input ASN & prefix:</b></p>
<form name=myForm> 
<table>
<tr><td align=right>ASN:</td><td> <input name=asn type="text" value=4134 onkeyup="RPKIvali()"></td></tr>
<tr><td align=right>Prefix:</td><td><input name=prefix type="text" value=202.141.160.0/20 onkeyup="RPKIvali()"></td></tr>
</table>
</form>

<p>Routinator output: <span id="Status">
</span></p>
<script>
RPKIvali();
</script>
<p>

<a href=http://github.com/bg6cq/rpki-validator>http://github.com/bg6cq/rpki-validator</a>
</body>
</html>
