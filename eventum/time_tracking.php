<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 encoding=utf-8: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 - 2008 MySQL AB                                   |
// | Copyright (c) 2008 - 2009 Sun Microsystem Inc.                       |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Authors: João Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id: time_tracking.php 3834 2009-02-10 07:37:26Z glen $

require_once(dirname(__FILE__) . "/init.php");
require_once(APP_INC_PATH . "class.template_helper.php");
require_once(APP_INC_PATH . "class.auth.php");
require_once(APP_INC_PATH . "class.time_tracking.php");
require_once(APP_INC_PATH . "db_access.php");

$tpl = new Template_Helper();
$tpl->setTemplate("add_time_tracking.tpl.html");

Auth::checkAuthentication(APP_COOKIE, 'index.php?err=5', true);

$issue_id = @$_POST["issue_id"] ? $_POST["issue_id"] : $_GET["iss_id"];

if ((!Issue::canAccess($issue_id, Auth::getUserID())) || (Auth::getCurrentRole() <= User::getRoleID("Customer"))) {
    $tpl = new Template_Helper();
    $tpl->setTemplate("permission_denied.tpl.html");
    $tpl->displayTemplate();
    exit;
}

if (@$_POST["cat"] == "add_time") {
    $res = Time_Tracking::insertEntry();
    $tpl->assign("time_add_result", $res);
}

$tpl->assign(array(
    "issue_id"           => $issue_id,
    "time_categories"    => Time_Tracking::getAssocCategories(),
    "current_user_prefs" => Prefs::get(Auth::getUserID())
));

$tpl->displayTemplate();
