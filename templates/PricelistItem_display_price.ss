<% if $NormalPrice > $CurrentPrice %>
	<span class="pricelist-old-price">$LeftCurrencySign $NormalPrice.XML $RightCurrencySign</span>
	<span class="pricelist-current-price pricelist-discount-price">$LeftCurrencySign $CurrentPrice.XML $RightCurrencySign</span>
<% else %>
	<span class="pricelist-current-price">$LeftCurrencySign $CurrentPrice.XML $RightCurrencySign</span>
<% end_if %>