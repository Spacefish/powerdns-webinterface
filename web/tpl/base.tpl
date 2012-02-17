<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="img/style.css" />
		<script language="javascript" type="text/javascript" src="img/jquery-1.4.2.min.js"></script>
		<script language="javascript" type="text/javascript" src="img/jquery.tablesorter.min.js"></script>
		<script language="javascript" type="text/javascript" src="img/code.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				PowerDNS Webinterface
				{if $app->Auth->isAuthed()}
					<div class="logout_button">
						{t}Logged in as{/t} {$_SESSION.auth.username}&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="?p=login&a[0]=logout-logout">
							<img src="img/icons/cancel.png" /> {t}Logout{/t}
						</a>
					</div>
				{/if}
			</div>
			<div id="main">
				{if $app->Auth->isAuthed()}
					<div class="menu_left" id="menu">
						<ul>
							<li id="m1">
								<a href="?p=overview">
									{t}Overview{/t}
								</a>
							</li>
							<li id="m2">
								<a href="?p=domains">
									{t}Domains{/t}
								</a>
							</li>
							{if $app->Auth->isAdmin()}
							<li id="m22">
								<a href="?p=records">
									{t}Records{/t}
								</a>
							</li>
							<li id="m3">
								<a href="?p=templates">
									{t}Templates{/t}
								</a>
							</li>
							<li id="m4">
								<a href="?p=user">
									{t}User{/t}
								</a>
							</li>
							<li id="m5">
								<a href="?p=supermasters">
									{t}Supermasters{/t}
								</a>
							</li>
							{/if}
						</ul>
					</div>
				{/if}
				<div class="{if $app->Auth->isAuthed()}contentbox_withmenu{else}contentbox{/if}" id="content">
					<div id="msg"></div>
					{include file=$_TEMPLATE}
					<div class="copyleft">
						{if $app->Auth->isAuthed()}PowerDNS Webinterface {$app->Configuration->getValue('base/version')}<br />{/if}
						Copyright 2011 Timo Witte Licensed under the Apache License (2.0)
					</div>
				</div>
			</div> {* END OF main * }
		</div> {* END OF wrapper *}
	</body>
</html>
