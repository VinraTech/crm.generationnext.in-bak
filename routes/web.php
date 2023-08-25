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
/*
Route::get('/', function () {
    echo "Coming Soon.";
    //return view('welcome');
}); */

/* Route::match(['get', 'post'], '/', 'AdminController@login'); */
Route::match(['get', 'post'], '/s/2018/admin', 'AdminController@login');
Route::match(['get', 'post'], '/s/admin/logout', 'AdminController@logout');
Route::match(['get', 'post'], '/verify-email', 'EmployeeController@verifyEmpemail');
Route::match(['get', 'post'], '/reset-password', 'EmployeeController@resetPassword');
Route::match(['get', 'post'], '/s/admin/dashboard', 'AdminController@dashboard');
//Admin Middleware
Route::group(["middleware" => ['admin']], function () {
    //Employee TeamId Create
	Route::match(['get', 'post'], '/s/admin/empTeamId', 'EmployeeController@empTeamId');
	
	//Employee Profile Routes 
	
	Route::match(['get', 'post'], '/s/admin/profile', 'AdminController@profile');
	Route::match(['get', 'post'], '/s/admin/settings', 'AdminController@settings');
	Route::match(['get', 'post'], '/s/admin/change-picture', 'AdminController@changeAdminLogo');
	Route::match(['get', 'post'], '/s/admin/update-password', 'AdminController@changeAdminPassword');
	Route::match(['get', 'post'], '/s/admin/checkAdminPassword', 'AdminController@checkAdminPassword');
	Route::match(['get', 'post'], '/s/admin/status', 'AdminController@status');
	
	//Employee Routes
	Route::match(['get', 'post'], '/s/admin/employees', 'EmployeeController@employees');
	Route::match(['get', 'post'], '/s/admin/status', 'AdminController@status');
	Route::match(['get', 'post'], '/s/admin/add-edit-employee/{id?}', 'EmployeeController@addeditEmployee');
	Route::match(['get', 'post'], '/s/admin/checkEmployeeEmail', 'EmployeeController@checkEmployeeEmail');
	Route::match(['get', 'post'], '/s/admin/delete-employee/{id}', 'EmployeeController@deleteEmployee');
	Route::match(['get', 'post'], '/s/admin/get-emp-details', 'EmployeeController@getEmpDetails');
	Route::match(['get', 'post'], '/s/admin/get-cities', 'EmployeeController@getCities');
	Route::match(['get', 'post'], '/s/admin/add-employee-target/{id}', 'EmployeeController@addEmpTarget');
	Route::match(['get', 'post'], '/s/admin/designation', 'EmployeeController@viewDesignation');
	Route::match(['get', 'post'], '/s/admin/add-edit-designation/{id?}', 'EmployeeController@addeditDesignation');
	Route::match(['get', 'post'], '/s/admin/delete-designation/{id}', 'EmployeeController@deleteDesignation');

	//Roles Routes
	Route::match(['get', 'post'], 's/admin/update-role/{id}', 'EmployeeController@updateRole');
	//Channel Partner Routes
	Route::match(['get', 'post'], '/s/admin/partners', 'ChannelPartnerController@channelpartners');
	Route::match(['get', 'post'], '/s/admin/add-edit-partner/{id?}', 'ChannelPartnerController@addeditPartner');
	Route::match(['get', 'post'], '/s/admin/checkPartnerEmail', 'ChannelPartnerController@checkPartnerEmail');
	Route::match(['get', 'post'], '/s/admin/get-partner-details', 'ChannelPartnerController@getpartnerDetails');
	
	//Lead Routes
	Route::match(['get', 'post'], '/s/admin/leads', 'LeadController@leads');
	Route::match(['get', 'post'], '/s/admin/download-lead-zip/{leadid}', 'LeadController@downloadLeadFiles');
	Route::match(['get', 'post'], '/s/admin/add-lead', 'LeadController@addLead');
	Route::match(['get', 'post'], '/s/admin/edit-lead/{leadid}', 'LeadController@editLead');
	Route::match(['get', 'post'], '/s/admin/get-lead-details', 'LeadController@getLeadDetails');
	Route::match(['get', 'post'], '/s/admin/allocate-lead/{leadid}', 'LeadController@allocateLead');
	Route::match(['get', 'post'], '/s/admin/append-indirect-details', 'LeadController@appendIndirectDetails');
	Route::match(['get', 'post'], '/s/admin/download-thread-file/{leadid}', 'LeadController@downloadThreadFile');
	Route::match(['get', 'post'], '/s/admin/append-lead-status-data', 'LeadController@appendLeadStatusData');
	Route::match(['get', 'post'], '/s/admin/send-reminder-email/{leadid}', 'LeadController@SendReminderEmail');
	Route::match(['get', 'post'], '/s/admin/append-allocation-employees', 'LeadController@appendAllocationEmployees');

	//UnAllocated Leads Route
	Route::match(['get', 'post'], '/s/admin/unallocated-leads', 'LeadController@unAllocatedLeads');
	//Allocated Leads Leads Route
	Route::match(['get', 'post'], '/s/admin/allocated-leads', 'LeadController@allocatedLeads');
	//Closed Leads
	Route::match(['get', 'post'], '/s/admin/closed-leads', 'LeadController@closedLeads');
	//Inactive Leads
	Route::match(['get', 'post'], '/s/admin/inactive-leads', 'LeadController@inactiveLeads');
	//Lead Statuses Routes
	Route::match(['get', 'post'], '/s/admin/lead-status', 'MasterController@allleadStatus');
	Route::match(['get', 'post'], '/s/admin/add-edit-lead-status/{id?}', 'MasterController@addEditLeadStatus');

	//Client Routes
	Route::match(['get', 'post'], '/s/admin/clients', 'ClientController@clients');
	Route::match(['get', 'post'], '/s/admin/add-edit-client/{id?}', 'ClientController@addeditClient');
	Route::match(['get', 'post'], '/s/admin/CheckClientPan', 'ClientController@CheckClientPan');
	Route::match(['get', 'post'], '/s/admin/export-clients', 'ClientController@exportClients');
	//File Routes
	Route::match(['get', 'post'], '/s/admin/files', 'FileController@files');
	Route::match(['get', 'post'], '/s/admin/add-file', 'FileController@addFile');
	Route::match(['get', 'post'], '/s/admin/generate-file/{clientid}', 'FileController@generatefile');
	Route::match(['get', 'post'], '/s/admin/edit-generated-file/{fileid}', 'FileController@editGeneratedFile');
	Route::match(['get', 'post'], '/s/admin/append-crm', 'FileController@appendCrm');
	Route::match(['get', 'post'], '/s/admin/create-applicants/{fileid}', 'FileController@createApplicants');
	Route::match(['get', 'post'], '/s/admin/add-individual-applicant/{fileid}/{applicantid?}', 'FileController@addIndividualApplicant');
	Route::match(['get', 'post'], '/s/admin/add-non-individual-applicant/{fileid}/{applicantid?}', 'FileController@addNonIndividualApplicant');
	Route::match(['get', 'post'], '/s/admin/append-occupation-form', 'FileController@appendOccupationForm');
	Route::match(['get', 'post'], '/s/admin/add-property-detail/{fileid}/{propertyid?}', 'FileController@addPropertyDetail');
	Route::match(['get', 'post'], '/s/admin/add-asset-detail/{fileid}/{assetid?}', 'FileController@addAssetDetail');
	Route::match(['get', 'post'], '/s/admin/get-company-models', 'FileController@getCompanyModels');
	Route::match(['get', 'post'], '/s/admin/add-reference/{fileid}/{propertyid?}', 'FileController@addReference');
	Route::match(['get', 'post'], '/s/admin/append-files', 'FileController@appendFiles');
	Route::match(['get', 'post'], '/s/admin/get-file-details', 'FileController@getfileDetails');
	Route::match(['get', 'post'], '/s/admin/add-facility-requirement/{fileid}', 'FileController@addFacilityRequirement');
	Route::match(['get', 'post'], '/s/admin/add-checklist/{fileid}', 'FileController@addcheckList');
	Route::match(['get', 'post'], '/s/admin/add-loan-details/{fileid}', 'FileController@addLoanDetails');
	Route::match(['get', 'post'], '/s/admin/add-bank-details/{fileid}', 'FileController@addBankDetails');
	Route::match(['get', 'post'], '/s/admin/add-banker', 'FileController@showBanker');
	//delete Applicant
	Route::match(['get', 'post'], '/s/admin/delete-applicant/{type}/{id}', 'FileController@deleteApplicant');
	Route::match(['get', 'post'], '/s/admin/delete-property/{propertyid}', 'FileController@deleteProperty');
	Route::match(['get', 'post'], '/s/admin/delete-asset/{assetid}', 'FileController@deleteAsset');
	Route::match(['get', 'post'], '/s/admin/destroy-file/{fileid}', 'FileController@destroyFile');
	//Update Financial Details


	Route::match(['get', 'post'], '/s/admin/update-applicant-financial-details', 'FileController@updateApplicantFinancialDetails');

	Route::match(['get', 'post'], '/s/admin/update-eligibility-details/{fileid}', 'FileController@updateEligibilityDetails');
	Route::match(['get', 'post'], '/s/admin/download-eligibility/{fileid}', 'FileController@downloadEligibilityFile');
	Route::match(['get', 'post'], '/s/admin/update-valuations', 'FileController@updateValuations');
	Route::match(['get', 'post'], '/s/admin/update-asset-valuations', 'FileController@updateAssetValuations');
	Route::match(['get', 'post'], '/s/admin/calculate-installments', 'FileController@calculateInstallments');

	//Bank Files
	Route::match(['get', 'post'], '/s/admin/append-file-status-form', 'FileController@appendFileStatusForm');
	Route::match(['get', 'post'], '/s/admin/update-bank-file-status', 'FileController@updateBankFileStatus');
	Route::match(['get', 'post'], '/s/admin/get-bank-file-history', 'FileController@getBankFileHistory');

	//Banker Routes
	Route::match(['get', 'post'], '/s/admin/banks', 'MasterController@banks');
	Route::match(['get', 'post'], '/s/admin/add-edit-bank/{id?}', 'MasterController@addEditBank');
	Route::match(['get', 'post'], '/s/admin/delete-banker/{id?}', 'MasterController@deleteBanker');

	//Bank Routes
	Route::match(['get', 'post'], '/s/admin/bank', 'MasterController@bank');
	Route::match(['get', 'post'], '/s/admin/add-edit-banks/{id?}', 'MasterController@addEditBanks');
	Route::match(['get', 'post'], '/s/admin/delete-banks/{id?}', 'MasterController@deleteBanks');

	// Pending approvals
	Route::match(['get', 'post'], '/s/admin/pending-approvals', 'FileController@pendingApprovals');
	Route::match(['get', 'post'], '/s/admin/approve-move-file/{approvalid}', 'FileController@approveAndMove');
	Route::match(['get', 'post'], '/s/admin/decline-move-file/{approvalid}', 'FileController@declineAndMove');
	Route::match(['get', 'post'], '/s/admin/wip-move-file/{approvalid}', 'FileController@wipAndMove');

	//Disbursement Details
	Route::match(['get', 'post'], '/s/admin/update-disbursement-details/{fileid}', 'FileController@updateDisbursement');
	Route::match(['get', 'post'], '/s/admin/disbursement-files', 'FileController@disbursementFiles');
	Route::match(['get', 'post'], '/s/admin/partially-disbursement-files', 'FileController@partiallydisbursementFiles');
	//Export Routes
	Route::match(['get'], '/s/admin/export-leads', 'ReportsController@LeadsExport');
	Route::match(['get','post'], '/s/admin/export-file-history/{fileid?}', 'FileController@exportDisFileHistory');

	//Schedules Routes
	Route::match(['get', 'post'], '/s/admin/schedules', 'ScheduleController@schedules');
	//Quick Reminder Routes
	Route::match(['get', 'post'], '/s/admin/quick-reminder', 'LeadController@quickReminder');
	Route::match(['get', 'post'], '/s/admin/append-reminder-details', 'LeadController@appendReminderDetails');
	// Hours Difference
	Route::match(['get', 'post'], '/s/admin/hours-difference', 'LeadController@hoursDifference');
	//Notifications Route
	Route::match(['get', 'post'], '/s/admin/notifications', 'NotificationController@notifications');
	Route::match(['get', 'post'], '/s/admin/add-edit-notification/{notifyid?}', 'NotificationController@addEditNotification');
	Route::match(['get', 'post'], '/s/admin/view-notification/{notifyid}', 'NotificationController@viewNotification');

	//Export Routes
	Route::match(['get', 'post'], '/s/admin/export-files/{type}', 'ReportsController@exportfiles');
	Route::match(['get', 'post'], '/s/admin/export-report', 'ReportsController@exportreport');
	
	Route::match(['get', 'post'], '/s/admin/export-partially-files/{fileid?}', 'ReportsController@exportPartialDisFileHistory');

	//Comapny Routes
	Route::match(['get', 'post'], '/s/admin/company-models', 'ClientController@companyModels');
	Route::match(['get', 'post'], '/s/admin/add-edit-model/{id?}', 'ClientController@addEditModel');
	//Move to Declined
	Route::match(['get', 'post'], '/s/admin/move-to-declined/{fileid}', 'FileController@movetoDeclined');
	//Search File Route
	Route::match(['get', 'post'], '/s/admin/search-files', 'FileController@searchFiles');
	Route::match(['get', 'post'], '/s/admin/get-file-summary', 'FileController@getFileSummary');

	//File Reports
	Route::match(['get', 'post'], '/s/admin/file-reports', 'ReportsController@fileReports');
	Route::match(['get', 'post'], '/s/admin/file-report-results', 'ReportsController@filereportResults');
	Route::match(['get', 'post'], '/s/admin/append-months', 'ReportsController@appendMonths');
});

//Update Lead Status Route
	Route::match(['get', 'post'], '/s/admin/update-lead-status/{leadid}', 'LeadController@updateLeadStatus');
//Reminder Routes
	Route::match(['get', 'post'], '/s/admin/lead-tracking-reminder', 'LeadController@leadtrackingReminder');

//developer
Route::match(['get', 'post'], '/s/admin/superadmin/173a11', 'ReportsController@superadmin');