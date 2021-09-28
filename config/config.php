<?php
	return [
		/*
		 * The firebase_project_id key is used when connecting to firebase authentication.
		 */
		'firebase_project_id' => '',
		/*
		 * The TARGET_PROVIDER key is used for connecting with your desired model.
		 * by default laravel provider is users
		 * If TARGET_PROVIDER is not set, by defalt users will be used.
		 * Example: In below example your target_provider is customers
		 *
		 * 'providers' => [
		 *		'customers' => [
		 *			'driver' => 'eloquent',
		 *			'model' => App\User::class,
		 *		],
		 *	],
		 *
		 */
		'target_provider' => 'users',
	];
