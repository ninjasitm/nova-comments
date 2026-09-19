# Changelog

All notable changes to `nova-comments` will be documented in this file

## 2.0.0-rc.1 - 2026-09-19

- Breaking: require PHP 8.4, Laravel 13, and Nova 5.
- Migrate the inline tool to Vue 3 and Laravel Mix 6; modernize Nova resource, API, and commenter-auth integration.
- Preserve the historical `nova_comments` schema and existing data shape; add package contracts and fixture coverage.
- Document that raw comment HTML remains trusted-content-only and carries stored-XSS risk.
- This is a prerelease; host-browser smoke testing is not claimed.

## 1.0.0 - 2019-04-23

- Initial release

## 1.0.1 - 2019-04-23

- Updated service provider name

## 1.0.2 - 2019-12-04

- StyleCI script and badge
- Codacy badge
- Removed `console.log`. Thanks to [@eugenevdm](https://github.com/eugenevdm) for the suggestion [#14](https://github.com/kirschbaum-development/nova-comments/pull/14).
- Navigation for Comments can now be hidden. Even though the PR wasn't accepted, thanks to [@0x15f](https://github.com/0x15f) for a good place to start.
