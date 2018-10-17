<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "heel";//view('welcome');
});
/*
  Accessing uploaded files
*/
Route::get('storage/profiles/{filename}', function ($filename)
{
    $path = storage_path('/app/profiles/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Route::get('storage/logo/{filename}', function ($filename)
{
    $path = storage_path('/app/logos/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/event_feautured_image/{filename}', function ($filename)
{
    $path = storage_path('/app/eventFeaturedImages/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/qr_code/{filename}', function ($filename)
{
    $path = storage_path('/app/qrCodes/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/clear', function () {
    $exitCode = Artisan::call('config:cache');
    return 'hey'.$exitCode;

    //
});
Route::get('/migrate', function () {
    $exitCode = Artisan::call('migrate');
    return 'hey'.$exitCode;

    //
});

/* Authentication Router */
Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
Route::post('authenticate/refresh', 'AuthenticateController@refreshToken');
Route::post('authenticate/invalidate', 'AuthenticateController@deauthenticate');


//Accounts
Route::post('/accounts/registration', "AccountController@registration");
Route::post('/accounts/create', "AccountController@create");
Route::post('/accounts/retrieve', "AccountController@retrieve");
Route::post('/accounts/update', "AccountController@update");
Route::post('/accounts/update_verification', "AccountController@updateByVerification");
Route::post('/accounts/update_action', "AccountController@updateAction");
Route::post('/accounts/update_active_semester', "AccountController@updateActiveSemester");
Route::post('/accounts/delete', "AccountController@delete");
Route::get('/accounts/test', 'AccountController@test');
Route::post('/accounts/mail', 'AccountController@testMail');
Route::post('/accounts/verify', 'AccountController@verify');
Route::post('/accounts/request_reset', 'AccountController@requestReset');


//Account Informations
Route::post('/account_informations/create', "AccountInformationController@create");
Route::post('/account_informations/retrieve', "AccountInformationController@retrieve");
Route::post('/account_informations/update', "AccountInformationController@update");
Route::post('/account_informations/delete', "AccountInformationController@delete");
Route::get('/account_informations/test', 'AccountInformationController@test');



//Account Degrees
Route::post('/account_degrees/create', "AccountDegreeController@create");
Route::post('/account_degrees/retrieve', "AccountDegreeController@retrieve");
Route::post('/account_degrees/update', "AccountDegreeController@update");
Route::post('/account_degrees/delete', "AccountDegreeController@delete");
Route::get('/account_degrees/test', 'AccountDegreeController@test');

//Account Degrees
Route::post('/account_work_experiences/create', "AccountWorkExperienceController@create");
Route::post('/account_work_experiences/retrieve', "AccountWorkExperienceController@retrieve");
Route::post('/account_work_experiences/update', "AccountWorkExperienceController@update");
Route::post('/account_work_experiences/delete', "AccountWorkExperienceController@delete");
Route::get('/account_work_experiences/test', 'AccountWorkExperienceController@test');


//Account Profiles
Route::post('/account_profiles/create', "AccountProfileController@create");
Route::post('/account_profiles/retrieve', "AccountProfileController@retrieve");
Route::post('/account_profiles/update', "AccountProfileController@update");
Route::post('/account_profiles/delete', "AccountProfileController@delete");
Route::get('/account_profiles/test', 'AccountProfileController@test');



//Account Profiles
Route::post('/account_profiles/create', "AccountProfileController@create");
Route::post('/account_profiles/retrieve', "AccountProfileController@retrieve");
Route::post('/account_profiles/update', "AccountProfileController@update");
Route::post('/account_profiles/delete', "AccountProfileController@delete");
Route::get('/account_profiles/test', 'AccountProfileController@test');



//Account Semesters
Route::post('/account_semesters/create', "AccountSemesterController@create");
Route::post('/account_semesters/retrieve', "AccountSemesterController@retrieve");
Route::post('/account_semesters/update', "AccountSemesterController@update");
Route::post('/account_semesters/delete', "AccountSemesterController@delete");
Route::get('/account_semesters/test', 'AccountSemesterController@test');


//Schools
Route::post('/schools/create', "SchoolController@create");
Route::post('/schools/retrieve', "SchoolController@retrieve");
Route::post('/schools/update', "SchoolController@update");
Route::post('/schools/delete', "SchoolController@delete");
Route::get('/schools/test', 'SchoolController@test');


//Semesters
Route::post('/semesters/create', "SemesterController@create");
Route::post('/semesters/retrieve', "SemesterController@retrieve");
Route::post('/semesters/update', "SemesterController@update");
Route::post('/semesters/delete', "SemesterController@delete");
Route::get('/semesters/test', 'SemesterController@test');

//Courses
Route::post('/courses/create', "CourseController@create");
Route::post('/courses/retrieve', "CourseController@retrieve");
Route::post('/courses/update_active_course', "CourseController@updateActiveCourse");
Route::post('/courses/update', "CourseController@update");
Route::post('/courses/delete', "CourseController@delete");
Route::post('/courses/accounts', "CourseController@accounts");
Route::post('/courses/retrieve_by_attendance', "CourseController@retrieveByAttendance");
Route::get('/courses/test', 'CourseController@test');

//Courses
Route::post('/enrolled_courses/create', "EnrolledAccountController@create");
Route::post('/enrolled_courses/retrieve', "EnrolledAccountController@retrieve");
Route::post('/enrolled_courses/update', "EnrolledAccountController@update");
Route::post('/enrolled_courses/delete', "EnrolledAccountController@delete");
Route::get('/enrolled_courses/test', 'EnrolledAccountController@test');


//Grades Settings
Route::post('/grade_settings/create', "GradeSettingController@create");
Route::post('/grade_settings/retrieve', "GradeSettingController@retrieve");
Route::post('/grade_settings/update', "GradeSettingController@update");
Route::post('/grade_settings/delete', "GradeSettingController@delete");
Route::get('/grade_settings/test', 'GradeSettingController@test');
Route::post('/grade_settings/update_by_course', 'GradeSettingController@updateByCourse');

//Tests
Route::post('/tests/create', "TestController@create");
Route::post('/tests/retrieve', "TestController@retrieve");
Route::post('/tests/retrieve_by_course', "TestController@retrieveByCourse");
Route::post('/tests/update', "TestController@update");
Route::post('/tests/delete', "TestController@delete");
Route::get('/tests/test', 'TestController@test');

//Questions
Route::post('/questions/create', "QuestionController@create");
Route::post('/questions/retrieve', "QuestionController@retrieve");
Route::post('/questions/retrieve_on_take', "QuestionController@retrieveOnTake");
Route::post('/questions/update', "QuestionController@update");
Route::post('/questions/delete', "QuestionController@delete");
Route::get('/questions/test', 'QuestionController@test');

//Question Options
Route::post('/question_options/create', "QuestionOptionController@create");
Route::post('/question_options/retrieve', "QuestionOptionController@retrieve");
Route::post('/question_options/update', "QuestionOptionController@update");
Route::post('/question_options/delete', "QuestionOptionController@delete");
Route::get('/question_options/test', 'QuestionOptionController@test');


//Answers
Route::post('/answers/create', "AnswerController@create");
Route::post('/answers/retrieve', "AnswerController@retrieve");
Route::post('/answers/update', "AnswerController@update");
Route::post('/answers/delete', "AnswerController@delete");
Route::get('/answers/test', 'AnswerController@test');

//Resources
Route::post('/resources/create', "ResourceController@create");
Route::post('/resources/retrieve', "ResourceController@retrieve");
Route::post('/resources/retrieve_by_course', "ResourceController@retrieveByCourse");
Route::post('/resources/retrieve_student', "ResourceController@retrieveStudent");
Route::post('/resources/update', "ResourceController@update");
Route::post('/resources/delete', "ResourceController@delete");
Route::get('/resources/test', 'ResourceController@test');

//Resource Viewers
Route::post('/resource_viewers/create', "ResourceViewerController@create");
Route::post('/resource_viewers/retrieve', "ResourceViewerController@retrieve");
Route::post('/resource_viewers/update', "ResourceViewerController@update");
Route::post('/resource_viewers/delete', "ResourceViewerController@delete");
Route::get('/resource_viewers/test', 'ResourceViewerController@test');


//Calendar
Route::post('/calendars/create', "CalendarController@create");
Route::post('/calendars/retrieve', "CalendarController@retrieve");
Route::post('/calendars/retrieve_week', "CalendarController@retrieveByWeek");
Route::post('/calendars/update', "CalendarController@update");
Route::post('/calendars/delete', "CalendarController@delete");
Route::get('/calendars/test', 'CalendarController@test');

//Announcements
Route::post('/announcements/create', "AnnouncementController@create");
Route::post('/announcements/retrieve', "AnnouncementController@retrieve");
Route::post('/announcements/retrieve_student', "AnnouncementController@retrieveStudent");
Route::post('/announcements/update', "AnnouncementController@update");
Route::post('/announcements/delete', "AnnouncementController@delete");
Route::get('/announcements/test', 'AnnouncementController@test');

//Topics
Route::post('/topics/create', "TopicController@create");
Route::post('/topics/retrieve', "TopicController@retrieve");
Route::post('/topics/update', "TopicController@update");
Route::post('/topics/delete', "TopicController@delete");
Route::get('/topics/test', 'TopicController@test');


//Topic Comments
Route::post('/topic_comments/create', "TopicCommentController@create");
Route::post('/topic_comments/retrieve', "TopicCommentController@retrieve");
Route::post('/topic_comments/update', "TopicCommentController@update");
Route::post('/topic_comments/delete', "TopicCommentController@delete");
Route::get('/topic_comments/test', 'TopicCommentController@test');

//Topic Comment Replies
Route::post('/topic_comment_replies/create', "TopicCommentReplyController@create");
Route::post('/topic_comment_replies/retrieve', "TopicCommentReplyController@retrieve");
Route::post('/topic_comment_replies/update', "TopicCommentReplyController@update");
Route::post('/topic_comment_replies/delete', "TopicCommentReplyController@delete");
Route::get('/topic_comment_replies/test', 'TopicCommentReplyController@test');


//Topic Comment Stars
Route::post('/topic_comment_stars/create', "TopicCommentStarController@create");
Route::post('/topic_comment_stars/retrieve', "TopicCommentStarController@retrieve");
Route::post('/topic_comment_stars/update', "TopicCommentStarController@update");
Route::post('/topic_comment_stars/delete', "TopicCommentStarController@delete");
Route::get('/topic_comment_stars/test', 'TopicCommentStarController@test');


//Attendances
Route::post('/attendances/create', "AttendanceController@create");
Route::post('/attendances/retrieve', "AttendanceController@retrieve");
Route::post('/attendances/generate_qr_code', 'AttendanceController@generateQrCode');
Route::post('/attendances/update', "AttendanceController@update");
Route::post('/attendances/delete', "AttendanceController@delete");
Route::get('/attendances/test', 'AttendanceController@test');


//Attendances
Route::post('/attendance_accounts/create', "AttendanceAccountController@create");
Route::post('/attendance_accounts/retrieve', "AttendanceAccountController@retrieve");
Route::post('/attendance_accounts/update', "AttendanceAccountController@update");
Route::post('/attendance_accounts/delete', "AttendanceAccountController@delete");
Route::get('/attendance_accounts/test', 'AttendanceAccountController@test');


//Notification Settings
Route::post('/notification_settings/create', "NotificationSettingController@create");
Route::post('/notification_settings/retrieve', "NotificationSettingController@retrieve");
Route::post('/notification_settings/update', "NotificationSettingController@update");
Route::post('/notification_settings/delete', "NotificationSettingController@delete");
Route::get('/notification_settings/test', 'NotificationSettingController@test');

//Reports
Route::post('/reports/retrieve', "ReportController@retrieve");

//Notifications
Route::post('/notifications/create', "NotificationController@create");
Route::post('/notifications/retrieve', "NotificationController@retrieve");
Route::post('/notifications/update', "NotificationController@update");
Route::post('/notifications/delete', "NotificationController@delete");
Route::get('/notifications/test', 'NotificationController@test');


// Google Spreadsheets
Route::get('/gsheets/create', 'GoogleSheetController@generate');
Route::post('/gsheets/auth', 'GoogleSheetController@getAuthUrl');
Route::post('/gsheets/save_token', 'GoogleSheetController@setAccessToken');
Route::get('/gsheets/sample', 'GoogleSheetController@sample');


//Discussion Posts
Route::post('/discussion_posts/create', "DiscussionPostController@create");
Route::post('/discussion_posts/retrieve', "DiscussionPostController@retrieve");
Route::post('/discussion_posts/update', "DiscussionPostController@update");
Route::post('/discussion_posts/delete', "DiscussionPostController@delete");
Route::get('/discussion_posts/test', 'DiscussionPostController@test');
Route::post('/discussion_posts/search', 'DiscussionPostController@search');

//Discussion Answer
Route::post('/discussion_answers/create', "DiscussionAnswerController@create");
Route::post('/discussion_answers/retrieve', "DiscussionAnswerController@retrieve");
Route::post('/discussion_answers/update', "DiscussionAnswerController@update");
Route::post('/discussion_answers/delete', "DiscussionAnswerController@delete");
Route::get('/discussion_answers/test', 'DiscussionAnswerController@test');

//Discussion Reports
Route::post('/discussion_reports/create', "DiscussionReportController@create");
Route::post('/discussion_reports/retrieve', "DiscussionReportController@retrieve");
Route::post('/discussion_reports/update', "DiscussionReportController@update");
Route::post('/discussion_reports/delete', "DiscussionReportController@delete");
Route::get('/discussion_reports/test', 'DiscussionReportController@test');

//Discussion Reports
Route::post('/discussion_reactions/create', "DiscussionReactionController@create");
Route::post('/discussion_reactions/retrieve', "DiscussionReactionController@retrieve");
Route::post('/discussion_reactions/update', "DiscussionReactionController@update");
Route::post('/discussion_reactions/delete', "DiscussionReactionController@delete");
Route::get('/discussion_reactions/test', 'DiscussionReactionController@test');


//Planners
Route::post('/planners/create', "PlannerController@create");
Route::post('/planners/retrieve_teacher', "PlannerController@retrieveByTeacher");
Route::post('/planners/update', "PlannerController@update");
Route::post('/planners/delete', "PlannerController@delete");
Route::get('/planners/test', 'PlannerController@test');


//Planners
Route::post('/planner_others/create', "PlannerOtherController@create");
Route::post('/planner_others/retrieve', "PlannerOtherController@retrieve");
Route::post('/planner_others/update', "PlannerOtherController@update");
Route::post('/planner_others/delete', "PlannerOtherController@delete");
Route::get('/planner_others/test', 'PlannerOtherController@test');


//Organizations
Route::post('/organizations/create', "OrganizationController@create");
Route::post('/organizations/retrieve', "OrganizationController@retrieve");
Route::post('/organizations/retrieve_my_affiliates', "OrganizationController@retrieveMyAffiliates");
Route::post('/organizations/update', "OrganizationController@update");
Route::post('/organizations/update_active_org', 'OrganizationController@updateActiveOrg');
Route::post('/organizations/delete', "OrganizationController@delete");
Route::get('/organizations/test', 'OrganizationController@test');

//Organization Logos
Route::post('/organization_logos/create', "OrganizationLogoController@create");
Route::post('/organization_logos/retrieve', "OrganizationLogoController@retrieve");
Route::post('/organization_logos/update', "OrganizationLogoController@update");
Route::post('/organization_logos/delete', "OrganizationLogoController@delete");
Route::get('/organization_logos/test', 'OrganizationLogoController@test');

//Organization Members
Route::post('/organization_members/create', "OrganizationMemberController@create");
Route::post('/organization_members/retrieve', "OrganizationMemberController@retrieve");
Route::post('/organization_members/update', "OrganizationMemberController@update");
Route::post('/organization_members/delete', "OrganizationMemberController@delete");
Route::get('/organization_members/test', 'OrganizationMemberController@test');

//Events
Route::post('/events/create', "EventController@create");
Route::post('/events/retrieve', "EventController@retrieve");
Route::post('/events/update', "EventController@update");
Route::post('/events/delete', "EventController@delete");
Route::get('/events/test', 'EventController@test');


//Events
Route::post('/event_featured_images/create', "EventFeaturedImageController@create");
Route::post('/event_featured_images/retrieve', "EventFeaturedImageController@retrieve");
Route::post('/event_featured_images/update', "EventFeaturedImageController@update");
Route::post('/event_featured_images/delete', "EventFeaturedImageController@delete");
Route::get('/event_featured_images/test', 'EventFeaturedImageController@test');


//Events
Route::post('/event_tickets/create', "EventTicketController@create");
Route::post('/event_tickets/retrieve', "EventTicketController@retrieve");
Route::post('/event_tickets/retrieve_my_tickets', "EventTicketController@retrieveMyTickets");
Route::post('/event_tickets/retrieve_by_code', "EventTicketController@retrieveByCode");
Route::post('/event_tickets/retrieve_by_attendees', "EventTicketController@retrieveByAttendees");
Route::post('/event_tickets/update', "EventTicketController@update");
Route::post('/event_tickets/delete', "EventTicketController@delete");
Route::get('/event_tickets/test', 'EventTicketController@test');

//Events
Route::post('/comments/create', "CommentController@create");
Route::post('/comments/retrieve', "CommentController@retrieve");
Route::post('/comments/update', "CommentController@update");
Route::post('/comments/delete', "CommentController@delete");
Route::get('/comments/test', 'CommentController@test');

//Events
Route::post('/comment_replies/create', "CommentReplyController@create");
Route::post('/comment_replies/retrieve', "CommentReplyController@retrieve");
Route::post('/comment_replies/update', "CommentReplyController@update");
Route::post('/comment_replies/delete', "CommentReplyController@delete");
Route::get('/comment_replies/test', 'CommentReplyController@test');


//Mobile Controller
Route::post('/mobile/v1/auth', "MobileController@auth");
Route::post('/mobile/v1/ticket_code', "EventTicketController@retrieveByCodeMobile");
Route::post('/mobile/v1/ticket_update', "EventTicketController@updateByMobile");
Route::post('/mobile/v1/attendance_code', 'AttendanceAccountController@retrieveByCodeMobile');
Route::post('/mobile/v1/attendance_create', 'AttendanceAccountController@createByMobile');