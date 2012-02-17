<form method="POST">
	<div style="background-color: #eee; width: 500px; margin: auto; padding: 10px; margin-top: 20px;">
		<img src="img/icons/lock.png"> <span style="color: #888; font-size: 14px;">{t}Please login below{/t}
		<div style="text-align: center;" class="hairlinebox">
			<table align="center" width="100%" cellspacing="5" style="margin-bottom: 0.5em;">
				<tr>
					<td width="150">
						{t}Username{/t}
					</td>
					<td>
						<input type="text" name="username" value="{$username}" class="logininput" />
					</td>
				</tr>
				<tr>
					<td>
						{t}Password{/t}
					</td>
					<td>
						<input type="password" name="password" class="logininput" />
					</td>
				</tr>
			</table>
			{if $fail}
				<span style="color: #f00;">{t}Wrong username or password{/t}</span>
				<br />
			{/if}
			<input type="submit" value="{t}Login{/t}" class="savebutton" />
		</div>
	</div>
</form>
