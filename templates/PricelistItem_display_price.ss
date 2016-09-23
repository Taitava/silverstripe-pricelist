<% if $NormalPrice > $CurrentPrice %>
	<span class="pricelist-current-price pricelist-discount-price">$StartingPriceAbbreviation $LeftCurrencySign $CurrentPrice.XML $RightCurrencySign</span>
	(<span class="pricelist-old-price">$StartingPriceAbbreviation $LeftCurrencySign $NormalPrice.XML $RightCurrencySign</span>)
<% else %>
	<span class="pricelist-current-price">$StartingPriceAbbreviation $LeftCurrencySign $CurrentPrice.XML $RightCurrencySign</span>
<% end_if %>