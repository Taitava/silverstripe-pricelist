<span class="pricelist-current-price <% if $NormalPrice > $CurrentPrice %>pricelist-discount-price<% end_if %>">
		$StartingPriceAbbreviation $LeftCurrencySign $CurrentPrice.XML $RightCurrencySign
	<% if $EndingPrice != 0 %> - $LeftCurrencySign $EndingPrice $RightCurrencySign<% end_if %>
</span>
<% if $NormalPrice > $CurrentPrice %>
	(<span class="pricelist-old-price">$StartingPriceAbbreviation $LeftCurrencySign $NormalPrice.XML $RightCurrencySign</span>)
<% end_if %>