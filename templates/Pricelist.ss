<div class="pricelist-container">
	<h2>$Title.XML</h2>
	
	<div class="pricelist-description">
		$Description
	</div>
	
	<% if $IncludeItems %>
		<table class="pricelist-table">
			<thead>
				<tr>
					<% loop $Columns %>
						<th>$Column</th>
					<% end_loop %>
				</tr>
			</thead>
			<tbody>
				<% loop $Items %>
					$TableRow
				<% end_loop %>
			</tbody>
		</table>
	<% end_if %>
</div>
