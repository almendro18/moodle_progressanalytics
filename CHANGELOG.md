# Changelog

All notable changes to the Progress Analytics Block will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned Features
- Additional chart types (histogram, scatter plots)
- Export functionality for analytics data
- Email notifications for milestone achievements
- Integration with course completion tracking
- Advanced filtering options
- Teacher dashboard with class overview
- Historical trend analysis
- Custom date range selection

## [1.0.2] - 2024-09-22

### Added
- Documented AMD build process, localisation coverage, and privacy/caching details in the README.
- Introduced a dedicated `styles.css` and namespaced classes for all block styling.
- Captured release notes in a maintained changelog file.

### Changed
- Localised numeric admin settings so options display translated labels.
- Regenerated AMD builds after source updates and ensured matching `.map` files.
- Converted chart containers to `<canvas>` elements to keep Chart.js compatible.

### Fixed
- Resolved layout issues caused by inline styles and ensured Moodle coding-style compliance across touched files.

## [1.0.1] - 2024-09-18

### Added
- Comprehensive GPL headers and docblocks across PHP and AMD source files.
- Language strings for cache definitions to describe stored analytics data.

### Changed
- Renamed the distribution directory to `block_progressanalytics` to match the declared component.
- Replaced hard-coded UI text in PHP, Mustache, and AMD modules with Moodle language strings.

## [1.0.0] - 2024-09-10

### Added
- Initial release of Progress Analytics Block
- Quiz progress tracking with doughnut chart visualization
- Individual quiz results display with line/bar charts
- Course comparison analytics with percentile ranking
- Responsive design with mobile optimization
- Dark mode support respecting user preferences
- Accessibility features (ARIA labels, screen reader support)
- Multi-language support (English and Spanish)
- Configurable caching system for performance optimization
- Privacy-compliant implementation following GDPR standards
- Global settings for administrators
- Block instance configuration options
- Comprehensive help documentation
- Error handling and graceful degradation

### Features
- **Progress Chart**: Shows completion percentage of course quizzes
- **Results Chart**: Displays chronological quiz performance
- **Comparison Chart**: Compares student average with course metrics
- **Smart Caching**: User-level and course-level cache optimization
- **Permission System**: Capability-based access control
- **Anonymized Data**: Course comparisons without exposing individual data

### Technical Details
- Compatible with Moodle 4.1+ and PHP 8.1+
- Uses Chart.js for modern, responsive visualizations
- AMD JavaScript modules for optimal performance
- Mustache templating for clean separation of concerns
- External API service for AJAX data loading
- Comprehensive unit test coverage ready
- Follows Moodle coding standards and best practices

### Supported Metrics
- Quiz completion progress (attempted vs total)
- Normalized grade calculations (0-100 scale)
- Course-wide statistical comparisons
- Percentile ranking within enrolled students
- Configurable minimum participant thresholds

### Configuration Options
- Include/exclude hidden quizzes
- Adjustable cache intervals (1-60 minutes)
- Minimum participants for comparison (3-20)
- Chart type selection (line/bar for results)
- Percentile display toggle
- Per-instance chart visibility controls
