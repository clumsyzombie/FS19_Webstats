{config_load file='../style.cfg'}
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Farming Simulator 19 Web Stats">
<meta name="author" content="John Hawk">
<link rel="icon" href="{#IMAGES#}/favicon.ico">
<title>{$map.Short} {$map.Version} Web Stats</title>
<link rel="stylesheet" type="text/css" href="{#CSS#}/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{#CSS#}/theme.min.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{#CSS#}/customstyle.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{#CSS#}/datatables.min.css" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<script type="text/javascript" src="{#SCRIPTS#}/jquery.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/bootstrap.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/datatables.min.js"></script>
</head>
<body>
	<header>
		<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark justify-content-center">
			<a class="navbar-brand" href="#">{$map.Short} {$map.Version} Web Stats</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarNav">
				<ul class="navbar-nav mx-auto text-center">
					{foreach $navItems as $link => $navItem}{if $navItem.showInNav} {if $navItem.active}{$class="nav-item active"}{else}{$class="nav-item"}{/if}
					<li class="{$class}"><a class="nav-link" href="index.php?page={$link}">{$navItem.text}</a></li>{/if} {/foreach}
				</ul>
				<span class="navbar-text  bg-secondary text-white px-3 text-right font-weight-bold">##DAY## {$currentDay}, {$dayTime}{if $money !== false} | {$money|number_format:0:",":"."}{/if}</span>
			</div>
		</nav>
	</header>
	<div class="container" style="padding-left: 10px; padding-right: 10px">
		{if $serverOnline}{assign var="fullPathToTemplate" value="./styles/$style/templates/$page.tpl"} {if file_exists($fullPathToTemplate)} {include file="$page.tpl"} {else}
		<div class="container theme-showcase" role="main">
			<div class="jumbotron">
				<h1>##TPL_ERROR_1##</h1>
				<p class="lead">##TPL_ERROR_2## {$fullPathToTemplate} ##TPL_ERROR_3##</p>
			</div>
		</div>
		{/if}{else}
		<div class="container theme-showcase" role="main">
			<div class="jumbotron">
				<h1>##CON_ERROR_1##</h1>
				<p class="lead">##CON_ERROR_2##</p>
			</div>
		</div>
		{/if}
	</div>
	{if !$hideFooter}
	<div class="navbar navbar-default navbar-fixed-bottom hidden-xs">
		<div class="container">
			<p class="navbar-text text-center">{$onlineUser} ##USER_ONLINE##</p>
			<p class="navbar-text pull-right">
				&copy; 2018 FS19 Web Stats by John Hawk &bull; <a href="https://github.com/J0hnHawk/FS19_WebStats" target="_blank">GitHub</a> &bull; Map config by {$map.configBy}
			</p>
		</div>
	</div>
	{/if} {if $reloadPage && $serverOnline}
	<script type="text/javascript">
	var time = new Date().getTime();
	$(document.body).bind("mousemove keypress", function () {
	    time = new Date().getTime();
	});

	setInterval(function() {
	    if (new Date().getTime() - time >= 60000) {
	        window.location.reload(true);
	    }
	}, 1000);
	</script>
	{/if}
</body>
</html>