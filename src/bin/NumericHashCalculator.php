#!/usr/bin/php
<?php
/* 
 * Copyright (C) 2014 blackfox
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

define ('DEFAULT_MAXNUM', '99999');

function usage () {
	die ('Usage: ' . $argv [0] . ' "string" [maxnum]');
}

@$string = $argv [1];
@$maxnum = $argv [2];

if ($argc == 1 || $argc > 3) {
	usage ();
}
if (!is_numeric ($maxnum) || $maxnum < 1) {
	$maxnum = DEFAULT_MAXNUM;
}
///**
var_dump ($string);
var_dump ($maxnum);
var_dump (md5 ($string));
var_dump (crc32 ($string));
//*/
$hash = crc32 ($string) % $maxnum;

echo $hash;