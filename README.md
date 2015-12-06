coveralls-kit-cli
=================

Command line interface for sending a report to **coveralls**.  
Support the report **lcov**, **clover**.

[![Build Status](https://travis-ci.org/cloak-php/coveralls-kit-cli.svg?branch=master)](https://travis-ci.org/cloak-php/coveralls-kit-cli)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cloak-php/coveralls-kit-cli/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cloak-php/coveralls-kit-cli/?branch=master)
[![Coverage Status](https://coveralls.io/repos/cloak-php/coveralls-kit-cli/badge.png?branch=master)](https://coveralls.io/r/cloak-php/coveralls-kit-cli?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/540f97fb9e1622709c000021/badge.svg?style=flat)](https://www.versioneye.com/user/projects/540f97fb9e1622709c000021)
[![Stories in Ready](https://badge.waffle.io/cloak-php/coveralls-kit-cli.png?label=ready&title=Ready)](https://waffle.io/cloak-php/coveralls-kit-cli)

## Requirements

* PHP >= 5.5
* Xdebug >= 2.2.2

## Install

Please execute the following command.

	composer require cloak/coverallskit-cli --dev

## Basic usage

First of all, please perform the setup.  
When the command is executed, the configuration file will be generated.  
The file name is **.coveralls.toml**.

	vendor/bin/coverallskit init

It will send a report to coveralls in the send command.  

	vendor/bin/coverallskit send

or

	vendor/bin/coverallskit send -c .coveralls.toml

## Configuration file format 

Please look at the documentation for [coveralls-kit](https://github.com/cloak-php/coveralls-kit) for a description of the configuration file.

## Run the unit test

	composer test
