<?php
	session_start();
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		//echo "Welcome to the member's area, " . $_SESSION['username'] . "!";
	} else {
		//echo "Please log in first to see this page.";
		header("Location: admin.php");
	}

?>
<html ng-app="myApp">
<head>
<title>
</title>
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="js/angular.min.js"></script>
<script src="js/app.js"></script>
<script src="js/app.js"></script>
<script src="js/Chart.bundle.min.js"></script>
</head>
<body ng-controller="PollController">
	<div style="width:100%;">
		<img src="images/logo.png" style="display:block; margin: 0 auto; width:18%" />
	</div>
	<div style="width:100%;">

		<div style="margin: 16px auto; width:18%; text-align:center;">
			<p ng-show="showStartButton()">Enter a poll name or click to just start!</p>
			<p ng-show="showStartButton()">
				<span style="font-weight:bold">Poll name:</span>&nbsp;&nbsp;<input type="text" name="poll_name" id="poll_name" ng-model="description" />
			</p>
			<h3 ng-show="showStopButton()">{{pollName}}</h3>
			<p ng-show="showStopButton()" style="font-size:16px">
				{{stringMins}}:{{stringSecs}}
			</p>
			<p ng-if="showNextButton()">
				<h4 style="margin-bottom:16px">{{pollHeader}}</h4>
				<canvas id="myChart" width="50" height="50"></canvas>
			</p>
			<h3 ng-show="showNextButton()">{{pollResultsText}}</h3>
			<button type="button" class="btn btn-success" ng-click="createPoll()" ng-show="showStartButton()">Click to start poll</button>
			<button type="button" class="btn btn-danger" ng-click="stopPoll()" ng-show="showStopButton()">Click to stop poll</button>
			<button type="button" class="btn btn-info" ng-click="newPoll()" ng-show="showNextButton()">Click to continue</button>
		</div>
	</div>

</body>
</html>