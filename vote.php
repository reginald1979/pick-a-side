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
		<img src="images/logo.png" style="display:block; margin: 0 auto; width:50%" />
	</div>
	<div style="width:100%;">
	
		<div style="margin: 16px auto; width:50%; text-align:center;">
			<h3 style="margin-bottom:16px">{{pollHeader}}</h4>
			<p ng-show="pollClosed">
				<canvas id="myChart" width="70" height="70"></canvas>
			</p>
			<button type="button" style="font-size:30px;" class="btn btn-danger" ng-hide="pollClosed" ng-click="voteA()" ng-disabled="votingDisabled">VOTE (A)</button>&nbsp;&nbsp;&nbsp;
			<button type="button" style="font-size:30px;" class="btn btn-info" ng-hide="pollClosed" ng-click="voteB()" ng-disabled="votingDisabled">VOTE (B)</button>
			<button type="button" style="font-size:30px;" class="btn btn-success" ng-show="pollClosed" ng-click="nextPoll()">Next Poll</button>

		</div>
	</div>

</body>
</html>