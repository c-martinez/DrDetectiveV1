<html>

<head>
		<title>Game Statistics</title>
		<script src="Chart.js/Chart.js"></script>
		<script src="amcharts_2.10.4/amcharts/amcharts.js" type="text/javascript"></script>
		<meta name = "viewport" content = "initial-scale = 1, user-scalable = no">
		<style>
			canvas{
			}
		</style>

		<?php include "game_stats.php"; ?>

</head>

<body>

	<h1>Game Statistics</h1>

	<?php 

	echo $nr_players_game;
	echo $nr_players_simple;
	echo $nr_players_total;
	echo $nr_players_either;

	echo "<br/>";
	
	echo "<script>";
	echo "var factorNoGame = new Array();\n";
	echo "var factorNoSimple = new Array();\n";
	echo "var factorNoCombined = new Array();\n";
	
	echo "var gameUsers = new Array();\n";
	echo "var simpleUsers = new Array();\n";
	
	echo "var factorTypesGame = new Array();\n";
	echo "var factorTypesSimple = new Array();\n";
	echo "var factorTypesCombined = new Array();\n";
	
	for ($i = 1; $i <= 20; $i++) {
		if (array_key_exists($i, $unique_factors_game))
			echo "factorNoGame.push(".count($unique_factors_game[$i]).");\n";
		else
			echo "factorNoGame.push(0);\n";
		
		if (array_key_exists($i, $unique_factors_simple))
			echo "factorNoSimple.push(".count($unique_factors_simple[$i]).");\n";
		else
			echo "factorNoSimple.push(0);\n";
		
		if (array_key_exists($i, $unique_factors_combined))
			echo "factorNoCombined.push(".count($unique_factors_combined[$i]).");\n";
		else
			echo "factorNoCombined.push(0);\n";
		
		if (array_key_exists($i, $game_users))
			echo "gameUsers.push(".$game_users[$i].");\n";
		else
			echo "gameUsers.push(0);\n";
		
		if (array_key_exists($i, $simple_users))
			echo "simpleUsers.push(".$simple_users[$i].");\n";
		else
			echo "simpleUsers.push(0);\n";
			
		if (array_key_exists($i, $factor_types_game_total))
			echo "factorTypesGame.push(".$factor_types_game_total[$i].");\n";
		else
			echo "factorTypesGame.push(0);\n";
			
		if (array_key_exists($i, $factor_types_simple_total))
			echo "factorTypesSimple.push(".$factor_types_simple_total[$i].");\n";
		else
			echo "factorTypesSimple.push(0);\n";
			
		if (array_key_exists($i, $factor_types_combined_total))
			echo "factorTypesCombined.push(".$factor_types_combined_total[$i].");\n";
		else
			echo "factorTypesCombined.push(0);\n";
	}
	echo "</script>";
	
	?>
	
	<table>
	<tr><td>
	Number of
	<br/>
	distinct
	<br/>
	users
	</td><td>
	<canvas id="canvas_users" height="450" width="600"></canvas>
	</td>
	<td>
	<span style="background-color: rgba(255,0,0,0.5)"> game version </span><br/><br/>
	<span style="background-color: rgba(151,187,205,0.5)"> simple version </span><br/><br/>
	</td>
	</tr>
	<tr><td></td>
	<td align="center">
	Paragraphs
	</td>
	<td></td></tr>
	</table>
	<script>
	
		var lb = new Array();
		for (var i = 1; i <= 20; i++) {
			lb.push(i.toString());
		}

		var barChartData = {
			labels : lb,
			datasets : [
				{
					fillColor : "rgba(255,0,0,0.5)",
					strokeColor : "rgba(220,0,0,1)",
					data : gameUsers
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : simpleUsers
				}
			]
			
		}

	var m1 = Math.max.apply(Math,gameUsers);
	var m2 = Math.max.apply(Math,simpleUsers);
	var myLine = new Chart(document.getElementById("canvas_users").getContext("2d")).Bar(barChartData,
		{scaleOverride: true, scaleSteps: Math.max(m1,m2), scaleStepWidth: 1, scaleStartValue: 0});
	
	</script>

	<br/><br/>
	<table>
	<tr><td>
	Number of
	<br/>
	distinct terms
	<br/>
	found
	</td><td>
	<canvas id="canvas" height="450" width="600"></canvas>
	</td>
	<td>
	<span style="background-color: rgba(255,0,0,0.5)"> game version </span><br/><br/>
	<span style="background-color: rgba(151,187,205,0.5)"> simple version </span><br/><br/>
	<span style="background-color: rgba(0,255,0,0.5)"> both versions </span>
	</td>
	</tr>
	<tr><td></td>
	<td align="center">
	Paragraphs
	</td>
	<td></td></tr>
	</table>
	<script>

		var barChartData = {
			labels : lb,
			datasets : [
				{
					fillColor : "rgba(255,0,0,0.5)",
					strokeColor : "rgba(220,0,0,1)",
					data : factorNoGame
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : factorNoSimple
				},
				{
					fillColor : "rgba(0,255,0,0.5)",
					strokeColor : "rgba(0,255,0,1)",
					data : factorNoCombined
				}
			]
			
		}

	var m1 = Math.max.apply(Math,factorNoGame);
	var m2 = Math.max.apply(Math,factorNoSimple);
	var m3 = Math.max.apply(Math,factorNoCombined);
	var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData,
		{scaleOverride: true, scaleSteps: Math.max(m1,Math.max(m2,m3))/2, scaleStepWidth: 2, scaleStartValue: 0});
	
	</script>

	<br/><br/>
	<table>
	<tr><td>
	Number of
	<br/>
	distinct
	<br/>
	factor
	types
	<br/>
	found
	</td><td>
	<canvas id="canvas_types" height="450" width="600"></canvas>
	</td>
	<td>
	<span style="background-color: rgba(255,0,0,0.5)"> game version </span><br/><br/>
	<span style="background-color: rgba(151,187,205,0.5)"> simple version </span><br/><br/>
	<span style="background-color: rgba(0,255,0,0.5)"> both versions </span>
	</td>
	</tr>
	<tr><td></td>
	<td align="center">
	Paragraphs
	</td>
	<td></td></tr>
	</table>
	<script>

		var barChartData = {
			labels : lb,
			datasets : [
				{
					fillColor : "rgba(255,0,0,0.5)",
					strokeColor : "rgba(220,0,0,1)",
					data : factorTypesGame
				},
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					data : factorTypesSimple
				},
				{
					fillColor : "rgba(0,255,0,0.5)",
					strokeColor : "rgba(0,255,0,1)",
					data : factorTypesCombined
				}
			]
			
		}

	var m1 = Math.max.apply(Math,factorTypesGame);
	var m2 = Math.max.apply(Math,factorTypesSimple);
	var m3 = Math.max.apply(Math,factorTypesCombined);
	var myLine = new Chart(document.getElementById("canvas_types").getContext("2d")).Bar(barChartData,
		{scaleOverride: true, scaleSteps: Math.max(m1,Math.max(m2,m3)), scaleStepWidth: 1, scaleStartValue: 0});
	
	</script>
	
	<script type="text/javascript">
  	var myJsArray = <?php echo json_encode($new_types_game); ?>;
	</script>
	
	
	<script type="text/javascript">
            var chart;
            
            var colorArr = ["#FFEBCD", "#0000FF", "#8A2BE2", "#A52A2A",
            	"#DEB887", "#5F9EA0", "#7FFF00", "#D2691E", "#FF7F50", "#6495ED",
            	"#FFF8DC", "#DC143C", "#00FFFF", "#00008B", "#008B8B", "#B8860B",
            	"#A9A9A9", "#006400", "#BDB76B", "#8B008B", "#556B2F", "#FF8C00",
            	"#9932CC", "#8B0000", "#E9967A", "#8FBC8F", "#483D8B", "#2F4F4F",
            	"#00CED1", "#9400D3", "#FF1493", "#00BFFF", "#696969", "#1E90FF",
            	"#B22222", "#FFFAF0", "#228B22", "#FF00FF", "#FFD700", "#DCDCDC"];
            
            var chartDataTerms = <?php echo json_encode($terms_json); ?>;
            var chartDataTypes = <?php echo json_encode($types_json); ?>;
            var chartDataWords = <?php echo json_encode($words_json); ?>;
            
            var chartData = chartDataTypes;
            var titleBar = "types";
            
            var maxIter = <?php echo $max_iterations; ?>;

            AmCharts.ready(function() {
							createStockChart();
							createChartLines();
					});
					
					function createStockChart() {
						// SERIAL CHART
							chart = new AmCharts.AmSerialChart();
							chart.dataProvider = chartData;
							chart.categoryField = "paragraph";
							chart.plotAreaBorderAlpha = 0.2;

							// AXES
							// category
							var categoryAxis = chart.categoryAxis;
							categoryAxis.gridAlpha = 0.1;
							categoryAxis.axisAlpha = 0;
							categoryAxis.gridPosition = "start";

							// value
							var valueAxis = new AmCharts.ValueAxis();
							valueAxis.stackType = "regular";
							valueAxis.gridAlpha = 0.1;
							valueAxis.axisAlpha = 0;
							chart.addValueAxis(valueAxis);

							// GRAPHS
							// first graph
							
							for (var i = 0; i <= maxIter; i++) {
								var pr = i + 1;
								var graph = new AmCharts.AmGraph();
								graph.title = pr + " game rounds";
								graph.labelText = "[[game_it_per_" + i +"]]%";
								graph.valueField = "game_it_" + i;
								graph.type = "column";
								graph.lineAlpha = 0;
								graph.fillAlphas = 1;
								graph.lineColor = colorArr[i];
								chart.addGraph(graph);
							}
							
							var valueAxis2 = new AmCharts.ValueAxis();
							valueAxis2.stackType = "regular";
							valueAxis2.gridAlpha = 0.1;
							valueAxis2.axisAlpha = 0;
							valueAxis2.position = "right";
							valueAxis2.synchronizationMultiplier = 1;
							valueAxis2.synchronizeWithAxis(valueAxis);
							chart.addValueAxis(valueAxis2)
							
							for (var i = 0; i <= maxIter; i++) {
								var pr = i + 1;
								var graph = new AmCharts.AmGraph();
								graph.title = pr + " simple rounds";
								graph.labelText = "[[simple_it_per_" + i +"]]%";
								graph.valueField = "simple_it_" + i;
								graph.stackable = true;
								graph.type = "column";
								graph.lineAlpha = 0;
								graph.fillAlphas = 1;
								graph.lineColor = colorArr[i + 1 + maxIter];
								graph.valueAxis = valueAxis2;
								chart.addGraph(graph);
							}

							// LEGEND                  
							var legend = new AmCharts.AmLegend();
							legend.borderAlpha = 0.2;
							legend.valueWidth = 0;
							legend.horizontalGap = 10;
							chart.addLegend(legend);
							
							chart.addTitle("Percentage of new " + titleBar +" found per paragraph, after each round of the game");

							// WRITE
							chart.write("chartdiv");
					}

					// this method sets chart 2D/3D
					function setDepth() {
							if (document.getElementById("rb1").checked) {
									chart.depth3D = 0;
									chart.angle = 0;
							} else {
									chart.depth3D = 25;
									chart.angle = 30;
							}
							chart.validateNow();
					}
					
					// set the data in the chart
					function setData() {
							if (document.getElementById("rb1").checked) {
									chartData = chartDataTypes;
									titleBar = "term types";
							} else if (document.getElementById("rb2").checked) {
									chartData = chartDataTerms;
									titleBar = "unique terms";
							}
							else {
									chartData = chartDataWords;
									titleBar = "words";
							}
							createStockChart();
					}
	</script>
	
	<div id="chartdiv" style="width: 1500px; height: 600px;"></div>
        <div style="margin-left:30px;">
	        <input type="radio" checked="true" name="group" id="rb1" onclick="setData()">term types
	        <input type="radio" name="group" id="rb2" onclick="setData()">unique terms
	        <input type="radio" name="group" id="rb3" onclick="setData()">words
		</div>
		
		<br/><br/><br/>
	<script type="text/javascript">
		var chartLinesData = <?php echo json_encode($lines_json); ?>;
		var topPars = <?php echo json_encode($top_pars); ?>;
	
		var chartLines;
		/*var chartLinesData = [{
				year: 2005,
				income: 23.5,
				expenses: 18.1},
		{
				year: 2006,
				income: 26.2,
				expenses: 22.8},
		{
				year: 2007,
				income: 30.1,
				expenses: 23.9},
		{
				year: 2008,
				income: 29.5,
				expenses: 25.1},
		{
				year: 2009,
				income: 24.6,
				expenses: 25.0}];*/


		var lineType = "type";

		function createChartLines() {
				// SERIAL chartLines  
				chartLines = new AmCharts.AmSerialChart();
				chartLines.pathToImages = "amcharts_2.10.4/images/";
				chartLines.autoMarginOffset = 0;
				chartLines.marginRight = 0;
				chartLines.dataProvider = chartLinesData;
				chartLines.categoryField = "round";
				chartLines.startDuration = 1;

				// AXES
				// category
				var categoryAxis = chartLines.categoryAxis;
				categoryAxis.gridPosition = "start";

				// line
				for (var i = 0; i < 3; i++) {
					var graph2 = new AmCharts.AmGraph();
					graph2.type = "line";
					graph2.valueField = "game_" + lineType + "_" + i;
					graph2.lineThickness = 2;
					graph2.bullet = "round";
					graph2.title = "par. " + topPars[i] + " in game";
					graph2.lineColor = colorArr[i+1];
					graph2.markerType = "line";
					chartLines.addGraph(graph2);
				}

				// LEGEND                
				var legend = new AmCharts.AmLegend();
				chartLines.addLegend(legend);
				
				for (var i = 0; i < 3; i++) {
					var graph2 = new AmCharts.AmGraph();
					graph2.type = "line";
					graph2.dashLength = 5;
					graph2.valueField = "simple_" + lineType + "_" + i;
					graph2.lineThickness = 2;
					graph2.bullet = "square";
					graph2.title = "par. " + topPars[i] + " in simple";
					graph2.lineColor = colorArr[i+1];
					graph2.markerType = "dashedLine";
					chartLines.addGraph(graph2);
				}
				chartLines.addTitle("Number of " + lineType +"s for the 3 most popular paragraphs,\nafter each round of the game");

				// WRITE
				chartLines.write("chartdivlines");
		}
					function setLine() {
							if (document.getElementById("lb1").checked) {
									lineType = "type";
							} else if (document.getElementById("lb2").checked) {
									lineType = "term";
							}
							else {
								lineType = "word";
							}
							createChartLines();
					}
	</script>
	<div id="chartdivlines" style="width: 1150px; height: 500px;"></div>
	<div style="margin-left:30px;">
	        <input type="radio" checked="true" name="group" id="lb1" onclick="setLine()">term types
	        <input type="radio" name="group" id="lb2" onclick="setLine()">unique terms
	        <input type="radio" name="group" id="lb3" onclick="setLine()">words
		</div>

</body>

</html>
