<?php
defined('BASEPATH') or exit('No direct script access allowed');
$route['login'] = 'frontend/FrontendAuthController/login';
$route['register'] = 'frontend/FrontendAuthController/register';
$route['refresh-token'] = 'frontend/FrontendAuthController/refresh';
$route['google-login'] = 'frontend/FrontendAuthController/googleLogin';
$route['facebook-login'] = 'frontend/FrontendAuthController/facebookLogin';
$route['twitter-login'] = 'frontend/FrontendAuthController/twitterLogin';
$route['me'] = 'frontend/FrontendAuthController/me';
$route['forgot-password'] = 'frontend/FrontendAuthController/forgot_password_token';
$route['forgot-password-reset'] = 'frontend/FrontendAuthController/forgot_password_reset';


// USERS
$route['users'] = 'frontend/FrontendUsersController/users';
$route['users/cities'] = 'frontend/FrontendUsersController/cities';
$route['users/districts'] = 'frontend/FrontendUsersController/districts';
$route['users/districts/(:any)'] = 'frontend/FrontendUsersController/districts/$1';
$route['users/neighborhoods'] = 'frontend/FrontendUsersController/neighborhoods';
$route['users/neighborhoods/(:any)'] = 'frontend/FrontendUsersController/neighborhoods/$1';
$route['users/quarters'] = 'frontend/FrontendUsersController/quarters';
$route['users/quarters/(:any)'] = 'frontend/FrontendUsersController/quarters/$1';
$route['follow-user'] = 'frontend/FrontendUsersController/follow_user';
$route['unfollow-user'] = 'frontend/FrontendUsersController/unfollow_user';
$route['profile/(:any)'] = 'frontend/FrontendUsersController/profile/$1';
$route['statistics/(:any)'] = 'frontend/FrontendUsersController/statistics/$1';
$route['user-polls/(:any)'] = 'frontend/FrontendUsersController/user_polls/$1';
$route['user-followings/(:any)'] = 'frontend/FrontendUsersController/user_followings/$1';
$route['user-followers/(:any)'] = 'frontend/FrontendUsersController/user_followers/$1';
$route['user-comments/(:any)'] = 'frontend/FrontendUsersController/user_comments/$1';


// HOME
$route['settings'] = 'frontend/FrontendHomeController/settings';
$route['settings/(:num)'] = 'frontend/FrontendHomeController/settings/$1';
$route['menus'] = 'frontend/FrontendHomeController/menus';
$route['categories'] = 'frontend/FrontendHomeController/categories';
$route['featured-polls'] = 'frontend/FrontendHomeController/featured_polls';
$route['featured-users'] = 'frontend/FrontendHomeController/featured_users';
$route['popular-categories'] = 'frontend/FrontendHomeController/popular_categories';
$route['follow-users'] = 'frontend/FrontendHomeController/follow_users';
$route['categories-list'] = 'frontend/FrontendCategoriesController/categories';
$route['categories/(:any)'] = 'frontend/FrontendHomeController/categories/$1';
$route['slides'] = 'frontend/FrontendHomeController/slides';
$route['pages'] = 'frontend/FrontendHomeController/pages';
$route['counters'] = 'frontend/FrontendHomeController/counters';

// PAGE
$route['page/(:any)'] = 'frontend/FrontendPageController/page/$1';

// POLLS
$route['polls-percentage'] = 'frontend/FrontendPollController/polls_percentage';
$route['polls-list'] = 'frontend/FrontendPollController/polls';
$route['poll/(:any)'] = 'frontend/FrontendPollController/polls/$1';
$route['poll-voters/(:any)'] = 'frontend/FrontendPollController/poll_voters/$1';
$route['polls/save'] = 'frontend/FrontendPollController/save';
$route["vote-poll"] = "frontend/FrontendPollController/vote";
$route["send-comment"] = "frontend/FrontendPollController/comment";
$route["add-sms-list"] = "frontend/FrontendPollController/add_sms_queue";
$route["queues"] = "frontend/FrontendPollController/queues";
$route["send-sms"] = "frontend/FrontendPollController/send_sms";

// MY PROFILE
$route['update-profile'] = 'frontend/FrontendProfileController/update';
$route['my-polls'] = 'frontend/FrontendProfileController/my_polls';
$route['my-statistics'] = 'frontend/FrontendProfileController/statistics';
$route['my-poll/(:any)'] = 'frontend/FrontendProfileController/my_poll/$1';
$route['my-followings'] = 'frontend/FrontendProfileController/my_followings';
$route['my-followers'] = 'frontend/FrontendProfileController/my_followers';
$route['my-comments'] = 'frontend/FrontendProfileController/my_comments';
$route['delete-my-comment/(:num)'] = 'frontend/FrontendProfileController/delete_my_comment/$1';
