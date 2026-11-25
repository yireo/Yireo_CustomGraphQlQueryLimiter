# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.7] - 25 November 2026
### Fixed
- Empty, zero or invalid Query Depth / Query Complexity values no longer switch off GraphQL limits; Magento its own defaults stay active and the Admin Panel now rejects invalid input
- Magento its own GraphQL rules (introspection setting and DI-configured limits) are no longer replaced; configured limits are applied on top of them via plugins in the GraphQL area only, and also in developer mode
- Configuration section could not be granted to restricted admin roles because its ACL resource was missing
- Require webonyx/graphql-php 15.32.3 or higher to exclude vulnerable versions
- Declare missing dependencies on Magento_GraphQl, Magento_Config and Magento_Backend

### Changed
- Settings are only configurable on the global (Default Config) scope
- Corrected the composer package description

### Removed
- Dropped PHP 8.1 support, PHP 8.2 up to 8.5 are supported

## [0.0.6] - 14 August 2025
### Fixed
- Switch $source type from string to DocumentNode|string

## [0.0.5] - 12 August 2025
### Fixed
- Fix implicit nullables for PHP 8.4
- PHP 8.2 upgrades
- Upgrade PHPStan workflow

## [0.0.4] - 12 April 2024
### Fixed
- Changed method arguments in M 2.4.7

## [0.0.3] - 9 February 2022 
### Fixed
- Fixed issue with new validateFieldCount() in 2.4.3 (issued by @youanden)
- Removed `setup_version` from module.xml

## [0.0.2] - 29 July 2020
### Added
- Magento 2.4 compatibility

## [0.0.1] - Undocumented
