=== Printus - Automatic Printing Plugin for WooCommerce - Print WooCommerce Orders, PDF Invoices, Packaging Slips & More ===
Contributors: uriahs-victor
Tags:  woocommerce, print invoice, invoices, order printing, pdf
Requires at least: 5.7
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically print order invoices, receipts, package slips and labels to your local printer. Cloud Printing for WooCommerce made easy.

== Description ==

[Printus](https://printus.cloud) is your ultimate cloud printing companion, enabling you to effortlessly print customer order receipts, invoices, shipping labels, packing slips, delivery notes, and more on any printer worldwide. Whether you're a restaurant eager to promptly receive new orders in the kitchen or a logistics business channeling jobs to the office printer, Printus ensures seamless operations.

**Automatic Order Printing Plugin for WooCommerce** 🚀

Printus simplifies the process by allowing you to choose triggers such as Checkout Complete, Order Complete, or Payment Complete. It effortlessly connects with the PrintNode API, enabling immediate PDF printing of WooCommerce invoices and packing slips as orders come in. No more email checking; orders are printed in seconds.

✅ **No Hardware Dependencies**

Printus is hardware-agnostic, working with **ANY PRINTER** you have on hand. It excels with receipt printers, directly printing orders to your kitchen or front-of-house POS, ensuring no more checking emails for received orders.

✅ **Print on Demand**

Need to reprint an old receipt, invoice, or label? Printus lets you revisit the order in your WooCommerce edit screen and re-print, even to a different printer, template, or paper size.

✅ **The Most Extendable Cloud Printing Plugin for WooCommerce**

With extensive actions and filters, Printus offers unparalleled flexibility. From crafting custom templates to defining unique print triggers, you can tailor its functionality to your needs with minimal coding knowledge.

✅ **Works With Any Printer**

Whether you have a receipt printer or an enterprise-level Xerox, Printus adapts to your device, offering versatile printing for WooCommerce orders, invoices, or package labels.

✅ **Built on Reliability**

Printus provides certainty in order printing for your store. Built on a robust foundation that has processed billions of cloud printing jobs, it ensures labels, PDF invoices, and packing slips for WooCommerce are handled with ease.

✅ **Supports Low-power Systems**

Worried about keeping your computer and printer on? Printus also operates seamlessly with low-power devices like Raspberry Pi, receiving WooCommerce order print jobs even while you sleep.

> **Works Great with Kikote!**
>
> The plugin seamlessly integrates with [Location Picker at Checkout Plugin for WooCommerce](https://wordpress.org/plugins/map-location-picker-at-checkout-for-woocommerce/). Feel free to try them both out!

**Stores That Benefit:**

- Online food delivery websites
- Online supermarkets
- Clothing websites
- Pure online restaurants
- Brick & mortar restaurants taking orders online
- And more

Printus is especially beneficial for restaurant-style E-commerce stores requiring immediate notifications for new orders. However, its utility extends beyond this use-case. Regardless of the document you need to print from your WooCommerce store (receipts, invoices, shipping labels, etc.), Printus has you covered.

Unhappy with the default templates? [Contact us](https://printus.cloud/custom-template-service/) to build your custom template at an affordable one-time flat fee.

> Works Great with Delivery & Scheduling WooCommerce Plugin!
>
> The plugin works great with [Delivery & Pickup Scheduling for WooCommerce](https://wordpress.org/plugins/delivery-and-pickup-scheduling-for-woocommerce/). Feel free to try them both out!

### Configuring Plugin:

- The plugin settings are located in **WordPress Admin Dashboard->SoaringLeads->Printus Cloud Printing**.

### Plugin Documentation

- You can find the plugin documentation [Here >>>](https://printus.cloud/docs/)

### Plugin Support

The plugin has support for built-in WooCommerce variations as well as custom product options(product addons) added by the following plugins:

- Product Addons for Woocommerce – Product Options with Custom Fields by Acowebs (Both Lite & Pro)
- WooCommerce Product Addons Ultimate by Plugin Republic.
- Advanced Product Fields (Product Addons) for WooCommerce by StudioWombat (Both Lite & Pro)
- Extra Product Options & Add-Ons for WooCommerce by ThemeComplete
- Extra product options For WooCommerce | Custom Product Addons and Fields by ThemeHigh
- Product Addons & Fields for WooCommerce by Themeisle (PPOM)
- Product Add-Ons by WooCommerce
- WooCommerce Product Add-ons & Extra Options by YITH (Both Lite & Pro)

We've tried to add support for the most popular Product Addon plugins for WooCommerce, feel free to use one of the above if you're creating custom product options, or let us know which plugin you're using and we'll try to add support for it. If one of the above plugins does not display its addons on the prints then feel free to let us or the developers of those plugins know.

### Misc

- Learn more about Printus and it's remote cloud printing features for WooCommerce [Here >>>](https://printus.cloud)
- Meet E-commerce store owners like yourself to discuss on ways to help grow your E-commerce store. [Here >>>](https://printus.cloud/e-commerce-support-community/)
- Checkout our other plugin to help further manage your store: 

 - Delivery & Pickup Scheduling Plugin for WooCommerce [Here >>>](https://chwazidatetime.com)

 - Kikote - Location Picker at Checkout Plugin for WooCommerce [Here >>>](https://lpacwp.com)

== Frequently Asked Questions ==

= Printing doesn't work =

There can be multiple reasons why the printing doesn't work. We've listed some troubleshooting steps that you can try out [here >>>](https://printus.cloud/docs/issue-with-final-print/)

= Is the plugin free? =

Yes the plugin is free. PrintNode however is not completely free but offers a free plan. You can learn more about the PrintNode pricing [here >>>](https://printus.cloud/docs/printnode-pricing/)

= Do I need a special printer? =

No, you do not need any special printer. The plugin should work with almost every standard printer as long as the printer is connected to your computer.

== Screenshots ==

1. Plugin General Settings. 
2. Plugin Template Settings. More settings and changes are available via plugin Action and Filters.
3. Plugin Localization Settings. These settings allow you to change the different text displayed in the plates, but it is totally possible to show custom text in your custom templates.

== Changelog ==

= 1.2.6 (2024-10-28) =
* [Dev] Updated Freemius SDK.

= 1.2.5 (2024-10-28) =
* [Improvement] Better support for product addons created by Acowebs' product addons plugin.
* [Improvement] Better margins when Page Length Fix option is enabled.
* [Dev] Updated Freemius SDK.
* [Info] Tested on WP 6.7-rc1.
* [Info] Tested on WC 9.4-rc1.

= 1.2.4 (2024-09-04) =
* [New] Add discount and order note to Nimbus and Cumulus templates.
* [New] Add new "Template Settings" option to show product prices inclusive of tax when printing.
* [Fix] Price text wasn't following font size setting in plugin.
* [Compatibility] Improve support for PPOM plugin.
* [Change] Fixed typo in `pintus_include_formatted_variation` filter, renamed to `printus_include_formatted_variation`.
* [Change] Add new `pintus_include_formatted_variation` filter to control when to show woocommerce variation on print.
* [Dev] Updated Freemius SDK.
* [Info] Tested on WC 9.2.

= 1.2.3 (2024-07-22) =
* [New] Added toggle option for Payment Complete trigger to prevent printing unless the payment was successful.
* [Change] Prices of products in the printed table will no longer show the discounted price of the product. This behaviour can be changed using the `printus_template__item_price` filter.
* [Dev] A new `printus_printnode_restrict_printer_ids` filter makes it possible to restrict the printer IDs that show on a site. This is useful if you're using one account across multiple websites.
* [Dev] Other code improvements.

= 1.2.2 (2024-07-09) =
* [Compatibility] Add support for custom order numbers.
* [Change] Switched around the `printus_template__order_item` filter. Please test your print after updating, especially if you've gotten a custom template in the past.
* [Dev] Made it possible to set print triggers as an array to allow for multiple triggers.
* [Info] Tested on WC 9.1.0-rc1.
* [Info] Tested on WP 6.6-rc2.

= 1.2.1 (2024-05-14) =
* [Compatibility] Add support Plugin Republic WooCommerce Add-ons uploads field type.
* [Dev] Add WC Blocks Incompatibility notice - WooCommerce Blocks doesn't fire "Checkout Complete" Hook.
* [Dev] Updated Freemius SDK.
* [Info] Tested on WC 8.9.0-rc1.

= 1.2.0 (2024-03-06) =

* [Fix] The order item name was being duplicated for items that did not have addons when they were in the same order as items that contained addons.
* [Compatibility] Add support for WooCommerce Product Add-ons & Extra Options by YITH (both Lite & Pro).
* [Compatibility] Add support for Advanced Product Fields Pro for WooCommerce by StudioWombat.
* [Compatibility] Add support for Woocommerce Custom Product Addons Pro by Acowebs.
* [Change] `printus_template__item` filter has been renamed to `printus_template__order_item`.
* [Change] Pass entire order item name with variations included to `printus_template__order_item` filter.
* [Change] Add `text-align: center` to text added to templates via `printus_template__after_template_data` action.
* [Dev] Add `printus_save_pdf` filter for saving PDF files to uploads directory in `wp-content\uploads\printus-pdfs`. Simply return `true` to the filter to enable.
* [Info] Tested on WC 8.7.0-rc1.

= 1.1.10 (2024-02-18) =

* [Fix] Built-in WooCommerce product variations were not showing on printed orders.
* [Fix] Bold font-weight was not applying correctly for product addon names.
* [Improvement] Add `printus_template__include_addon_price` filter to enable showing of product addon prices when using PPOM. Simply return boolean true to filter to enable showing of prices.
* [Dev] Updated Freemius SDK.
* [Info] Tested on WC 8.6.


= 1.1.9 (2023-11-30) =

* [Fix] Order Complete print trigger was not working.
* [Improvement] Add "Print Length Fix" option to "Tools" menu which fixes print length issues in some printers.
* [Improvement] Display product addons better on printed templates.
* [Compatibility] WooCommerce Product Addons Ultimate by Plugin Republic.
* [Compatibility] Advanced Product Fields (Product Addons) for WooCommerce by StudioWombat.
* [Dev] Change plugin initialization to fire on `plugins_loaded` hook.
* [Dev] Updated Freemius SDK.
* [Info] Tested on WC 8.3.
* [Info] Tested on WP 6.4.


= 1.1.8 (2023-10-15) =
* [Improvement] Update Cumulus template data to take a bit less space on paper.
* [Compatibility] With [Product Addons & Fields for WooCommerce (PPOM)](https://es.wordpress.org/plugins/woocommerce-product-addon/) by ThemeIsle
* [Fix] Product addon labels would always show on receipt when using Product Addons by WooCommerce.
* [Fix] A4, Letter and Legal paper sizes were being converted twice.
* [Info] Compatibility with WC HPOS.
* [Info] Tested on WC 8.2.

= 1.1.7 =
* [Compatibility] With [Extra product options For WooCommerce | Custom Product Addons and Fields Lite](https://wordpress.org/plugins/woo-extra-product-options/) by ThemeHigh
* [Compatibility] With [Product Addons for Woocommerce – Product Options with Custom Fields](https://wordpress.org/plugins/woo-custom-product-addons/) by Acowebs
* [Compatibility] With [Extra Product Options & Add-Ons for WooCommerce](https://codecanyon.net/item/woocommerce-extra-product-options/7908619) by ThemeComplete
* [Compatibility] With [Product Addons](https://woocommerce.com/products/product-add-ons/) by WooCommerce
* [Info] Tested on WC 8.1.
* [Dev] Updated Freemius SDK.


= 1.1.6 =
* [Fix] Prints were off canvas because of turning fit to page option to false. Use `printus__printnode_job_data` filter to add more config data to print jobs if needed.

= 1.1.5 =
* [Fix] Converting MM to PT was returning string value instead of float value.

= 1.1.4 =
* [Fix] Customer first name showing twice on printed templates.
* [Fix] Error when no height value is entered in height field for manual paper size.
* [Improvement] It is now possible to also load custom templates from a plugin and not just child themes.
* [Improvement] Made PrintNode job data filterable before sending to API.
* [Info] Tested on WP 6.3.
* [Info] Tested on WC 8.0.

= 1.1.3 =
* [Dev] Updated Freemius SDK.

= 1.1.2 =
* [Dev] Version bump.

= 1.1.1 =
* [Info] Tested on WC 7.9.
* [Dev] Updated Freemius SDK.

= 1.1.0 =
* [Note] There are breaking changes in this update.
* [New] Option to add store phone number.
* [New] Feature to manually print an order (Print on demand).
* [New] Cumulus Template - for larger sized papers (A4, Letter, Legal +).
* [Change] Moved around some plugins settings.
* [Change] Store details of Basic template has been replaced with Customer details.
* [Change] Made format changes to Basic template.
* [Change] Basic template has been renamed to Nimbus.
* [Change] Customer address details will be shown on Nimbus template.
* [Improvement] More localization string options.

= 1.0.0 =

* Initial release.