var myApp = angular.module('myApp', []);

myApp.controller('PollController', function PollController($scope,$http,$interval) {
	$scope.description = "";
	$scope.pollStarted = false;
	$scope.pollEnded = false;
	$scope.pollId = 0;
	var stop;
	$scope.getOpenPoll = function() {
		$http.get('http://pick-a-side-poll.000webhostapp.com/api/poll/get_open_poll.php')
				.then(function (response) {
					// check if there is an open poll, if so load that
					if(!response.data.stop_date) {
						$scope.pollName = "Running poll: " + response.data.description;
						$scope.pollStarted = true;
						$scope.pollId = response.data.id;
						
						// also preset timer
						$scope.stringMins = "00";
						$scope.stringSecs = "00";
						$scope.seconds = 0;
						$scope.minutes = 0;
						stop = $interval( function(){ $scope.incrementTimer(); }, 1000);
					}
				})
				.catch(function (response) {
					$scope.ResponseDetails = "Data: " + response.data +
						"<hr />status: " + response.status +
						"<hr />headers: " + response.header +
						"<hr />config: " + response.config; 
				});	
	};
	$scope.getOpenPoll();
	$scope.showStartButton = function() {
		if($scope.pollEnded) {
			return false;
		}
		
		if($scope.pollStarted) {
			return false;
		}
		
		return true;
	};
	$scope.showStopButton = function() {
		if($scope.pollEnded) {
			return false;
		}
		
		if(!$scope.pollStarted) {
			return false;
		}
		
		return true;
	};
	$scope.showNextButton = function() {
		if(!$scope.pollEnded) {
			return false;
		}
		
		if($scope.pollStarted) {
			return false;
		}
		
		return true;
	};
	$scope.incrementTimer = function() {
		if($scope.seconds == 59) {
			$scope.minutes += 1;
			$scope.seconds = 0;
		}
		else {
			$scope.seconds += 1;
		}
		
		$scope.stringMins = "00" + $scope.minutes.toString();	
		$scope.stringMins = $scope.stringMins.slice(-2);
		
		$scope.stringSecs = "00" + $scope.seconds.toString();	
		$scope.stringSecs = $scope.stringSecs.slice(-2);
		
	};
	$scope.createPoll = function() {
	
		if(!$scope.description) {
			$scope.description = "Quick poll# " + Date.now().toString();
		}
		$scope.stringMins = "00";
		$scope.stringSecs = "00";
		$scope.seconds = 0;
		$scope.minutes = 0;
		stop = $interval( function(){ $scope.incrementTimer(); }, 1000);
		
		var data = {};
		data.description = $scope.description;
		
		var config = {
                headers : {
                    'Content-Type': 'application/json;'
                }
            }
		
		$http.post('http://pick-a-side-poll.000webhostapp.com/api/poll/create.php', JSON.stringify(data), config)
            .then(function (response) {
				$scope.pollName = "Running poll: " + $scope.description;
				$scope.pollStarted = true;
				$scope.pollId = response.data.id;
            })
            .catch(function (response) {
                $scope.ResponseDetails = "Data: " + response.data +
                    "<hr />status: " + response.status +
                    "<hr />headers: " + response.header +
                    "<hr />config: " + response.config;
            });		
		
	};
	
	
	$scope.stopPoll = function() {
		 if (angular.isDefined(stop)) {
            $interval.cancel(stop);
            stop = undefined;
          }
		var data = {};
		data.id = $scope.pollId;
		
		var config = {
                headers : {
                    'Content-Type': 'application/json;'
                }
            }
		
		$http.post('http://pick-a-side-poll.000webhostapp.com/api/poll/stop_poll.php', JSON.stringify(data), config)
            .then(function (response) {
				$scope.pollStarted = false;
				$scope.pollEnded = true;
				
				$http.get('http://pick-a-side-poll.000webhostapp.com/api/poll/read_one.php?id=' + $scope.pollId)
				.then(function (response) {
					$scope.pollStarted = false;
					$scope.pollEnded = true;
					$scope.pollHeader = "Voting closed for " + response.data.description;
					var ctx = document.getElementById("myChart");
						var myChart = new Chart(ctx, {
							type: 'pie',
							data: {
								labels: ["Debater A", "Debater B"],
								datasets: [{
									label: '# of Votes',
									data: [response.data.total_a, response.data.total_b],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)'
									  
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)'
									],
									borderWidth: 1
								}]
							}
						});
					
				})
				.catch(function (response) {
					$scope.ResponseDetails = "Data: " + response.data +
						"<hr />status: " + response.status +
						"<hr />headers: " + response.header +
						"<hr />config: " + response.config;
				});	
				
            })
            .catch(function (response) {
                $scope.ResponseDetails = "Data: " + response.data +
                    "<hr />status: " + response.status +
                    "<hr />headers: " + response.header +
                    "<hr />config: " + response.config;
            });	
			
		// todo: get results and display
		// todo: hide all screen items
		// todo: show next poll button
	};
	
	$scope.newPoll = function() {
		location.reload(true);
	}
	
});

myApp.controller('VoteController', function VoteController($scope,$http,$interval) {
	// todo: get active poll
	// todo: count vote on click and disable vote buttons
	// todo: disable voting after click
	// todo: keep polling active poll
	
	
	$scope.pollClosed = false;
	$scope.votingDisabled = false;
	$scope.loadResults = function() {
		$http.get('http://pick-a-side-poll.000webhostapp.com/api/poll/get_open_poll.php')
				.then(function (response) {
					if(!$scope.pollHeader)
					{
						var promptText = "Cast your vote for ";
						if($scope.votingDisabled) {
							promptText = "You have already voted on poll "
						}
						$scope.pollHeader = promptText + response.data.description;
						$scope.pollId = response.data.id;
					}
					if(response.data.stop_date) {
						// clear timer, display results, show refresh button
						$scope.pollClosed = true;
						$interval.cancel(stop);
						$scope.pollHeader = "Voting closed for " + response.data.description;
						
						var ctx = document.getElementById("myChart");
						var myChart = new Chart(ctx, {
							type: 'pie',
							data: {
								labels: ["Debater A", "Debater B"],
								datasets: [{
									label: '# of Votes',
									data: [response.data.total_a, response.data.total_b],
									backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)'
									  
									],
									borderColor: [
										'rgba(255,99,132,1)',
										'rgba(54, 162, 235, 1)'
									],
									borderWidth: 1
								}]
							}
						});
					}
				})
				.catch(function (response) {
					$scope.ResponseDetails = "Data: " + response.data +
						"<hr />status: " + response.status +
						"<hr />headers: " + response.header +
						"<hr />config: " + response.config; 
				});	
	}
	
	$scope.canVote = function() {
		$http.get('http://pick-a-side-poll.000webhostapp.com/api/poll/get_open_poll.php')
				.then(function (response) {
					$scope.pollHeader = "Cast your vote for " + response.data.description;
					$scope.pollId = response.data.id;
					var data = {};
					data.poll_id = $scope.pollId;
					data.ip = $scope.ip;
					var headerText = response.data.description;
					var config = {
							headers : {
								'Content-Type': 'application/json;'
							}
						}
					$http.post('http://pick-a-side-poll.000webhostapp.com/api/vote/can_vote.php', JSON.stringify(data), config)
					.then(function(response) {
						//alert(response.data.can_vote);
						if(response.data.can_vote == "false") {
							$scope.pollHeader = "You have already voted on poll " + headerText;
							$scope.votingDisabled = true;
						}
						
					})
					.catch(function(response) {
						$scope.ResponseDetails = "Data: " + response.data +
						"<hr />status: " + response.status +
						"<hr />headers: " + response.header +
						"<hr />config: " + response.config; 
					}); 
				})
				.catch(function (response) {
					$scope.ResponseDetails = "Data: " + response.data +
						"<hr />status: " + response.status +
						"<hr />headers: " + response.header +
						"<hr />config: " + response.config; 
				});	
	};
	
	$scope.getIp = function() {
		$http.get('https://api.ipify.org?format=json')
			.then(function(response) {
				$scope.ip = response.data.ip;
				$scope.canVote();
			})
			.catch(function(response) {
				alert("Could not get ip address");
			});
	};
	$scope.getIp();
	
	var stop;
	stop = $interval( function(){ $scope.loadResults(); }, 1000);
	
	$scope.voteA = function() {
		var data = {};
		data.poll_id = $scope.pollId;
		data.debater_a = 1;
		data.debater_b = 0;
		data.ip = $scope.ip;
		
		var config = {
                headers : {
                    'Content-Type': 'application/json;'
                }
            }
			
		$http.post('http://pick-a-side-poll.000webhostapp.com/api/vote/cast_vote.php', JSON.stringify(data), config)
            .then(function (response) {
				alert(response.data.message);
				$scope.votingDisabled = true;
            })
            .catch(function (response) {
                $scope.ResponseDetails = "Data: " + response.data +
                    "<hr />status: " + response.status +
                    "<hr />headers: " + response.header +
                    "<hr />config: " + response.config;
            });	
	}
	
	$scope.voteB = function() {
		var data = {};
		data.poll_id = $scope.pollId;
		data.debater_a = 0;
		data.debater_b = 1;
		data.ip = $scope.ip;
		
		var config = {
                headers : {
                    'Content-Type': 'application/json;'
                }
            }
			
		$http.post('http://pick-a-side-poll.000webhostapp.com/api/vote/cast_vote.php', JSON.stringify(data), config)
            .then(function (response) {
				alert(response.data.message);
				$scope.votingDisabled = true;
            })
            .catch(function (response) {
                $scope.ResponseDetails = "Data: " + response.data +
                    "<hr />status: " + response.status +
                    "<hr />headers: " + response.header +
                    "<hr />config: " + response.config;
            });	
	}
	
	$scope.nextPoll = function() {
		location.reload(true);
	};
	
});