<?php
/*
Plugin Name: License Lounge - Contracts
Description: Creates contracts, creates PDFs
Author: Catchon Media
Author URI: http://www.catchonmedia.com
Version: 1.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

/*

    Copyright (C) Year  Catchon Media  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once "inc/models/contract.php";
require_once "inc/contract_template.php";
require_once "inc/pdf.php";
require_once "inc/wp.php";

register_activation_hook( __FILE__, array( 'LicenseLounge\Contract\WP', 'activate' ) );
add_action( 'process_hook', array('\LicenseLounge\Contract\Contract', 'process') );
