<?php
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
 * Spanish language strings for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Analíticas de Progreso';
$string['progressanalytics:addinstance'] = 'Agregar un nuevo bloque de analíticas de progreso';
$string['progressanalytics:myaddinstance'] = 'Agregar un nuevo bloque de analíticas de progreso al Panel';
$string['progressanalytics:view'] = 'Ver analíticas de progreso';
$string['progressanalytics:viewall'] = 'Ver analíticas de progreso extendidas';

// UI Strings
$string['loading'] = 'Cargando analíticas...';
$string['errorloadingdata'] = 'Error al cargar los datos de analíticas. Inténtelo de nuevo más tarde.';
$string['noquizzes'] = 'Sin actividades configuradas para el progreso en este curso.';
$string['progress'] = 'Progreso en actividades';
$string['results'] = 'Mis Resultados';
$string['comparison'] = 'Comparativa del Curso';

// Chart Labels
$string['progresschartlabel'] = 'Gráfica de progreso mostrando porcentaje de actividades completadas';
$string['resultschartlabel'] = 'Gráfica de resultados mostrando las calificaciones por cuestionario';
$string['comparisonchartlabel'] = 'Gráfica de comparación mostrando estudiante vs promedio del curso';

// Privacy
$string['privacy:metadata'] = 'El bloque de Analíticas de Progreso no almacena datos personales. Solo muestra información agregada del libro de calificaciones y datos de intentos de cuestionarios existentes.';
$string['privacy:metadata:core_cache'] = 'El bloque de Analíticas de Progreso almacena en caché los datos de analíticas de cuestionarios para mejorar el rendimiento. Esta caché contiene métricas calculadas pero ninguna información personal adicional.';

// Settings
$string['config_title'] = 'Configuración de Analíticas de Progreso';
$string['config_includehidden'] = 'Incluir cuestionarios ocultos';
$string['config_includehidden_desc'] = 'Incluir cuestionarios ocultos en los cálculos de analíticas';
$string['config_cacheinterval'] = 'Intervalo de caché (minutos)';
$string['config_cacheinterval_desc'] = 'Cuánto tiempo mantener los datos de analíticas en caché (1-60 minutos)';
$string['config_minparticipants'] = 'Mínimo de participantes para comparación';
$string['config_minparticipants_desc'] = 'Número mínimo de participantes necesarios para mostrar la comparación del curso (3-20)';
$string['config_charttype'] = 'Tipo de gráfica de resultados';
$string['config_charttype_desc'] = 'Elija el tipo de gráfica para mostrar los resultados de los cuestionarios';
$string['config_charttype_line'] = 'Gráfica de líneas';
$string['config_charttype_bar'] = 'Gráfica de barras';
$string['config_showpercentile'] = 'Mostrar información de percentil';
$string['config_showpercentile_desc'] = 'Mostrar el percentil del estudiante en la comparación del curso';
$string['config_progressmodules'] = 'Tipos de actividades a contar en el progreso';
$string['config_progressmodules_desc'] = 'Selecciona qué tipos de actividades del curso se incluyen en el cálculo del progreso (por ejemplo, Cuestionarios, Tareas). No se usará la configuración de finalización.';
$string['config_resultslimit'] = 'Máximo de resultados en “Mis resultados”';
$string['config_resultslimit_desc'] = 'Número de cuestionarios a mostrar por defecto (se podrán ver todos con un botón)';

$string['config_minutes_1'] = '1 minuto';
$string['config_minutes_2'] = '2 minutos';
$string['config_minutes_5'] = '5 minutos';
$string['config_minutes_10'] = '10 minutos';
$string['config_minutes_15'] = '15 minutos';
$string['config_minutes_30'] = '30 minutos';
$string['config_minutes_60'] = '60 minutos';
$string['config_participants_3'] = '3 participantes';
$string['config_participants_5'] = '5 participantes';
$string['config_participants_10'] = '10 participantes';
$string['config_participants_15'] = '15 participantes';
$string['config_participants_20'] = '20 participantes';
$string['defaultmod_quiz'] = 'Cuestionarios';
$string['defaultmod_assign'] = 'Tareas';

// Block Instance Settings
$string['blocktitle'] = 'Título del bloque';
$string['blocktitle_desc'] = 'Título personalizado para esta instancia del bloque';
$string['showprogress'] = 'Mostrar gráfica de progreso';
$string['showprogress_desc'] = 'Mostrar la gráfica de progreso de completación de cuestionarios';
$string['showresults'] = 'Mostrar gráfica de resultados';
$string['showresults_desc'] = 'Mostrar la gráfica de resultados de cuestionarios';
$string['showcomparison'] = 'Mostrar gráfica de comparación';
$string['showcomparison_desc'] = 'Mostrar la gráfica de comparación del curso';

// UI actions
$string['showall'] = 'Ver todos';
$string['showless'] = 'Ver menos';

// JS/localized labels
$string['js_notstarted'] = 'Por comenzar';
$string['js_completed'] = 'Completado';
$string['js_noresults'] = 'Completa cuestionarios para ver tus resultados aquí';
$string['js_noresults_desc'] = 'Aún no has completado ningún cuestionario';
$string['js_personalavg'] = 'Promedio personal';
$string['js_quizzes'] = 'cuestionarios';
$string['js_myaverage'] = 'Mi promedio';
$string['js_courseaverage'] = 'Promedio del curso';
$string['js_comparison_pending'] = 'Los datos de comparación aparecerán cuando más estudiantes completen los cuestionarios';
$string['js_courseavgprefix'] = 'Promedio del curso:';
$string['js_yourpercentileprefix'] = 'Tu percentil:';
$string['progress_tooltip_completed'] = 'Completados: {count}';
$string['progress_tooltip_remaining'] = 'Pendientes: {count}';
$string['progress_summary'] = '{completed} de {total} actividades completadas';
$string['results_empty_message'] = 'Sin resultados disponibles';
$string['results_dataset_label'] = 'Calificación';
$string['results_tooltip'] = 'Calificación: {grade}%';
$string['results_summary'] = 'Promedio personal: {average}% ({count} cuestionarios)';
$string['comparison_tooltip'] = '{label}: {value}%';
$string['comparison_summary_with_percentile'] = 'Promedio del curso: {course}% • Tu percentil: {percentile}%';
$string['comparison_summary_without_percentile'] = 'Promedio del curso: {course}%';

// Cache definitions
$string['cachedef_usermetrics'] = 'Datos de progreso por usuario en caché';
$string['cachedef_coursemetrics'] = 'Datos analíticos del curso en caché';
