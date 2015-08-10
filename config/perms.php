<?php

/**
 * Note that '*' as a permission name grants all non-admin permissions and
 * anyone marked as '%' is considered an admin with unlimited permissions
 */

return [
	/**
	 * User role definitions
	 *
	 * The key is the name of the role and the array
	 * is the list of pages that role has access to
	 */
	'roles' => [
		'trial' => ['demonyms', 'log/mod', 'log/mod/search', 'log/mod/listing'],
		'notices' => ['notices', 'notices/fetch']
	],

	/**
	 * Pages that any authenticated user can see
	 */
	'open_pages' => [
		'bot/status',
		'bot/metadata',
		'/'
	]
]

?>