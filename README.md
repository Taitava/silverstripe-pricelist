# silverstripe-pricelist

## Setup

1. Extend SiteTree (or some other class derived from it):

`mysite/_config/pricelist.yml`:
```YAML
SiteTree:
  extensions:
   - PricelistSiteTreeExtension
```

**Note!** If you apply the extension to any other class than `SiteTree`, you need to define this additional configuration in `mysite/_config/pricelist.yml`:
```YAML
Pricelist:
  belongs_many_many:
    Pages: *YourCustomClassNameHere*
```
If you forget this, (at least) the backend will crash when you go to add a new Pricelist to your pricelist page. This additional configuration is not needed if you just extend the whole `SiteTree` class, as that's already configured by default.

2. Put this to `themes/*your-theme-folder*/Page.ss` (or to some other template file):
```
$AllPricelists
```

3. If you want to adjust some configuration settings, you can put these to `mysite/_config/pricelist.yml`:
```
Pricelist:
  include_stylesheet: false
  currency_sign: '€' #Whether to use the Requirements class to include this module's own stylesheet in frontend to perform some small styling.
  currency_side: 'right'
PricelistItem:
  hide_zero_prices: false #If true, do not display anything in the price column for items whose price is 0.
```
Note that the above listing contains the default values, so if they seem good for you, you do not need to copypaste this list to anywhere.

4. Run `/dev/build?flush=all` in your browser.

5. Go to the CMS and edit some page. You should see a Pricelists tab there.


## Contribution

If you have any ideas about how to improve this module or any questions, I would be glad to hear them! :) Please raise an issue or create a pull request - which ever you like.
