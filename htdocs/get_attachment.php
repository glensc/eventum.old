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
// @(#) $Id: get_attachment.php 3868 2009-03-30 00:22:35Z glen $

require_once dirname(__FILE__) . '/../init.php';

Auth::checkAuthentication(APP_COOKIE);

if (@$_GET['cat'] == 'blocked_email') {
    $email = Note::getBlockedMessage($_GET["note_id"]);
} else {
    $email = Support::getFullEmail($_GET["sup_id"]);
}
if (!empty($_GET['raw'])) {
    Attachment::outputDownload($email, 'message.eml', strlen($email), 'message/rfc822');
} else {
    if (!empty($_GET['cid'])) {
        list($mimetype, $data) = Mime_Helper::getAttachment($email, $_GET["filename"], $_GET["cid"]);
    } else {
        list($mimetype, $data) = Mime_Helper::getAttachment($email, $_GET["filename"]);
    }
    Attachment::outputDownload($data, $_GET["filename"], strlen($data), $mimetype);
}