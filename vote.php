<html ng-app="myApp">
<head>
<title>
</title>
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="js/angular.min.js"></script>
<script src="js/app.js"></script>
<script src="js/Chart.bundle.min.js"></script>
</head>
<body ng-controller="VoteController">
	<div style="width:100%;">
		<img src="images/logo.png" style="display:block; margin: 0 auto; width:18%" />
	</div>
	<div style="width:100%;">
	
		<div style="margin: 16px auto; width:20%; text-align:center;">
			<h4 style="margin-bottom:16px">{{pollHeader}}</h4>
			<p ng-show="pollClosed">
				<canvas id="myChart" width="70" height="70"></canvas>
			</p>
			<button type="button" class="btn btn-danger" ng-hide="pollClosed" ng-click="voteA()" ng-disabled="votingDisabled">Vote Debater A</button>&nbsp;&nbsp;&nbsp;
			<button type="button" class="btn btn-info" ng-hide="pollClosed" ng-click="voteB()" ng-disabled="votingDisabled">Vote Debater B</button>
			<button type="button" class="btn btn-success" ng-show="pollClosed" ng-click="nextPoll()">Next Poll</button>

		</div>
	</div>

</body>
</html>