#!/usr/bin/php
<?php
/**
 * CLI Script to fetch and save VDM stories
 */

$config = require "config.php";

// FIXME: should be a POST request
// (using GET for now for simplicity)
echo file_get_contents($config["vdm.url"]);