<?php
return [

	// https://console.developers.google.com/
	// https://developers.google.com/oauthplayground/
	'google' => [
		'client_id' => '429390440371-f99m46g6ouv54c5uq84uerj357r1mmlg.apps.googleusercontent.com',
		'client_secret' => 'K29R-8WVc_QY1V-P49ZPzsmP',
		'api_key' => 'AIzaSyCA-E6qmfC-5lBExUOoOrXhflEHJs3v9I8',
		'scopes' => ['https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'],
	],


	// https://developers.facebook.com/apps/?action=create
	// https://developers.facebook.com/docs/facebook-login/permissions#permissions
	'facebook' => [
		'app_id' => '1564147550567740',
		'app_secret' => 'bab1da08a930a8a1640451ecd3f3ec22',
		'scopes' => ['public_profile', 'email'],
	],


	// https://dev.twitter.com/app/new
	'twitter' => [
		'consumer_key' => 'YK1JUd2MplmcRRpVKfiQ',
		'consumer_secret' => 'xMJoAsHN3nAffCJBMUZ499D9l7b645mpQuMs9P40I',
	],
];
