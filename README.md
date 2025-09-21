# Progress Analytics Block for Moodle

A modern, responsive Moodle block that displays comprehensive quiz analytics for students, showing their progress, results, and course comparisons.

## Features

- **Quiz Progress**: Visual doughnut chart showing completion percentage
- **Individual Results**: Line/bar chart displaying grades per completed quiz  
- **Course Comparison**: Comparative analysis with course averages and percentile ranking
- **Responsive Design**: Mobile-friendly with dark mode support
- **Accessibility**: ARIA labels, screen reader support, and keyboard navigation
- **Caching**: Optimized performance with configurable cache intervals
- **Privacy Compliant**: GDPR-compliant with proper privacy provider implementation

## Requirements

- Moodle 4.1 (build 2022112800) or newer
- PHP 8.1 or newer
- Node.js 18+ with npm for rebuilding AMD assets during development
- JavaScript enabled on the browser (Chart.js loaded via Moodle core)

## Installation

1. **Manual Installation:**
   ```bash
   # Extract the plugin to your Moodle blocks directory
   cp -r block_progressanalytics /path/to/moodle/blocks/
   
   # Set proper permissions
   chown -R www-data:www-data /path/to/moodle/blocks/block_progressanalytics
   ```

2. **Via Admin Interface:**
   - Go to Site Administration > Plugins > Install plugins
   - Upload the plugin ZIP file
   - Follow the installation wizard

3. **Complete Installation:**
   - Navigate to Site Administration > Notifications
   - Follow the upgrade process to install database changes

## Building AMD assets

When developing, regenerate the minified AMD assets so Moodle serves the latest JavaScript build:

```bash
npm install
npx grunt amd
```

Run both commands from the Moodle root directory. The minified files are written to `blocks/progressanalytics/amd/build` and should be committed when distributing the plugin.

## Configuration

### Global Settings
Access via Site Administration > Plugins > Blocks > Progress Analytics

- **Include hidden quizzes**: Include hidden quizzes in calculations
- **Cache interval**: Data caching duration (1-60 minutes) 
- **Minimum participants**: Required participants for course comparison (3-20)
- **Chart type**: Line or bar chart for results display
- **Show percentile**: Display percentile information in comparison

### Block Instance Settings
When editing a block instance:

- **Block title**: Custom title for the block
- **Show progress chart**: Toggle progress visualization
- **Show results chart**: Toggle individual results display  
- **Show comparison chart**: Toggle course comparison features

## Usage

### For Students
1. Add the block to any course page
2. View three key analytics:
   - **Progress**: Percentage of quizzes attempted
   - **Results**: Individual quiz performance over time
   - **Comparison**: Performance vs course average with percentile

### For Teachers
- Same analytics as students
- Additional capability to view extended analytics (future feature)
- Can configure block settings per course

## Data Sources and Calculations

### Progress Calculation
```
Progress = (Quizzes with ≥1 attempt / Total visible quizzes) × 100
```

### Results Calculation  
- Uses normalized grades from Moodle gradebook (0-100 scale)
- Handles different grade scales automatically
- Sorted chronologically by quiz completion date

### Comparison Metrics
- **Course Mean**: Average of all student quiz averages
- **Percentile**: Student's ranking within course participants
- **Minimum 5 participants** required for comparison display
- Excludes non-student users based on capabilities

## Privacy & Cache

- The GDPR privacy provider declares that no additional personal data is stored and integrates with Moodle privacy APIs.
- Computed metrics are cached in `usermetrics` and `coursemetrics` stores (configurable TTL via admin settings).
- Event observers invalidate caches automatically when quiz attempts, grades or completions change.

## Performance Optimization

- **Multi-level caching**: Separate cache for user and course-level data
- **Configurable intervals**: User data (5min), Course data (10min)  
- **Efficient queries**: Uses Moodle APIs, minimal database impact
- **AJAX loading**: Deferred data loading for better page performance

## Accessibility Features

- **ARIA compliance**: Proper labels and roles for charts
- **Screen reader support**: Text descriptions for all visualizations
- **Keyboard navigation**: Full keyboard accessibility
- **High contrast**: Meets WCAG AA standards
- **Fallback content**: Graceful degradation when JavaScript disabled

## Localisation

The block ships with English (`en`) and Spanish (`es`) language packs. All UI text is retrieved through Moodle's string API, including AMD modules and Mustache templates, so translating to new locales only requires copying the language file into a new `lang/<langcode>/block_progressanalytics.php`.

## Troubleshooting

### Common Issues

**Block shows "No quizzes available"**
- Verify course contains visible quiz activities
- Check user has appropriate permissions

**Charts not loading**
- Ensure JavaScript is enabled
- Check browser console for network errors
- Verify Chart.js CDN accessibility

**Performance issues**
- Reduce cache intervals in settings
- Increase minimum participants threshold
- Check server resource utilization

### Debug Information
Enable debug mode in Moodle to see detailed error messages and performance metrics.

## Development

### File Structure
```
blocks/block_progressanalytics/
├── block_progressanalytics.php    # Main block class
├── edit_form.php                  # Block configuration form
├── settings.php                   # Global settings
├── version.php                    # Plugin metadata
├── classes/
│   ├── external/                  # Web service API
│   └── privacy/                   # Privacy provider
├── db/                           # Database definitions
├── lang/                         # Language files
├── templates/                    # Mustache templates
└── amd/src/                     # JavaScript modules
```

### Extending the Plugin

To add new chart types or metrics:

1. Extend the external API service
2. Update JavaScript module with new chart rendering
3. Add corresponding language strings
4. Update templates as needed

## Support and Contributing

- **Issues**: Report via Moodle plugin directory
- **Documentation**: Available at plugin homepage  
- **Contributing**: Follow Moodle coding standards and submit patches

## License

GNU General Public License v3.0 or later

## Changelog

The full release history, including the latest 1.0.2 update, is documented in [CHANGELOG.md](CHANGELOG.md).
