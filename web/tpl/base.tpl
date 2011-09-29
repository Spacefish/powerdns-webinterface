<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="img/style.css" />
		<script language="javascript" type="text/javascript" src="img/jquery-1.4.2.min.js"></script>
		<script language="javascript" type="text/javascript" src="img/jquery.tablesorter.min.js"></script>
		<script language="javascript" type="text/javascript" src="img/code.js"></script>
	</head>
	<body>
		<div class="webdns_header">
			PowerDNS Webinterface
			{if $app->Auth->isAuthed()}
				<div class="logout_button">
					Logged in as {$_SESSION.auth.username}&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="?p=login&a[0]=logout-logout">
						<img src="img/icons/cancel.png" /> Logout
					</a>
				</div>
			{/if}
		</div>
		{if $app->Auth->isAuthed()}
			<div class="menu_left">
				<ul>
					<li id="m1">
						<a href="?p=overview">
							Overview
						</a>
					</li>
					<li id="m2">
						<a href="?p=domains">
							Domains
						</a>
					</li>
					{if $app->Auth->isAdmin()}
					<li id="m22">
						<a href="?p=records">
							Records
						</a>
					</li>
					<li id="m3">
						<a href="?p=templates">
							Templates
						</a>
					</li>
					<li id="m4">
						<a href="?p=user">
							User
						</a>
					</li>
					<li id="m5">
						<a href="?p=supermasters">
							Supermasters
						</a>
					</li>
					{/if}
				</ul>
			</div>
		{/if}
		<div class="{if $app->Auth->isAuthed()}contentbox_withmenu{else}contentbox{/if}">
			<div id="msg"></div>
			{include file=$_TEMPLATE}
			<div class="copyleft">
				Copyright 2010 Timo Witte Licensed under the Apache License (2.0)
			</div>
		</div>
	</body>
</html>
