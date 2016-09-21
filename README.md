# silverstripe-pricelist

## Setup

1. Extend SiteTree (or some other class derived from it):

`mysite/_config/pricelist.yml`:
```YAML
SiteTree:
  extensions:
   - PricelistSiteTreeExtension
```

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
```
Note that the above listing contains the default values, so if they seem good for you, you do not need to copypaste this list to anywhere.

4. Run `/dev/build?flush=all` in your browser.

5. Go to the CMS and edit some page. You should see a Pricelists tab there.


## Contribution

If you have any ideas about how to improve this module or any questions, I would be glad to hear them! :) Please raise an issue or create a pull request - which ever you like.
