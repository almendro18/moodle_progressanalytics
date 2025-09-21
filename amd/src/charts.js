// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * AMD module powering Progress Analytics block charts.
 *
 * @module block_progressanalytics/charts
 */

define(['core/ajax', 'core/chartjs', 'core/str'], function(Ajax, Chart, Str) {
    'use strict';

    const stringConfig = [
        {id: 'progressTooltipCompleted', key: 'progress_tooltip_completed'},
        {id: 'progressTooltipRemaining', key: 'progress_tooltip_remaining'},
        {id: 'progressSummary', key: 'progress_summary'},
        {id: 'resultsEmptyMessage', key: 'results_empty_message'},
        {id: 'resultsDatasetLabel', key: 'results_dataset_label'},
        {id: 'resultsTooltip', key: 'results_tooltip'},
        {id: 'resultsSummary', key: 'results_summary'},
        {id: 'comparisonPending', key: 'js_comparison_pending'},
        {id: 'comparisonMyAverage', key: 'js_myaverage'},
        {id: 'comparisonCourseAverage', key: 'js_courseaverage'},
        {id: 'comparisonTooltip', key: 'comparison_tooltip'},
        {id: 'comparisonSummaryWithPercentile', key: 'comparison_summary_with_percentile'},
        {id: 'comparisonSummaryWithoutPercentile', key: 'comparison_summary_without_percentile'}
    ];

    const Charts = {
        progressChart: null,
        resultsChart: null,
        comparisonChart: null,
        strings: null,

        /**
         * Initialize the charts.
         * @param {number} courseid Course identifier
         */
        init: function(courseid) {
            const fallback = this.getFallbackStrings();
            const requests = stringConfig.map(entry => ({
                key: entry.key,
                component: 'block_progressanalytics'
            }));

            Str.get_strings(requests).then(values => {
                this.strings = {};
                stringConfig.forEach((entry, index) => {
                    const value = values[index];
                    this.strings[entry.id] = value || fallback[entry.id];
                });
                this.loadData(courseid);
            }).catch(() => {
                this.strings = fallback;
                this.loadData(courseid);
            });
        },

        /**
         * Load data from the server.
         * @param {number} courseid Course identifier
         */
        loadData: function(courseid) {
            Ajax.call([{
                methodname: 'block_progressanalytics_get_course_quiz_metrics',
                args: { courseid: courseid }
            }])[0].then(data => {
                this.hideLoading();

                if (data.progress.total === 0) {
                    this.showNoQuizzes();
                    return;
                }

                this.showContent();
                this.renderCharts(data);
            }).catch(() => {
                this.showError();
            });
        },

        /**
         * Render all charts using the retrieved data.
         * @param {Object} data Dataset returned by the webservice
         */
        renderCharts: function(data) {
            this.renderProgressChart(data.progress);
            this.renderResultsChart(data.results);
            this.renderComparisonChart(data.comparison);
        },

        /**
         * Render the progress doughnut chart.
         * @param {Object} progress Progress data structure
         */
        renderProgressChart: function(progress) {
            const ctx = document.getElementById('progress-chart');
            if (!ctx) {
                return;
            }

            const completed = progress.completed;
            const remaining = progress.total - completed;
            const tooltipValues = {
                completed: this.formatString(this.strings.progressTooltipCompleted, {count: completed}),
                remaining: this.formatString(this.strings.progressTooltipRemaining, {count: remaining})
            };

            this.progressChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [completed, remaining],
                        backgroundColor: ['#28a745', '#e9ecef'],
                        borderColor: ['#1e7e34', '#dee2e6'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataIndex === 0 ? tooltipValues.completed : tooltipValues.remaining;
                                }
                            }
                        }
                    },
                    elements: {
                        center: {
                            text: progress.percentage.toFixed(1) + '%',
                            color: '#495057',
                            fontStyle: 'bold',
                            fontSize: 24
                        }
                    }
                },
                plugins: [{
                    beforeDraw: function(chart) {
                        if (chart.config.options.elements.center) {
                            const context2d = chart.ctx;
                            const centerConfig = chart.config.options.elements.center;
                            const fontStyle = centerConfig.fontStyle || 'Arial';
                            const fontSize = centerConfig.fontSize || 24;

                            context2d.restore();
                            context2d.font = fontSize + 'px ' + fontStyle;
                            context2d.textBaseline = 'middle';
                            context2d.fillStyle = centerConfig.color || '#000';

                            const centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                            const centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                            context2d.textAlign = 'center';
                            context2d.fillText(centerConfig.text, centerX, centerY);
                            context2d.save();
                        }
                    }
                }]
            });

            const description = document.getElementById('progress-description');
            if (description) {
                description.textContent = this.formatString(this.strings.progressSummary, {
                    completed: completed,
                    total: progress.total
                });
            }
        },

        /**
         * Render the results line chart.
         * @param {Array} results Array of quiz results
         */
        renderResultsChart: function(results) {
            const ctx = document.getElementById('results-chart');
            if (!ctx) {
                return;
            }

            if (results.length === 0) {
                ctx.parentElement.innerHTML = '<div class="text-center text-muted p-3">' +
                    this.strings.resultsEmptyMessage + '</div>';
                const emptyDescription = document.getElementById('results-description');
                if (emptyDescription) {
                    emptyDescription.textContent = '';
                }
                return;
            }

            const labels = results.map(result => result.name.length > 15 ? result.name.substring(0, 15) + '...' : result.name);
            const grades = results.map(result => result.grade);
            const self = this;
            const tooltipTemplate = this.strings.resultsTooltip;

            this.resultsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: this.strings.resultsDatasetLabel,
                        data: grades,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1,
                        pointBackgroundColor: '#007bff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return results[context[0].dataIndex].name;
                                },
                                label: function(context) {
                                    return self.formatString(tooltipTemplate, {
                                        grade: context.parsed.y.toFixed(1)
                                    });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45
                            }
                        }
                    }
                }
            });

            const description = document.getElementById('results-description');
            if (description) {
                const average = (grades.reduce((a, b) => a + b, 0) / grades.length).toFixed(1);
                description.textContent = this.formatString(this.strings.resultsSummary, {
                    average: average,
                    count: results.length
                });
            }
        },

        /**
         * Render the comparison bar chart.
         * @param {Object} comparison Comparison metrics
         */
        renderComparisonChart: function(comparison) {
            const ctx = document.getElementById('comparison-chart');
            if (!ctx) {
                return;
            }

            if (!comparison.hasComparison || comparison.courseMean === 0) {
                ctx.parentElement.innerHTML = '<div class="text-center text-muted p-3">' +
                    this.strings.comparisonPending + '</div>';
                const emptyDescription = document.getElementById('comparison-description');
                if (emptyDescription) {
                    emptyDescription.textContent = '';
                }
                return;
            }

            const data = {
                labels: [this.strings.comparisonMyAverage, this.strings.comparisonCourseAverage],
                datasets: [{
                    data: [comparison.studentMean, comparison.courseMean],
                    backgroundColor: ['#007bff', '#6c757d'],
                    borderColor: ['#0056b3', '#5a6268'],
                    borderWidth: 1
                }]
            };
            const self = this;

            this.comparisonChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return self.formatString(self.strings.comparisonTooltip, {
                                        label: context.label,
                                        value: context.parsed.y.toFixed(1)
                                    });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });

            const description = document.getElementById('comparison-description');
            if (description) {
                const courseMean = comparison.courseMean.toFixed(1);
                if (comparison.hasComparison && comparison.studentMean > 0) {
                    description.textContent = this.formatString(
                        this.strings.comparisonSummaryWithPercentile,
                        {
                            course: courseMean,
                            percentile: comparison.percentile
                        }
                    );
                } else {
                    description.textContent = this.formatString(
                        this.strings.comparisonSummaryWithoutPercentile,
                        {
                            course: courseMean
                        }
                    );
                }
            }
        },

        /**
         * Show loading state.
         */
        showLoading: function() {
            document.getElementById('analytics-loading').classList.remove('d-none');
            document.getElementById('analytics-error').classList.add('d-none');
            document.getElementById('analytics-noquizzes').classList.add('d-none');
            document.getElementById('analytics-content').classList.add('d-none');
        },

        /**
         * Hide loading state.
         */
        hideLoading: function() {
            document.getElementById('analytics-loading').classList.add('d-none');
        },

        /**
         * Show error state.
         */
        showError: function() {
            this.hideLoading();
            document.getElementById('analytics-error').classList.remove('d-none');
        },

        /**
         * Show no quizzes state.
         */
        showNoQuizzes: function() {
            this.hideLoading();
            document.getElementById('analytics-noquizzes').classList.remove('d-none');
        },

        /**
         * Show main content.
         */
        showContent: function() {
            this.hideLoading();
            document.getElementById('analytics-content').classList.remove('d-none');
        },

        /**
         * Provide fallback strings in cases where the language API fails.
         * @return {Object} Fallback translations
         */
        getFallbackStrings: function() {
            return {
                progressTooltipCompleted: 'Completed: {count}',
                progressTooltipRemaining: 'Remaining: {count}',
                progressSummary: '{completed} of {total} activities completed',
                resultsEmptyMessage: 'No quiz results available yet',
                resultsDatasetLabel: 'Grade',
                resultsTooltip: 'Grade: {grade}%',
                resultsSummary: 'Personal average: {average}% ({count} quizzes)',
                comparisonPending: 'Comparison data will appear when more students complete quizzes',
                comparisonMyAverage: 'My average',
                comparisonCourseAverage: 'Course average',
                comparisonTooltip: '{label}: {value}%',
                comparisonSummaryWithPercentile: 'Course average: {course}% • Your percentile: {percentile}%',
                comparisonSummaryWithoutPercentile: 'Course average: {course}%'
            };
        },

        /**
         * Replace {placeholder} tokens with contextual data.
         * @param {String} template Template string with placeholders
         * @param {Object} data Replacement map
         * @return {String} The formatted string
         */
        formatString: function(template, data) {
            if (!template) {
                return '';
            }

            return template.replace(/\{(\w+)\}/g, function(match, key) {
                if (Object.prototype.hasOwnProperty.call(data, key)) {
                    return data[key];
                }
                return match;
            });
        }
    };

    return Charts;
});
