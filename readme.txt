=== About ===
name: CrowdFlower
website: http://www.rrbaker.com/projects
description: Receive reports from CrowdFlower via the API
version: 0.5
requires: 2.1
tested up to: 2.1
author: Rob Baker
author website: http://www.rrbaker.com

== Description ==
Receive reports from CrowdFlower via the API

== Installation ==
1. Copy the entire /ushahidicrowdflower/ directory into your /plugins/ directory. Please note that naming this plugin something else will results in installation errors.
2. Activate the plugin.
3. Access the plugin settings and enter your CrowdFlower API key and Job number.  While activated, the plugin will pull results via JSON from your CrowdFlower account marked as Gold.

== Changelog ==
1.0 (Initial release)
- Import reports from CF
- Set API and Job ID within admin settings
- Pull reports via the scheduler with at least three judgements
- Report arrive pre-approved, pre-verified

== Development Roadmap ==
- Toggle for reports coming into Ushahidi as Approved or Unapproved
- Toggle for reports coming into Ushahidi as Verified or Unverified
- Ability to select number of judgements units should have before they are received