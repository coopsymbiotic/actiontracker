# Action Tracker

Work-in-progress extension that helps track specific actions in CiviCRM:

* CiviMail URLs with checksums (by default, CiviCRM does not track these).
* see 'todo'

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP 7.0 or later
* CiviCRM 5.x or later

## Installation

See the documentation: https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/

## Usage

Besides enabling the extension, this extension does not require any other configuration.

## Support

Please post bug reports in the issue tracker of this project on CiviCRM's gitlab:  
https://lab.civicrm.org/extensions/actiontracker/issues

Commercial support is available from Coop SymbioTIC:  
https://www.symbiotic.coop/en

## Todo / brainstorm

Nice to haves:

* Track forms that have been submitted (to know how many people opened the form, vs how many submitted). Could be done by adding an argument in the civimail tracking URL, ex `actiontracker=submit`.
* ?

## Known Issues

This extensions adds URL parameters to the URL ('qid' and 'u'). This could
potentionally conflict with other customizations if they use the same URL
arguments. If you stumble on such conflicts, please report them on the issue
tracker.
