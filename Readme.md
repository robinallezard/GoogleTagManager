# Google Tag Manager

This module is made to use the Google Tag Manager tool.

## Installation

### Composer

```
composer require thelia/google-tag-manager-module dev-main
```

## Usage

You need to configure the id from your google tag manager account in the thelia administration panel.\
It should looks like ```GTM-XXXX```. \
This will generate both the head script and the body no-script tags and insert them in the ```main.head-top``` and
in the ```main.body-top``` hooks. \
If these hooks are not present in your template, you'll need to add them beforehand.

To track products added to the cart you need to implement this js event on the "Add to cart" buttons.
```js 
const event = new CustomEvent("addPseToCart", {
    detail: {
          pse: pseId,
          quantity
    },
 });
 document.dispatchEvent(event);
```
