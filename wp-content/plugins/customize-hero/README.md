# Customize Hero #
**Contributors:** andrew.taylor, westonruter, danielbachhuber  
**Tags:** customizer, customize  
**Requires at least:** 3.7  
**Tested up to:** 4.8.1  
**Stable tag:** 0.1.0  
**License:** GPLv3 or later  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  

Allows WordPress sites to add a hero image or carousel via the customizer.

## Description ##

This is an example plugin built for the WordPress customizer workshop at LoopConf 2.1 on February, 2017.

Each branch of this repository corresponds with a checkpoint in the workshop. The `master` branch is the beginning code base.

### Files ###
The hero options implemented with the WordPress customizer PHP API can be found in the files [`inc/class-customize-hero-options.php`](https://github.com/ataylorme/customize-hero/blob/master/wp-content/plugins/customize-hero/inc/class-customize-hero-options.php) and [`inc/class-customize-hero.php`](https://github.com/ataylorme/customize-hero/blob/master/wp-content/plugins/customize-hero/inc/class-customize-hero.php).

JavaScript functionality can be found in the [`assets/js/customizer-preview.js`](https://github.com/ataylorme/customize-hero/blob/master/wp-content/plugins/customize-hero/assets/js/customizer-preview.js) file.

## Branches ##

### [master](https://github.com/ataylorme/customize-hero/tree/master) ###
The `master` branch is the starting codebase for the workshop. It adds hero options with the WordPress customizer.

This branch does _not_ include any Customizer functionality introduced in WordPress `4.5` or later.

The options use `postMessage` but need some improvement. The code in `inc/class-customize-hero-options.php` is where you'll want to start.

### [full-page-reload](https://github.com/ataylorme/customize-hero/tree/full-page-reload) ###
This is the starting codebase without `postMessage` implemented.

### [partial-refresh](https://github.com/ataylorme/customize-hero/tree/partial-refresh) ###
The `partial-refresh` branch expands on the customizer implementation by adding a new field for hero background image in `inc/class-customize-hero-options.php` with partial refresh support for an optimized user experience.
 
Modified files:

* [`inc/class-customize-hero-options.php`](https://github.com/ataylorme/customize-hero/blob/partial-refresh/wp-content/plugins/customize-hero/inc/class-customize-hero-options.php)
* [`inc/class-customize-hero.php`](https://github.com/ataylorme/customize-hero/blob/partial-refresh/wp-content/plugins/customize-hero/inc/class-customize-hero.php)
 
### [settings-validation](https://github.com/ataylorme/customize-hero/tree/settings-validation) ###
The `settings-validation` branch further expands on the Customizer implementation by adding settings validation to the hero title text and hero background image in `inc/class-customize-hero-options.php`.

Modified files:

* [`inc/class-customize-hero-options.php`](https://github.com/ataylorme/customize-hero/blob/partial-refresh/wp-content/plugins/customize-hero/inc/class-customize-hero-options.php)
* [`inc/class-customize-hero.php`](https://github.com/ataylorme/customize-hero/blob/partial-refresh/wp-content/plugins/customize-hero/inc/class-customize-hero.php)

## Changelog ##

### 0.1.0 ###
* Initial version
* Add options menu page to WordPress admin
* Register options with customizer
* Handle hero output
* Register inline CSS for customized styles
* Use postMessage for live preview
