//Show/hide the EndingPrice field depending on the state of IsStartingPrice checkbox.
var IsStartingPriceChanged = function ()
{
	var display = jQuery(this).attr('checked') ? 'inline' : 'none';
	jQuery('#Form_ItemEditForm_EndingPrice_Holder').css('display', display);
};
jQuery('input[name=IsStartingPrice]').on('change', IsStartingPriceChanged);
IsStartingPriceChanged(); //Trigger also on page load.