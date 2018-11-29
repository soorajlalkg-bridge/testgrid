@extends('user.layout.app')

@section('content')
<div class="row padding-15">
  <div class="col-sm-12">
<script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>

<script>
    function updateFilter()
    {
//	limit1chk, missinginputchk, choffchk;
	
	$(".innertable").parents("table").show();
	$(".limit1").show();
	$(".limit2").show();
	$(".limit3").show();
	$(".missinginput").show();
	$(".isdown").show();
	if($("#limit1chk").is(':checked'))
		$(".limit1").hide();
	if($("#limit2chk").is(':checked'))
		$(".limit2").hide();
	if($("#limit3chk").is(':checked'))
		$(".limit3").hide();
	if($("#missinginputchk").is(':checked'))
		$(".missinginput").hide();
	if($("#choffchk").is(':checked'))
		$(".isdown").hide();
	$(".innertable").each(function(){
	  var curTable = $(this);
	  var count = $(this).find("tr:visible").length;
	  if (count < 2)
		$(this).parents("table").hide();
	});
    }
    var jsonUrl = '<?php echo $skimmerUrl;?>';//"http://139.162.157.92/igigridview/gridview/storage/app/public/skimmer";
    //<?=$_SERVER['SERVER_ADDR'];?>:62621/data";

    function updateJson()
    {
	var val1, val2, val3;
	val1 = $("#limit1").val();
	val2 = $("#limit2").val();
	val3 = $("#limit3").val();
	if(isNaN(val1))
		val1 = 12;
	if(isNaN(val2))
		val2 = 24;
	if(isNaN(val3))
		val3 = 7;
        $.getJSON(jsonUrl, 
        { 
                system: 0,
	        ts: new Date().getTime()
        }, 
        function(data) 
        {
	        var txt ="";
	        var txt3 ="";
		var ip ="#";
		var lessthan12 = 0;
		var lessthan24 = 0;
		var lessthanwk = 0;
		var choff= 0;
		var missinginput= 0;
		if(data == null)
		{
			alert("Polling data is not yet ready, will retry in one second");
			return;
		}
		for (i in data["Systems"]){
		txt3 += txt;
        	for (x in data["Systems"][i]) {
		txt = "";
	        if(x != "Channels")	
            	txt += "<td>" + x + "</td>";
        	}
 		txt += "</tr><thead class='table-head'><tr role='row'>"
		for (x in data["Systems"][i]) {
	        if(x == "IP")
			ip = data["Systems"][i][x];
	        if(x != "Channels")	
            	txt += "<th><a target='_blank' href='http://" + ip + "/control.php'>" + data["Systems"][i][x] + "</a></th>";
        	}
        	txt += "</tr></thead><tr><td colspan=5>"        
		if(data["Systems"][i]["Channels"][0])
		{
 		txt += "<table class='table table-striped table-bordered text-center configure dataTable no-footer' width='100%' id='test2' border='1' style='border: 1px solid black;'>\n"
            	txt += "<thead class='table-head'><tr role='row'><th>Channel Index</th>";
            		for (y in data["Systems"][i]["Channels"][0]) {
            			txt += "<th>" + y + "</th>";
        		}
		txt +="</tr></thead>";
		var channelHasError = 0;
        	for (x in data["Systems"][i]["Channels"]) {
			var hasError = 0;
			var tmpTxt = "";
			var errorclass = "";
            		for (y in data["Systems"][i]["Channels"][x]) {
				var color="";
				var isup = data["Systems"][i]["Channels"][x]["AppState"];
				var input = data["Systems"][i]["Channels"][x]["InputSignal"];
				if(y=="InputSignal")
				{
					if(isup=="0")
						color="red";
					else
						color="#66ff33";
				}
				if(y=="AppState")
				{
					if(isup=="0" && input=="0")
						color="red";
					else
						color="#66ff33";
				}
 
 				if(y == "Uptime")
				{
					var uptime = data["Systems"][i]["Channels"][x][y];
					var d, h;
					d = uptime.substr(0,uptime.indexOf("d"));
					if(d == "")
					    d = -1;
					else
					    d = parseInt(d);
					if(uptime.indexOf("h") == -1)
					    h = 0;
					else
					    h = parseInt(uptime.substr(uptime.indexOf("d")+1,uptime.indexOf("h")));
					if(isup=="0"){
						choff++;
						errorclass += 'isdown ';
					}else if(input=="0"){
						missinginput++;
						errorclass += 'missinginput ';
					}else if(d < 1 && h < parseInt(val1))
					{
						lessthan12++;
						color="red";
						errorclass += 'limit1 ';
					}
					else if (d < 1 && h < parseInt(val2))
					{
						lessthan24++;
						color="orange";
						errorclass += 'limit2 ';
					}
					else if (d < val3)
					{
						lessthanwk++;
						color="yellow";
						errorclass += 'limit3 ';
					}
				}
				if(color!="" && color !="#66ff33")
					hasError++;
           			tmpTxt += "<td width='14%' bgcolor='"+color+"'>" + data["Systems"][i]["Channels"][x][y] + "</td>";
        		}
			if(hasError > 0)
            			txt += "<tr class='hasError " + errorclass + "'><td>" + x + "</td>";
            		else
				txt += "<tr class='noError'><td>" + x + "</td>";
			txt += tmpTxt;
			channelHasError = hasError;
        	}
        	txt += "</table></td></tr>";
		} else {
        	txt += "No channels found</td></tr>";
		}

		ip = "#";
        	txt += "</table>";
		if(hasError > 0)
  			txt = "<table class='hasError table table-striped table-bordered text-center configure dataTable no-footer' id='skimmerdata' border='1' style='margin-bottom:10px; border: 1px solid black;'>\n<tr>"+txt;
		else
  			txt = "<table class='blueTable noError table table-striped table-bordered text-center configure dataTable no-footer' id='skimmerdata' border='1' style='margin-bottom:10px; border: 1px solid black;'>\n<tr>"+txt;
		}
		txt3 += txt;
		var txt2="";
        	txt2 += "<table class='blueTable table table-striped table-bordered text-center configure dataTable no-footer' border=1><thead class='table-head'><tr role='row'><th>Channels Running Less than <input style='width:30px' type='number' value="+val1+" id='limit1' onChange='updateJson()'> hours</th><th>Channels Running Less than <input style='width:30px' type='number' value="+val2+" id='limit2' onChange='updateJson()'> hours</th><th>Channels Running Less than <input style='width:30px' type='number' value="+val3+" id='limit3' onChange='updateJson()'> days</th><th>Missing Input (Ch is on)</th><th>Off Channels</th></tr></thead>";
		txt2 += "<tr><td bgcolor='red'>"+lessthan12+"</td>";
		txt2 += "<td bgcolor='orange'>"+lessthan24+"</td>";
		txt2 += "<td bgcolor='yellow'>"+lessthanwk+"</td>";
		txt2 += "<td bgcolor='red'>"+missinginput+"</td>";
		txt2 += "<td bgcolor='red'>"+choff+"</td>";
        	txt2 += "</tr>";
		txt2 += "<tr><td>Hide: <input type='checkbox' id='limit1chk' onChange='updateFilter()'></td>";
		txt2 += "<td>Hide: <input type='checkbox' id='limit2chk' onChange='updateFilter()'></td>";
		txt2 += "<td>Hide: <input type='checkbox' id='limit3chk' onChange='updateFilter()'></td>";
		txt2 += "<td>Hide: <input type='checkbox' id='missinginputchk' onChange='updateFilter()'></td>";
		txt2 += "<td>Hide: <input type='checkbox' id='choffchk' onChange='updateFilter()'></td>";
        	txt2 += "</tr></table>";
		var cmd = "$(&quot;.noError&quot;).toggle()";
		//txt2 += "Hide channels without errors <input type='checkbox' onclick='"+cmd+"'>"
		txt2 += "<br><br>"
        	document.getElementById("status").innerHTML = txt2+txt3;
        });
    }
	updateJson();
</script>
    <div id="status">
    </div>
  </div>
</div>

@endsection
