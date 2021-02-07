(function (api) {

	/**
	 * Add client side validation for title text.
	 *
	 * @link https://make.wordpress.org/core/2016/07/05/customizer-apis-in-4-6-for-setting-validation-and-notifications/
	 */

	api( 'customize_hero_title_text', function (setting) {
		setting.validate = function (value) {
			var code = 'capital_p_dangit', notification;

			var capitalPcheck = new RegExp( '.*([wW]ordpress).*' );

			if (capitalPcheck.test( value )) {
				notification = new wp.customize.Notification( code, {message: customizeHero.l10n.capitalP} );
				setting.notifications.add( code, notification );
			} else {
				setting.notifications.remove( code);
			}

			return value;
		};
	} );

})( wp.customize );
