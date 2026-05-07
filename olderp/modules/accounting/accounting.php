<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Accounting and Bookkeeping
Description: Accounting is the process of recording and tracking financial statements to see the financial health of an entity.
Version: 1.0.8
Requires at least: 2.3.*
Author: GreenTech Solutions
Author URI: https://codecanyon.net/user/greentech_solutions
 */

define('ACCOUNTING_MODULE_NAME', 'accounting');
define('ACCOUTING_MODULE_UPLOAD_FOLDER', module_dir_path(ACCOUNTING_MODULE_NAME, 'uploads'));
define('ACCOUTING_IMPORT_ITEM_ERROR', 'modules/accounting/uploads/import_item_error/');
define('ACCOUTING_ERROR', FCPATH);
define('ACCOUTING_EXPORT_XLSX', 'modules/accounting/uploads/export_xlsx/');

hooks()->add_action('app_admin_head', 'accounting_add_head_component');
hooks()->add_action('app_admin_footer', 'accounting_load_js');
hooks()->add_action('admin_init', 'accounting_module_init_menu_items');
hooks()->add_action('admin_init', 'accounting_permissions');
hooks()->add_action('after_invoice_added', 'acc_automatic_invoice_conversion');
hooks()->add_action('after_payment_added', 'acc_automatic_payment_conversion');
hooks()->add_action('after_expense_added', 'acc_automatic_expense_conversion');
hooks()->add_action('before_invoice_deleted', 'acc_delete_invoice_convert');
hooks()->add_action('before_payment_deleted', 'acc_delete_payment_convert');
hooks()->add_action('after_expense_deleted', 'acc_delete_expense_convert');
hooks()->add_action('invoice_status_changed', 'acc_invoice_status_changed');

define('ACCOUNTING_REVISION', 108);

/**
 * Register activation module hook
 */

register_activation_hook(ACCOUNTING_MODULE_NAME, 'accounting_module_activation_hook');

$CI = &get_instance();

$CI->load->helper(ACCOUNTING_MODULE_NAME . '/Accounting');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(ACCOUNTING_MODULE_NAME, [ACCOUNTING_MODULE_NAME]);

/**
 * spreadsheet online module activation hook
 */
function accounting_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * init add head component
 */
function accounting_add_head_component() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';

	}
	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_receipt_entry') === false)) {

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_payment_entry') === false)) {

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_contra_entry') === false)) {

		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/rp_') === false) || !(strpos($viewuri, 'admin/accounting/report') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/report.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/treegrid/css/jquery.treegrid.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/chart_of_accounts') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/chart_of_accounts.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, 'admin/accounting/reconcile') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/reconcile.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/reconcile_account') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/reconcile_account.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/transaction.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/import_xlsx_banking') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/dashboard') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/dashboard.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, 'admin/accounting/setting') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/setting.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/new_journal_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_receipt_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/new_receipt_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_payment_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/new_payment_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_contra_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/new_contra_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/journal_entry') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/manage_journal_entry.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
	}

	if (!(strpos($viewuri, 'admin/accounting/budget') === false) || !(strpos($viewuri, 'admin/accounting/user_register_view') === false)) {
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
		echo '<link href="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';

	}
}

/**
 * init add footer component
 */
function accounting_load_js() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	$mediaLocale = get_media_locale();

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=banking') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/banking.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=sales') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/sales.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=expenses') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/expenses.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=payslips') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/payslips.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=purchase') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/purchase_order.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=warehouse') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/warehouse.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction?group=stock_export') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/transaction/stock_export.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=general') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/general.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=mapping_setup') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/automatic_conversion.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=banking_rules') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/banking_rules.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/setting?group=account_type_details') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/account_type_details.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/new_rule') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/setting/new_rule.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/journal_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/journal_entry/manage.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, 'admin/accounting/account_group_master') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/group_master/manage.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}
	if (!(strpos($viewuri, 'admin/accounting/new_journal_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}
	if (!(strpos($viewuri, 'admin/accounting/new_receipt_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_payment_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}
	
	if (!(strpos($viewuri, 'admin/accounting/new_contra_entry') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/transaction') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/reconcile') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/reconcile/reconcile.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/rp_') === false) || !(strpos($viewuri, 'admin/accounting/report') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/treegrid/js/jquery.treegrid.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/js/report/main.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
	}

	if (!(strpos($viewuri, '/admin/accounting/dashboard') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}

	if (!(strpos($viewuri, 'admin/accounting/budget') === false)) {
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
		echo '<script src="' . module_dir_url(ACCOUNTING_MODULE_NAME, 'assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
	}
}

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function accounting_module_init_menu_items() {
	$CI = &get_instance();

	if (has_permission_new('accounting_ledger_entry', '', 'view') || has_permission_new('accounting_ledger_entry_SC', '', 'view') || has_permission_new('accounting_ledger_entry_SD', '', 'view') || has_permission_new('accounting_contra_entry', '', 'view') || has_permission_new('accounting_journal_entry', '', 'view') || has_permission_new('accounting_receipt_entry', '', 'view') || has_permission_new('accounting_payment_entry', '', 'view') || has_permission_new('accounting_voucher_register_entry', '', 'view') || has_permission_new('accounting_tcs_report', '', 'view') || has_permission_new('accounting_cd_report', '', 'view') || has_permission_new('accounting_report', '', 'view') || has_permission_new('accounting_trial_balance', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('accounting', [
			'name' => 'Accounts',
			'icon' => 'fa fa-usd',
			'position' => 5,
		]);

		
		if (has_permission_new('accounting_ledger_entry', '', 'view')  || has_permission_new('accounting_ledger_entry_SC', '', 'view') || has_permission_new('accounting_ledger_entry_SD', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'rp_general_ledger',
				'main_menu' => 'Accounts',
				'name' => "Account Ledger",
				'icon' => 'fa fa-handshake-o',
				'href' => admin_url('accounting/rp_general_ledger'),
				'position' => 1,
			]);
		}

		if (has_permission_new('accounting_journal_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_journal_entry',
				'name' => _l('journal_entry'),
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/new_journal_entry'),
				'position' => 2,
			]);
		}
		
		if (has_permission_new('accounting_contra_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'new_contra_entry',
				'name' => "Contra",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/new_contra_entry'),
				'position' => 3,
			]);
		}
		
		if (has_permission_new('accounting_receipt_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'new_receipt_entry',
				'name' => "Receipts",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/new_receipt_entry'),
				'position' => 4,
			]);
		}
		
		if (has_permission_new('accounting_payment_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'new_payment_entry',
				'name' => "Payment",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/new_payment_entry'),
				'position' => 5,
			]);
		}
		
		if (has_permission_new('accounting_voucher_register_entry', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'voucher_register',
				'name' => "Voucher Register",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/voucher_register'),
				'position' => 6,
			]);
		}
		
		if (has_permission_new('accounting_trial_balance', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_trial_balance',
				'name' => "Trial Balance",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/trial_balance'),
				'position' => 7,
			]);
		}
		
		if (has_permission_new('accounting_cd_report', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'credit_debit_report',
				'name' => "CD Report",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/credit_debit_report'),
				'position' => 8,
			]);
		}
		
		if (has_permission_new('accounting_tcs_report', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'tcs_data',
				'name' => "TCS Report",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/tcs_data'),
				'position' => 9,
			]);
		}
		if (has_permission_new('account_monitor_report', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'account_monitor_report',
				'name' => "Account Monitor",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/AccountMonitor'),
				'position' => 10,
			]);
		}
		
		/*if (has_permission('accounting_dashboard', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_dashboard',
				'name' => _l('dashboard'),
				'icon' => 'fa fa-home',
				'href' => admin_url('accounting/dashboard'),
				'position' => 1,
			]);
		}*/
		
		/*if (has_permission('accounting_dashboard', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'account_group_master',
				'name' => "Account Group",
				'icon' => 'fa fa-book',
				'href' => admin_url('accounting/account_group_master'),
				'position' => 2,
			]);
		}*/

		/*if (has_permission('accounting_transaction', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_transaction',
				'name' => _l('transaction'),
				'icon' => 'fa fa-handshake-o',
				'href' => admin_url('accounting/transaction?group=banking'),
				'position' => 3,
			]);
		}*/

		/*if (has_permission('accounting_transfer', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_transfer',
				'name' => _l('accounting_transfer'),
				'icon' => 'fa fa-exchange',
				'href' => admin_url('accounting/transfer'),
				'position' => 7,
			]);
		}*/

		/*if (has_permission('accounting_chart_of_accounts', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_chart_of_accounts',
				'name' => _l('chart_of_accounts'),
				'icon' => 'fa fa-list-ol',
				'href' => admin_url('accounting/chart_of_accounts'),
				'position' => 7,
			]);
		}*/

		/*if (has_permission('accounting_reconcile', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_reconcile',
				'name' => _l('reconcile'),
				'icon' => 'fa fa-sliders',
				'href' => admin_url('accounting/reconcile'),
				'position' => 8,
			]);
		}*/

		/*if (has_permission('accounting_budget', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_budget',
				'name' => _l('budget'),
				'icon' => 'fa fa-exchange',
				'href' => admin_url('accounting/budget'),
				'position' => 9,
			]);
		}*/

		/*if (has_permission('accounting_report', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_report',
				'name' => _l('accounting_report'),
				'icon' => 'fa fa-area-chart',
				'href' => admin_url('accounting/report'),
				'position' => 10,
			]);
		}*/

		/*if (has_permission('accounting_setting', '', 'view')) {
			$CI->app_menu->add_sidebar_children_item('accounting', [
				'slug' => 'accounting_setting',
				'name' => _l('setting'),
				'icon' => 'fa fa-cog',
				'href' => admin_url('accounting/setting?group=general'),
				'position' => 11,
			]);
		}*/
	}
}

/**
 * Init accounting module permissions in setup in admin_init hook
 */
function accounting_permissions() {

	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		
	];
	//register_staff_capabilities('accounting_dashboard', $capabilities, _l('accounting_dashboard'));

	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	//register_staff_capabilities('accounting_transaction', $capabilities, _l('accounting_transaction'));

	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => ['not_applicable' => true, 'name' => _l('permission_create')],
		'edit' => ['not_applicable' => true, 'name' => _l('permission_edit')],
		'delete' => ['not_applicable' => true, 'name' => _l('permission_delete')],
	];
	register_staff_capabilities('accounting_ledger_entry', $capabilities, 'Account Ledger All','Accounts');
	register_staff_capabilities('accounting_ledger_entry_SC', $capabilities, 'Account Ledger SUNDRY CREDITORS - RM','Accounts');
	register_staff_capabilities('accounting_ledger_entry_SD', $capabilities, 'Account Ledger SUNDRY DEBITORS','Accounts');
	
	
	
	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_journal_entry', $capabilities, _l('accounting_journal_entry'),'Accounts');
	
	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_contra_entry', $capabilities, _l('accounting_contra_entry'),'Accounts');
	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_payment_entry', $capabilities, _l('accounting_payments_entry'),'Accounts');
	$capabilities = [];
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('accounting_receipt_entry', $capabilities, _l('accounting_receipts_entry'),'Accounts');

    $capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => ['not_applicable' => true, 'name' => _l('permission_create')],
		'edit' => ['not_applicable' => true, 'name' => _l('permission_edit')],
		'delete' => ['not_applicable' => true, 'name' => _l('permission_delete')],
	];
	register_staff_capabilities('accounting_voucher_register_entry', $capabilities, 'Voucher Register','Accounts');
	
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => ['not_applicable' => true, 'name' => _l('permission_create')],
		'edit' => ['not_applicable' => true, 'name' => _l('permission_edit')],
		'delete' => ['not_applicable' => true, 'name' => _l('permission_delete')],
	];
	register_staff_capabilities('accounting_trial_balance', $capabilities, 'Trial Balance','Accounts');
	register_staff_capabilities('accounting_cd_report', $capabilities, 'CD Report','Accounts');
	register_staff_capabilities('account_monitor_report', $capabilities, 'Account Monitor','Accounts');
	
	$capabilities['capabilities'] = [
	    'view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')],
		'view' => _l('permission_view'),
		'create' => ['not_applicable' => true, 'name' => _l('permission_create')],
		'edit' => ['not_applicable' => true, 'name' => _l('permission_edit')],
		'delete' => ['not_applicable' => true, 'name' => _l('permission_delete')],
	];
	register_staff_capabilities('accounting_tcs_report', $capabilities, 'TCS Report','Accounts');
	
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	//register_staff_capabilities('accounting_transfer', $capabilities, _l('accounting_transfer'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	//register_staff_capabilities('accounting_chart_of_accounts', $capabilities, _l('accounting_chart_of_accounts'));
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
	];
	//register_staff_capabilities('accounting_reconcile', $capabilities, _l('accounting_reconcile'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
	];
	//register_staff_capabilities('accounting_budget', $capabilities, _l('accounting_budget'));
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
	];
	//register_staff_capabilities('accounting_report', $capabilities, _l('accounting_report'));

	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view'),
		'edit' => _l('permission_edit'),
	];
	//register_staff_capabilities('accounting_setting', $capabilities, _l('accounting_setting'));
}

function acc_automatic_invoice_conversion($invoice_id) {
	if ($invoice_id) {
		if (get_option('acc_invoice_automatic_conversion') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->automatic_invoice_conversion($invoice_id);
		}

	}

	return $invoice_id;
}

function acc_automatic_payment_conversion($payment_id) {
	if ($payment_id) {
		if (get_option('acc_payment_automatic_conversion') == 1 || get_option('acc_active_payment_mode_mapping') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');

			$CI->accounting_model->automatic_payment_conversion($payment_id);
		}

	}

	return $payment_id;
}

function acc_automatic_expense_conversion($expense_id) {
	if ($expense_id) {
		if (get_option('acc_expense_automatic_conversion') == 1 || get_option('acc_active_expense_category_mapping') == 1) {
			$CI = &get_instance();
			$CI->load->model('accounting/accounting_model');
			$CI->accounting_model->automatic_expense_conversion($expense_id);
		}

	}
	return $expense_id;
}

function acc_delete_invoice_convert($invoice_id) {
	if ($invoice_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_invoice_convert($invoice_id);

	}

	return $data;
}

function acc_delete_payment_convert($data) {
	if ($data['paymentid']) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($data['paymentid'], 'payment');
	}

	return $data;
}

function acc_delete_expense_convert($expense_id) {
	if ($expense_id) {
		$CI = &get_instance();
		$CI->load->model('accounting/accounting_model');

		$CI->accounting_model->delete_convert($expense_id, 'expense');
	}

	return $data;
}

function acc_invoice_status_changed($data) {
	$CI = &get_instance();
	$CI->load->model('accounting/accounting_model');

	$CI->accounting_model->invoice_status_changed($data);

	return $data;
}
