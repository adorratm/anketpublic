<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Admin Panel Routes
 */
$route['panel/login'] = 'backend/AuthController/login';
$route['panel/refresh-token'] = 'backend/AuthController/refresh';
$route['panel/google-login'] = 'backend/AuthController/googleLogin';
$route['panel/facebook-login'] = 'backend/AuthController/facebookLogin';
$route['panel/twitter-login'] = 'backend/AuthController/twitterLogin';
$route['panel/me'] = 'backend/AuthController/me';

// SETTINGS
$route['panel/settings/(:num)'] = 'backend/SettingsController/$1';
$route['panel/settings/save'] = 'backend/SettingsController/save';
$route['panel/settings/update/(:num)'] = 'backend/SettingsController/update/$1';
$route['panel/settings/delete'] = 'backend/SettingsController/delete';
$route['panel/settings/delete/(:num)'] = 'backend/SettingsController/delete/$1';
$route['panel/settings/datatable'] = 'backend/SettingsController/datatable';
$route['panel/settings/rank/(:num)'] = 'backend/SettingsController/rank/$1';
$route['panel/settings/isactive/(:num)'] = 'backend/SettingsController/isactive/$1';
$route['panel/settings/uploadimage'] = 'backend/SettingsController/uploadImage';

// EMAIL SETTINGS
$route['panel/email-settings/(:num)'] = 'backend/EmailSettingsController/$1';
$route['panel/email-settings/save'] = 'backend/EmailSettingsController/save';
$route['panel/email-settings/update/(:num)'] = 'backend/EmailSettingsController/update/$1';
$route['panel/email-settings/delete'] = 'backend/EmailSettingsController/delete';
$route['panel/email-settings/delete/(:num)'] = 'backend/EmailSettingsController/delete/$1';
$route['panel/email-settings/datatable'] = 'backend/EmailSettingsController/datatable';
$route['panel/email-settings/rank/(:num)'] = 'backend/EmailSettingsController/rank/$1';
$route['panel/email-settings/isactive/(:num)'] = 'backend/EmailSettingsController/isactive/$1';

// CATEGORIES
$route['panel/categories/(:num)'] = 'backend/CategoriesController/$1';
$route['panel/categories/save'] = 'backend/CategoriesController/save';
$route['panel/categories/update/(:num)'] = 'backend/CategoriesController/update/$1';
$route['panel/categories/delete'] = 'backend/CategoriesController/delete';
$route['panel/categories/delete/(:num)'] = 'backend/CategoriesController/delete/$1';
$route['panel/categories/datatable'] = 'backend/CategoriesController/datatable';
$route['panel/categories/rank/(:num)'] = 'backend/CategoriesController/rank/$1';
$route['panel/categories/isactive/(:num)'] = 'backend/CategoriesController/isactive/$1';
$route['panel/categories/ispopular/(:num)'] = 'backend/CategoriesController/ispopular/$1';

// SLIDES
$route['panel/slides/(:num)'] = 'backend/SlidesController/$1';
$route['panel/slides/save'] = 'backend/SlidesController/save';
$route['panel/slides/update/(:num)'] = 'backend/SlidesController/update/$1';
$route['panel/slides/delete'] = 'backend/SlidesController/delete';
$route['panel/slides/delete/(:num)'] = 'backend/SlidesController/delete/$1';
$route['panel/slides/datatable'] = 'backend/SlidesController/datatable';
$route['panel/slides/rank/(:num)'] = 'backend/SlidesController/rank/$1';
$route['panel/slides/isactive/(:num)'] = 'backend/SlidesController/isactive/$1';

// PAGES
$route['panel/pages'] = 'backend/PagesController';
$route['panel/pages/(:num)'] = 'backend/PagesController/$1';
$route['panel/pages/save'] = 'backend/PagesController/save';
$route['panel/pages/update/(:num)'] = 'backend/PagesController/update/$1';
$route['panel/pages/delete'] = 'backend/PagesController/delete';
$route['panel/pages/delete/(:num)'] = 'backend/PagesController/delete/$1';
$route['panel/pages/datatable'] = 'backend/PagesController/datatable';
$route['panel/pages/rank/(:num)'] = 'backend/PagesController/rank/$1';
$route['panel/pages/isactive/(:num)'] = 'backend/PagesController/isactive/$1';

// USERS
$route['panel/users'] = 'backend/UsersController';
$route['panel/users/(:num)'] = 'backend/UsersController/$1';
$route['panel/users/save'] = 'backend/UsersController/save';
$route['panel/users/update/(:num)'] = 'backend/UsersController/update/$1';
$route['panel/users/delete'] = 'backend/UsersController/delete';
$route['panel/users/delete/(:num)'] = 'backend/UsersController/delete/$1';
$route['panel/users/datatable'] = 'backend/UsersController/datatable';
$route['panel/users/rank/(:num)'] = 'backend/UsersController/rank/$1';
$route['panel/users/isactive/(:num)'] = 'backend/UsersController/isactive/$1';
$route['panel/users/cities'] = 'backend/UsersController/cities';
$route['panel/users/districts'] = 'backend/UsersController/districts';
$route['panel/users/districts/(:any)'] = 'backend/UsersController/districts/$1';
$route['panel/users/neighborhoods'] = 'backend/UsersController/neighborhoods';
$route['panel/users/neighborhoods/(:any)'] = 'backend/UsersController/neighborhoods/$1';
$route['panel/users/quarters'] = 'backend/UsersController/quarters';
$route['panel/users/quarters/(:any)'] = 'backend/UsersController/quarters/$1';


// USER ROLES
$route['panel/user-roles'] = 'backend/UserRolesController';
$route['panel/user-roles/(:num)'] = 'backend/UserRolesController/$1';
$route['panel/user-roles/save'] = 'backend/UserRolesController/save';
$route['panel/user-roles/update/(:num)'] = 'backend/UserRolesController/update/$1';
$route['panel/user-roles/delete'] = 'backend/UserRolesController/delete';
$route['panel/user-roles/delete/(:num)'] = 'backend/UserRolesController/delete/$1';
$route['panel/user-roles/datatable'] = 'backend/UserRolesController/datatable';
$route['panel/user-roles/rank/(:num)'] = 'backend/UserRolesController/rank/$1';
$route['panel/user-roles/isactive/(:num)'] = 'backend/UserRolesController/isactive/$1';

// MENUS
$route['panel/menus'] = 'backend/MenusController';
$route['panel/menus/(:num)'] = 'backend/MenusController/$1';
$route['panel/menus/save'] = 'backend/MenusController/save';
$route['panel/menus/update/(:num)'] = 'backend/MenusController/update/$1';
$route['panel/menus/delete'] = 'backend/MenusController/delete';
$route['panel/menus/delete/(:num)'] = 'backend/MenusController/delete/$1';
$route['panel/menus/rank/(:num)'] = 'backend/MenusController/rank/$1';
$route['panel/menus/isactive/(:num)'] = 'backend/MenusController/isactive/$1';

// POLLS
$route['panel/polls/(:num)'] = 'backend/PollsController/$1';
$route['panel/polls/save'] = 'backend/PollsController/save';
$route['panel/polls/update/(:num)'] = 'backend/PollsController/update/$1';
$route['panel/polls/delete'] = 'backend/PollsController/delete';
$route['panel/polls/delete/(:num)'] = 'backend/PollsController/delete/$1';
$route['panel/polls/datatable'] = 'backend/PollsController/datatable';
$route['panel/polls/rank/(:num)'] = 'backend/PollsController/rank/$1';
$route['panel/polls/isactive/(:num)'] = 'backend/PollsController/isactive/$1';
$route['panel/polls/pinned/(:num)'] = 'backend/PollsController/pinned/$1';
$route['panel/poll/(:num)'] = 'backend/PollsController/polls/$1';
$route['panel/polls/authors'] = 'backend/PollsController/authors';

// POLL COMMENTS
$route['panel/poll-comments/update/(:num)'] = 'backend/PollsController/comments_update/$1';
$route['panel/poll-comments/delete'] = 'backend/PollsController/comments_delete';
$route['panel/poll-comments/delete/(:num)'] = 'backend/PollsController/comments_delete/$1';
$route['panel/poll-comments/datatable/(:num)'] = 'backend/PollsController/comments_datatable/$1';
$route['panel/poll-comments/isactive/(:num)'] = 'backend/PollsController/comments_isactive/$1';

// POLL VOTES
$route['panel/poll-votes/datatable/(:num)'] = 'backend/PollsController/votes_datatable/$1';
$route['panel/poll-votes/export-to-excel/(:num)'] = 'backend/PollsController/votes_export_to_excel/$1';
$route['panel/poll-votes/add-fake-votes/(:num)'] = 'backend/PollsController/add_fake_votes/$1';

// POLL STATISTICS
$route['panel/poll-statistic/(:num)'] = 'backend/PollsController/poll_statistic/$1';
