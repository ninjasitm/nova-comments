# CHIP-261 Nova 5 / Laravel 13 modernization plan

**Goal:** Modernize the package for PHP 8.4, Laravel 13, and Nova 5 while preserving its schema, public package surface, and inline commenting behavior.

**Approach:** Add dependency and behavior-contract tests before each compatible implementation change. Resolve Nova APIs from the licensed package source after dependency installation; keep the frontend as a small Vue 3 Nova tool built through Mix 6.

1. Add and run failing metadata contracts for PHP, Laravel, Nova, and stable Composer settings.
2. Resolve dependencies without scripts; inspect the installed Nova 5 source for public resource, relationship, panel, and registration APIs.
3. Add failing Testbench contracts for the model, resource validation/commenter resolution, provider registration, panel construction, and historical migration shape; make minimal PHP changes until green.
4. Add failing frontend source contracts, then port the registered Nova tool to Vue 3, `Nova.request()`, native field lookup/date display, relationship metadata URLs, and one Mix 6 lockfile/build.
5. Update package documentation with requirements, commenter-resource behavior, and the owner-approved raw-HTML trust warning.
6. Run focused tests, PHP lint, Composer validation, dependency/build checks, bundle/path checks, diff checks, and independent spec/code-quality review; fix findings and rerun affected evidence.
