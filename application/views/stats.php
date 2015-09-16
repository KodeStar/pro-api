<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100,700' rel='stylesheet' type='text/css'>
	<title>Fanart.tv PRO-API</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font-family: 'Lato', sans-serif;
		font-size: 13px;
		font-weight: 400;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #57A2B1;
		background-color: transparent;
		font-size: 120px;
		font-weight: 300;
		margin: 0 0 0px 0;
		padding: 14px 15px 0px 15px;
		text-align: center;
		text-transform: uppercase;
		display: inline-block;
		border-bottom: 1px solid #E0E0E0;
		position: relative;
	}
	h1 span {
		font-size:50px;
	}
	h1::after {
		content: " ";
		position: absolute;
		border-bottom: 1px solid #FFF;
    	bottom: -2px;
    	width: 100%;
    	left: 0;
	}
	h2 {
		color: #57A2B1;
		background-color: transparent;
		border-bottom: 1px solid #E0E0E0;
		font-size: 40px;
		font-weight: 300;
		margin: 10px 0 14px 0;
		padding:0px 15px 30px 15px;
		text-align: center;
		text-transform: uppercase;
		position: relative;
	}
	h2::after {
		content: " ";
		position: absolute;
		border-bottom: 1px solid #FFF;
    	bottom: -2px;
    	width: 100%;
    	left: 0;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 30px 0 30px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #E0E0E0;
		line-height: 50px;
		padding: 0 30px 0 30px;
		margin: 20px 0 0 0;
		position: relative;
	}
	p.footer::after {
		content: " ";
		position: absolute;
		border-bottom: 1px solid #FFF;
    	top: 0px;
    	width: 100%;
    	left: 0;
	}

	.stats {
		display: inline-block;
		width: 50%;
		vertical-align: top;
		text-align: left;
	}

	section {
		display: block;
		border-top: 1px solid #E0E0E0;
		margin: 20px 0 0 0;
		padding: 0 30px;
	}
	section::after {
		content: " ";
		position: absolute;
		border-bottom: 1px solid #FFF;
    	top: 0px;
    	width: 100%;
    	left: 0;
	}
	section .col4 {
		display: inline-block;
		vertical-align: top;
		width: 25%;
		text-align: left;
	}

	#container {
		margin: 10px;
		background: #F7F7F7;
		/*border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;*/
		border: 1px solid #E6E6E6;
    	box-shadow: 0 0 8px #E2E2E2;
    	text-align: center;
	}
	table {
		width: 100%;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Fanart<span>.</span>tv</h1>
	<h2>Pro API</h2>

	<div id="body">
		<div class="stats">
			<h3>System Stats</h3>
			<table>
				<tr>
					<td>Uptime</td>
					<td><?php echo $uptime;?></td>
				</tr>
				<tr>
					<td>Load</td>
					<td><?php echo $load;?></td>
				</tr>
				<tr>
					<td>Memory</td>
					<td><?php echo $memory;?></td>
				</tr>
				<tr>
					<td>Connected</td>
					<td><?php echo $clients;?> Client<?php echo ($clients === 1) ? '' : 's';?></td>
				</tr>
				<tr>
					<td>Database size</td>
					<td><?php echo $database; ?></td>
				</tr>
			</table>
		</div><div class="stats">
			<h3>Network Stats</h3>
			<table>
				<tr>
					<td>This month</td>
					<td>891.61 GiB</td>
				</tr>
				<tr>
					<td>Last Month</td>
					<td>891.61 GiB</td>
				</tr>
				<tr>
					<td>Total</td>
					<td>1891.61 GiB</td>
				</tr>
			</table>
		</div>
	</div>
	<section>
	<div class="col4">
		<h3>Movies</h3>
		<p><?php echo $movies;?></p>
	</div><div class="col4">
		<h3>TV</h3>
		<p><?php echo $tv;?></p>
	</div><div class="col4">
		<h3>Music</h3>
		<p><?php echo $music;?></p>
	</div><div class="col4">
		<h3>Labels</h3>
		<p><?php echo $labels;?></p>
	</div>
	</section>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo 'API Version <strong>3.0</strong>' ?></p>
</div>

</body>
</html>