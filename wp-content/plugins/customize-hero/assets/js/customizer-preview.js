(function( api, $ ) {

	api( 'customize_hero_background_color', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero' ).css( 'background-color', newVal );
		});
	});

	api( 'customize_hero_title_text', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero-title' ).text( newVal );
		});
	});

	api( 'customize_hero_title_size', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero-title' ).css( 'font-size', newVal + 'px' );
		});
	});

	api( 'customize_hero_title_color', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero-title' ).css( 'color', newVal );
		});
	});

	api( 'customize_hero_description_text', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero-description' ).text( newVal );
		});
	});

	api( 'customize_hero_description_color', function( setting ) {
		setting.bind(function( newVal ) {
			$( '#customize-hero-description' ).css( 'color', newVal );
		});
	});

})( wp.customize, jQuery );
