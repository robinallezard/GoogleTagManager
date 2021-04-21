# Google Tag Manager

This module is made to use the Google Tag Manager tool.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is GoogleTagManager.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/google-tag-manager-module:~1.0
```

## Usage

You need to configure the id from your google tag manager account in the thelia administration panel.\
It should looks like ```GTM-XXXX```. \
This will generate both the head script and the body no-script tags and insert them in the ```main.head-top``` and 
in the ```main.body-top``` hooks. \
If these hooks are not present in your template, you'll need to add them beforehand. 

## Migration form version 1.0 to 2.0 
This module no longer needs the whole script, just add the GTM id in the Thelia administration panel. 
The ```main.head-top``` hook should be present as it was used in 1.0 but you'll need to check the ```main.body-top``` one.\
If you had the noscript block in your template, you have to remove it as it will now be handled by this module. 

