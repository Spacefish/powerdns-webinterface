<form method="POST">
	<div style="background-color: #eee; width: 500px; margin: auto; padding: 10px; margin-top: 20px;">
		<img src="img/icons/lock.png"> <span style="color: #888; font-size: 14px;">Please login below
		<div style="text-align: center;" class="hairlinebox">
			<table align="center">
				<tr>
					<td>
						Username
					</td>
					<td>
						<input type="text" name="username" value="{$username}" />
					</td>
				</tr>
				<tr>
					<td>
						Password
					</td>
					<td>
						<input type="password" name="password" />
					</td>
				</tr>
			</table>
			{if $fail}
				<span style="color: #f00;">Wrong username or password</span>
				<br />
			{/if}
			<input type="submit" value="Login" />
		</div>
	</div>
</form>
