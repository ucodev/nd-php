<?php if (!defined('FROM_BASE')) { header('HTTP/1.1 403 Forbidden'); die('Invalid requested path.'); }

/*
 * This file is part of ND PHP Framework.
 *
 * ND PHP Framework - An handy PHP Framework (www.nd-php.org)
 * Copyright (C) 2015-2016  Pedro A. Hortas (pah@ucodev.org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/*
 * ND PHP Framework (www.nd-php.org) - Contributor Agreement
 *
 * When contributing material to this project, please read, accept, sign
 * and send us the Contributor Agreement located in the file NDCA.pdf
 * inside the documentation/ directory.
 *
 */

class UW_Charts extends UW_Module {
	private $config;	/* Configuration */

	private function _init() {
		/* Load configuration */
		$this->config = $this->configuration->core_get();

		/* Load required modules */
		$this->load->module('request');
		$this->load->module('response');
		$this->load->module('get');
		$this->load->module('table');

		/* Initialize charts configuration */
		$this->_charts_config();
	}

	public function __construct() {
		parent::__construct();

		/* Initialize module */
		$this->_init();
	}

	private function _charts_config() {
		/* NOTE: This method shall be invoked on every other method that access $_charts and $_charts_foreign array.
		 *
		 * Currently, only the following methos are invoking it by default: list_generic(), result_generic(), view_generic(), chart_publish_generic().
		 *
		 */

		/* Fetch all charts related to this controller */
		$this->db->select('title,controller,charts_types_id,charts_geometry.chart_geometry AS geometry,fields,abscissa,foreign_table,start_ts,end_ts,field,field_ts,field_legend,field_total,import_ctrl,chartid');
		$this->db->from('charts_config');
		$this->db->join('charts_geometry', 'charts_config.charts_geometry_id = charts_geometry.id', 'left');
		$this->db->where('controller', $this->config['name']);

		$q = $this->db->get();

		foreach ($q->result_array() as $row) {
			switch (intval($row['charts_types_id'])) {
				case 1: {
					/* TS */
					$this->chart_add_ts($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['abscissa']), $row['start_ts'], $row['end_ts']);
				} break;
				case 2: {
					/* REL */
					$this->chart_add_rel($row['title'], strtolower($row['geometry']), strtolower($row['field']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 3: {
					/* TOTALS */
					$this->chart_add_totals($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['field_legend']), strtolower($row['field_total']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 4: {
					/* FOREIGN TS */
					$this->chart_add_foreign_ts($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['abscissa']), strtolower($row['foreign_table']), $row['start_ts'], $row['end_ts']);
				} break;
				case 5: {
					/* FOREIGN REL */
					$this->chart_add_foreign_rel($row['title'], strtolower($row['geometry']), strtolower($row['field']), strtolower($row['foreign_table']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 6: {
					/* FOREIGN TOTALS */
					$this->chart_add_foreign_totals($row['title'], strtolower($row['geometry']), explode(',', strtolower(str_replace(' ', '', $row['fields']))), strtolower($row['field_legend']), strtolower($row['foreign_table']), strtolower($row['field_total']), strtolower($row['field_ts']), $row['start_ts'], $row['end_ts']);
				} break;
				case 7: {
					/* IMPORT */
					$this->chart_add_import(strtolower($row['import_ctrl']), $row['chartid'], $row['start_ts'], $row['end_ts']);
				} break;
				case 8: {
					$this->chart_add_foreign_import(strtolower($row['import_ctrl']), $row['chartid'], $row['start_ts'], $row['end_ts']);
				} break;
			}
		}
	}

	private function _chart_process_image_map($chart, &$pimage) {
		$chart_imagemap_name = 'chart_name_' . ($chart['foreign'] ? 'foreign' : 'local') . '_ '. $this->config['name'] . '_' . $chart['id'] . '_' . $this->config['session_data']['user_id'];
		$chart_imagemap_map = 'chart_map_' . ($chart['foreign'] ? 'foreign' : 'local') . '_ '. $this->config['name'] . '_' . $chart['id'] . '_' . $this->config['session_data']['user_id'];

		/* If this is a imagemap request, dump it.
		 * FIXME: There's no need to deliver the image map at this later stage. A new method should be implemented
		 * such as chart_publish_imagemap() to read the imagemap file directly from the temporary directory and deliver it
		 * without the need to create a new dataset and create a image object just to deliver what is already created.
		 */
		if ($chart['imagemap_request']) {
			$pimage->dumpImageMap(
				$chart_imagemap_name,	/* Image map name */
				IMAGE_MAP_STORAGE_FILE,	/* Storage type */
				$chart_imagemap_map,	/* Unique ID */
				$this->config['temp_dir']		/* Storage directory */
			);

			/* NOTE: Execution was terminated by the above call */
		}

		/* Initialize imagemap */
		$pimage->initialiseImageMap(
			$chart_imagemap_name,	/* Image map name */
			IMAGE_MAP_STORAGE_FILE,	/* Storage type */
			$chart_imagemap_map,	/* Unique ID */
			$this->config['temp_dir']		/* Storage directory */
		);
	}

	/* Generates a time-series dataset object for $chart, based on a query object $q, to be used as chart_build_image_ts() */
	protected function chart_build_dataset_ts($chart, $q) {
		$dataset = array();

		/* Prepare dataset */
		$i = 0;
		$ts_first = 0;
		foreach ($q->result_array() as $row) {
			if ($i == 0)
				$ts_first = $row['abscissa'];

			/* Fetch data from all Y axis fields */
			foreach ($chart['fields'] as $field)
				$dataset[$field][] = $row[$field];

			/* Fetch data from X axis field */
			$dataset[$chart['field_ts']][] = $row['abscissa'];

			$i ++;
		}
		$ts_last = $row['abscissa'];

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$i = 0;
		foreach ($dataset as $set => $data) {
			/* Also setup the both axis properties */
			if ($set == $chart['field_ts']) {
				/* This is the X axis */
				$pchart->addPoints($data, $set);

				$pchart->setAbscissa($chart['field_ts']);
				$pchart->setXAxisName(isset($this->config['table_field_aliases'][$chart['field_ts']]) ? $this->config['table_field_aliases'][$chart['field_ts']] : ucfirst($chart['field_ts']));

				/* Compute the X Axis Display format */
				$axis_format_id = AXIS_FORMAT_TIME;
				$axis_format_str = "H:i";

				if (($ts_last - $ts_first) < 3600) {
					/* Less than a hour */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "i:s";
				} else if (($ts_last - $ts_first) < 86400) {
					/* Less than a day */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "H:i";
				} else {
					/* More than a day */
					$axis_format_id = AXIS_FORMAT_DATE;
					$axis_format_str = "Y-m-d";
				}

				/* Set the X Axis Display format */
				$pchart->setXAxisDisplay($axis_format_id, $axis_format_str);
			} else {
				/* This belongs to Y axis */
				$pchart->addPoints($data, ucfirst($set));
				$pchart->setSerieOnAxis(ucfirst($set), $i);
				$pchart->setAxisName($i, isset($this->config['table_field_aliases'][$set]) ? $this->config['table_field_aliases'][$set] : ucfirst($set));

				/* Get field units */
				$this->db->select('field_units');
				$this->db->from('_help_tfhd');
				$this->db->where('table_name', $this->config['name']);
				$this->db->where('field_name', $set);
				$qu = $this->db->get();

				if ($qu->num_rows()) {
					$u = $qu->row_array();
					$pchart->setAxisUnit($i, $u['field_units']);
				}
			}

			$i ++;
		}

		return $pchart;
	}

	/* Generates a time-series (from a foreign table) dataset object for $chart, based on a query object $q,
	 * to be used as chart_build_image_foreign_ts()
	 */
	protected function chart_build_dataset_foreign_ts($chart, $q) {
		$dataset = array();

		/* Fetch the foreign controller object */
		$fctrl = $this->access->controller($chart['ftable']);

		/* Prepare dataset */
		$i = 0;
		$ts_first = 0;
		foreach ($q->result_array() as $row) {
			if ($i == 0)
				$ts_first = $row['abscissa'];

			/* Fetch data from all Y axis fields */
			foreach ($chart['fields'] as $field)
				$dataset[$field][] = $row[$field];

			/* Fetch data from X axis field */
			$dataset[$chart['field_ts']][] = $row['abscissa'];

			$i ++;
		}
		$ts_last = $row['abscissa'];

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$i = 0;
		foreach ($dataset as $set => $data) {
			/* Also setup the both axis properties */
			if ($set == $chart['field_ts']) {
				/* This is the X axis */
				$pchart->addPoints($data, $set);

				$pchart->setAbscissa($chart['field_ts']);
				$pchart->setXAxisName(isset($fctrl->config['table_field_aliases'][$chart['field_ts']]) ? $fctrl->config['table_field_aliases'][$chart['field_ts']] : ucfirst($chart['field_ts']));

				/* Compute the X Axis Display format */
				$axis_format_id = AXIS_FORMAT_TIME;
				$axis_format_str = "H:i";

				if (($ts_last - $ts_first) < 3600) {
					/* Less than a hour */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "i:s";
				} else if (($ts_last - $ts_first) < 86400) {
					/* Less than a day */
					$axis_format_id = AXIS_FORMAT_TIME;
					$axis_format_str = "H:i";
				} else {
					/* More than a day */
					$axis_format_id = AXIS_FORMAT_DATE;
					$axis_format_str = "Y-m-d";
				}

				/* Set the X Axis Display format */
				$pchart->setXAxisDisplay($axis_format_id, $axis_format_str);
			} else {
				/* This belongs to Y axis */
				$pchart->addPoints($data, ucfirst($set));
				$pchart->setSerieOnAxis(ucfirst($set), $i);
				$pchart->setAxisName($i, isset($fctrl->config['table_field_aliases'][$set]) ? $fctrl->config['table_field_aliases'][$set] : ucfirst($set));

				/* Get field units */
				$this->db->select('field_units');
				$this->db->from('_help_tfhd');
				$this->db->where('table_name', $fctrl->config['name']);
				$this->db->where('field_name', $set);
				$qu = $this->db->get();

				if ($qu->num_rows()) {
					$u = $qu->row_array();
					$pchart->setAxisUnit($i, $u['field_units']);
				}
			}

			$i ++;
		}

		return $pchart;
	}

	/* Generates a dataset object for $chart, based on a query object $q which refers to a single relationship,
	 * to be used as chart_build_image_rel()
	 */
	protected function chart_build_dataset_rel($chart, $q) {
		$dataset = array();

		/* Compute sum */
		$result_array = array();
		$sum = 0;
		foreach ($q->result_array() as $row) {
			array_push($result_array, $row);
			$sum += $row['total'];
		}

		/* Prepare dataset */
		foreach ($result_array as $row) {
			/* Fetch the set name */
			if ($chart['geometry'] == 'pie') {
				/* If this is a pie chart, add percentages and absolute values to the Labels */
				$dataset[$chart['fields']][] = $row[$chart['foreign_field']] . ' ' . round((($row['total'] * 100.0) / $sum), 2) . '% [' . $row['total'] . ']';
			} else {
				/* .. otherwise, just use the foreign field name as label */
				$dataset[$chart['fields']][] = $row[$chart['foreign_field']];
			}

			/* Fetch the set weight */
			$dataset['total'][] = $row['total'];
		}

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		$pchart->addPoints($dataset['total'], $row[$chart['foreign_field']]);
		$pchart->addPoints($dataset[$chart['fields']], 'Labels'); /* Always import abscissa as the last dataset to keep chart color coherency */
		$pchart->setAbscissa('Labels');

		/* All good */
		return $pchart;
	}

	protected function chart_build_dataset_foreign_rel($chart, $q) {
		/* Basically, there's no difference on dataset builder from 'foreign_rel' to 'rel' chart types */
		return $this->chart_build_dataset_rel($chart, $q);
	}

	/* Generates a dataset object for $chart, based on a query object $q which refers one or more fields containing numerical values,
	 * to be used as chart_build_image_totals()
	 */
	protected function chart_build_dataset_totals($chart, $q) {
		$dataset = array();

		/* Compute sum */
		$result_array = array();
		$sum = 0;
		foreach ($q->result_array() as $row) {
			array_push($result_array, $row);
			if (isset($row['total'])) /* Only sum if a total field was defined */
				$sum += $row['total'];
		}

		/* Prepare dataset */
		foreach ($result_array as $row) {
			if ($chart['field_tot']) {
				unset($row[$chart['field_tot']]); /* Remove replicas... */
			}
			/* Fetch the set name */
			if ($chart['geometry'] == 'pie' && $sum) {
				/* If this is a pie chart, add percentages and absolute values to the Labels */
				$dataset[$chart['fields'][0]][] = $row[$chart['fields'][0]] . ' ' . round((($row['total'] * 100.0) / $sum), 2) . '% [' . $row['total'] . ']';
			} else {
				/* .. otherwise, just use the foreign field name as label */
				$dataset[$chart['fields'][0]][] = $row[$chart['fields'][0]];
			}

			/* If a total field was set, fetch that value */
			if (isset($row['total']))
				$dataset['total'][] = $row['total'];

			/* Fetch the remaining fields data */
			foreach ($row as $field => $value) {
				/* We already have fetched this dataset (Label) */
				if ($field == $chart['fields'][0])
					continue;

				/* If a total dataset was defined, it was already fetched */
				if ($field == 'total')
					continue;

				/* Fetch the remaining set values */
				$dataset[$field][] = $value;
			}
		}

		/* Instantiate a pChart object */
		$pchart = $this->pchart->pData();

		/* Import dataset */
		if (isset($dataset['total']))
			$pchart->addPoints($dataset['total'], ucfirst($chart['field_tot']));

		foreach ($dataset as $set => $data) {
			/* Labels and totals already added */
			if ($set == 'total' || $set == $chart['fields'][0])
				continue;

			$pchart->addPoints($dataset[$set], ucfirst($set));
		}

		/* Always import abscissa as the last dataset to keep chart color coherency */
		$pchart->addPoints($dataset[$chart['fields'][0]], 'Labels');
		$pchart->setAbscissa('Labels');

		/* All good */
		return $pchart;
	}

	protected function chart_build_dataset_foreign_totals($chart, $q) {
		return $this->chart_build_dataset_totals($chart, $q);
	}

	/* Generates a time-series image object for $chart, based on $dataset returned from chart_build_dataset_ts() functions family */
	protected function chart_build_image_ts($chart, $dataset) {
		/* Instantiate image object */
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->config['charts_canvas_width'], $chart['height'] ? $chart['height'] : $this->config['charts_canvas_height'], $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set the chart area within canvas */
		$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_title_font_size']
		));

		/* Set chart title */
		$pimage->drawText($this->config['charts_canvas_width'] / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_axis_font_size']
		));

		/* Draw scale... */
		$pimage->drawScale(array(
			'DrawXLines'	=> false,
			'DrawYLines' 	=> ALL,
			'GridTicks'		=> 0,
			'GridR'			=> 200,
			'GridG'			=> 200,
			'GridB'			=> 200
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_ts($chart, $dataset) {
		/* Instantiate image object */
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->config['charts_canvas_width'], $chart['height'] ? $chart['height'] : $this->config['charts_canvas_height'], $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set the chart area within canvas */
		$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_title_font_size']
		));

		/* Set chart title */
		$pimage->drawText($this->config['charts_canvas_width'] / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_axis_font_size']
		));

		/* Draw scale... */
		$pimage->drawScale(array(
			'DrawXLines'	=> false,
			'DrawYLines' 	=> ALL,
			'GridTicks'		=> 0,
			'GridR'			=> 200,
			'GridG'			=> 200,
			'GridB'			=> 200
		));

		return $pimage;
	}

	protected function chart_build_image_rel($chart, $dataset) {
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->config['charts_canvas_width'], $chart['height'] ? $chart['height'] : $this->config['charts_canvas_height'], $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_title_font_size']
		));

		/* Set chart title */
		$pimage->drawText($this->config['charts_canvas_width'] / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_axis_font_size']
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_rel($chart, $dataset) {
		/* Basically, there's no difference on image builder from 'foreign_rel' to 'rel' chart types */
		return $this->chart_build_image_rel($chart, $dataset);
	}

	protected function chart_build_image_totals($chart, $dataset) {
		$pimage = $this->pchart->pImage($chart['width'] ? $chart['width'] : $this->config['charts_canvas_width'], $chart['height'] ? $chart['height'] : $this->config['charts_canvas_height'], $dataset);

		/* Process imagemap */
		$this->_chart_process_image_map($chart, $pimage);

		/* Set font for title */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_title_font_size']
		));

		/* Set chart title */
		$pimage->drawText($this->config['charts_canvas_width'] / 2, 10, $chart['title'], array(
			"R"		=> 0,
			"G"		=> 0,
			"B"		=> 0,
			"Align"	=> TEXT_ALIGN_TOPMIDDLE
		));

		/* Set font */
		$pimage->setFontProperties(array(
			'FontName' => $this->pchart->fonts_path() . '/' . $this->config['charts_font_family'] . '.ttf',
			'FontSize' => $this->config['charts_axis_font_size']
		));

		return $pimage;
	}

	protected function chart_build_image_foreign_totals($chart, $dataset) {
		return $this->chart_build_image_totals($chart, $dataset);
	}

	protected function chart_add_raw($chart) {
		array_push($this->config['charts'], $chart);
	}

	protected function chart_add_foreign_raw($chart) {
		array_push($this->config['charts_foreign'], $chart);
	}

	protected function chart_add_ts($title = 'Title', $geometry = 'line', $fields = array(), $abscissa = 'timestamp_field', $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts'], array(
			'title'		=> $title,
			'type'		=> 'ts',
			'geometry'	=> $geometry,
			'fields'	=> $fields,
			'field_ts'	=> $abscissa,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_ts($title = 'Title', $geometry = 'line', $ft_fields = array(), $ft_abscissa = 'timestamp_field', $ft_name, $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts_foreign'], array(
			'title'		=> $title,
			'type'		=> 'foreign_ts',
			'geometry'	=> $geometry,
			'fields'	=> $ft_fields,
			'field_ts'	=> $ft_abscissa,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL, /* NOTE: Will be set on chart_foreign_publish() */
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_rel($title = 'Title', $geometry = 'pie', $field, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts'], array(
			'title'		=> $title,
			'type'		=> 'rel',
			'geometry'	=> $geometry,
			'fields'	=> $field,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_rel($title = 'Title', $geometry = 'pie', $ft_field, $ft_name, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts_foreign'], array(
			'title'		=> $title,
			'type'		=> 'foreign_rel',
			'geometry'	=> $geometry,
			'fields'	=> $ft_field,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL, /* NOTE: Will be set on chart_foreign_publish() */
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_totals($title = 'Title', $geometry = 'bar', $fields = array(), $field_legend, $field_total = NULL, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		/* NOTE: $field_total only makes sense for pie charts (which will allow to compute and print the absolute and percentage values on legend) */

		$fields_all = array($field_legend);
		$fields_all = array_merge($fields_all, $fields);

		array_push($this->config['charts'], array(
			'imported'	=> false,
			'title'		=> $title,
			'type'		=> 'totals',
			'geometry'	=> $geometry,
			'fields'	=> $fields_all,
			'field_tot' => $field_total,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_foreign_totals($title = 'Title', $geometry = 'bar', $ft_fields = array(), $ft_field_legend, $ft_name, $field_total = NULL, $field_ts = NULL, $start_ts = 0, $end_ts = 0) {
		/* NOTE: $field_total only makes sense for pie charts (which will allow to compute and print the absolute and percentage values on legend) */

		$fields_all = array($ft_field_legend);
		$fields_all = array_merge($fields_all, $ft_fields);

		array_push($this->config['charts_foreign'], array(
			'imported'	=> false,
			'title'		=> $title,
			'type'		=> 'foreign_totals',
			'geometry'	=> $geometry,
			'fields'	=> $fields_all,
			'field_tot' => $field_total,
			'ftable'	=> $ft_name,
			'entry_id'	=> NULL,
			'field_ts'	=> $field_ts,
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'pimage'	=> NULL,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> false,
			'imp_ctrl'	=> NULL,
			'imp_id'	=> NULL,
			'height'	=> NULL,
			'width'		=> NULL
		));
	}

	protected function chart_add_import($controller, $chart_id, $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts'], array(
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'imagemap_request' => false,
			'foreign'	=> false,
			'imported'	=> true,
			'imp_ctrl'	=> $controller,
			'imp_id'	=> $chart_id
		));
	}

	protected function chart_add_foreign_import($controller, $chart_id, $start_ts = 0, $end_ts = 0) {
		array_push($this->config['charts_foreign'], array(
			'start_ts'	=> $start_ts,
			'end_ts'	=> $end_ts,
			'imagemap_request' => false,
			'foreign'	=> true,
			'imported'	=> true,
			'imp_ctrl'	=> $controller,
			'imp_id'	=> $chart_id
		));
	}

	private function _chart_generate_ts(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		/* Check if there is READ permission for abscissa (in ts type charts, this is the field_ts) */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $chart['field_ts']))
			return false;

		/* Also grant that there is READ permissions for the y axis fields */
		$fields_filtered = array();
		foreach ($chart['fields'] as $field) {
			if ($this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $field))
				array_push($fields_filtered, $field);
		}
		$chart['fields'] = $fields_filtered;

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Fetch data from database. NOTE: UNIX_TIMESTAMP() will always return UTC timestamps (converted from time_zone=SYSTEM, which should match the $this->config['default_timezone'] variable), so we need always to convert from Etc/UTC (and not from the $this->config['default_timezone']) */
		$this->db->select('`' . implode('`,`', $chart['fields']) . '`,UNIX_TIMESTAMP(CONVERT_TZ(`' . $chart['field_ts'] . '`, \'Etc/UTC\', \'' . $this->config['session_data']['timezone'] . '\')) AS abscissa', false);
		$this->db->from($this->config['name']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Join all relationships */
			$this->table->join_rels($this->config['name'], 'left');

			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_ts($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_ts($chart, $dataset);

		/* Set chart type */
		switch ($chart['geometry']) {
			case 'line'    : $pimage->drawLineChart(array('RecordImageMap' => true));        break;
			case 'spline'  : $pimage->drawSplineChart(array('RecordImageMap' => true));      break;
			case 'area'    : $pimage->drawAreaChart(array('RecordImageMap' => true));        break;
			case 'stacked' : $pimage->drawStackedAreaChart(array('RecordImageMap' => true)); break;
			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* Draw Legend */
		$pimage->drawLegend($this->config['charts_canvas_width'] - 80, 20, array(
			'R'		=> 255,
			'G'		=> 255,
			'B'		=> 255,
			'Alpha'	=> 0
		));

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_ts(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			/* Check if there is READ permissions on the foreign table that will feed the data */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable']))
				return false;

			/* Check if there is READ permission for abscissa (in ts type charts, this is the field_ts) */
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable'], $chart['field_ts']))
				return false;
		}

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->config['name'] table. */
		$this->db->select('id');
		$this->db->from($this->config['name']);
		$this->db->where('id', $chart['entry_id']);

		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Also grant that there is READ permissions for the y axis fields (which belong to the foreign table) */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fields_filtered = array();

			foreach ($chart['fields'] as $field) {
				if ($this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable'], $field))
					array_push($fields_filtered, $field);
			}

			$chart['fields'] = $fields_filtered;
		}

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Fetch data from database. NOTE: UNIX_TIMESTAMP() will always return UTC timestamps (converted from time_zone=SYSTEM, which should match the $this->config['default_timezone'] variable), so we need always to convert from Etc/UTC (and not from the $this->config['default_timezone']) */
		$this->db->select('`' . $chart['ftable'] . '`.`' . implode('`,`' . $chart['ftable'] . '`.`', $chart['fields']) . '`,UNIX_TIMESTAMP(CONVERT_TZ(`' . $chart['ftable'] . '`.`' . $chart['field_ts'] . '`, \'Etc/UTC\', \'' . $this->config['session_data']['timezone'] . '\')) AS abscissa', false);
		$this->db->from($chart['ftable']);
		$this->db->join($this->config['name'], $chart['ftable'] . '.' . $this->config['name'] . '_id = ' . $this->config['name'] . '.id', 'left');
		$this->db->where($this->config['name'] . '.id', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if this isn't a mixed table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->get->table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->config['session_data'][$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_ts($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_ts($chart, $dataset);

		/* Set chart type */
		switch ($chart['geometry']) {
			case 'line'    : $pimage->drawLineChart(array('RecordImageMap' => true));        break;
			case 'spline'  : $pimage->drawSplineChart(array('RecordImageMap' => true));      break;
			case 'area'    : $pimage->drawAreaChart(array('RecordImageMap' => true));        break;
			case 'stacked' : $pimage->drawStackedAreaChart(array('RecordImageMap' => true)); break;
			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* Draw Legend */
		$pimage->drawLegend($this->config['charts_canvas_width'] - 80, 20, array(
			'R'		=> 255,
			'G'		=> 255,
			'B'		=> 255,
			'Alpha'	=> 0
		));

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_rel(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Get foreign table and fields information */
		$foreign_table = substr($chart['fields'], 0, -3);

		/* Check the READ permission for the foreign table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $foreign_table))
			return false;

		$foreign_fields = $this->get->table_fields($foreign_table);
		$chart['foreign_field'] = $foreign_fields[1];

		/* Check the READ permissions for the foreign field */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $foreign_table, $chart['foreign_field']))
			return false;

		/* Fetch data from database */
		/* TODO: FIXME: Check $_rel_table_fields_aliases for field concatenations... */
		/* TODO: FIXME: Also add table prefixes on select() to avoid ambiguous field names */
		$this->db->select('`' . $foreign_table . '`.`' . $foreign_fields[1] . '`,COUNT(`' . $foreign_table . '`.`'. $foreign_fields[1] . '`) AS `total`', false);
		$this->db->from($this->config['name']);
		$this->db->join($foreign_table, '`' . $foreign_table . '`.`id` = `' . $this->config['name'] . '`.`' . $chart['fields'] . '`', 'left');
		$this->db->group_by($foreign_fields[1]);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Join all relationships, excluding the one that was already joined before */
			$this->table->join_rels($this->config['name'], 'left', array($foreign_table));

			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_rel($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_rel($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->config['charts_canvas_width'] / 2, $this->config['charts_canvas_height'] / 2, array(
					'DrawLabels'	 => true,
					'Border'		 => true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_rel(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		/* Check if there is READ permission for foreign table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable']))
				return false;
		}

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		$foreign_table = substr($chart['fields'], 0, -3);

		$foreign_fields = $this->get->table_fields($foreign_table);
		$chart['foreign_field'] = $foreign_fields[1];

		/* Check the READ permissions for the foreign field */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $foreign_table, $chart['foreign_field']))
			return false;

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->config['name'] table. */
		$this->db->select('id');
		$this->db->from($this->config['name']);
		$this->db->where('id', $chart['entry_id']);

		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Fetch data from database */
		/* TODO: FIXME: Check $_rel_table_fields_aliases for field concatenations... */
		/* TODO: FIXME: Also add table prefixes on select() to avoid ambiguous field names */
		$this->db->select('`' . $foreign_table . '`.`' . $foreign_fields[1] . '`,COUNT(`' . $foreign_table . '`.`' . $foreign_fields[1] . '`) AS `total`', false);
		$this->db->from($chart['ftable']);
		$this->db->join($foreign_table, '`' . $foreign_table . '`.`id` = `' . $chart['ftable'] . '`.`' . $chart['fields'] . '`', 'left');
		//$this->db->join($this->config['name'], '`' . $this->config['name'] . '`.`id` = `' . $chart['ftable'] . '`.`' . $this->config['name'] . '_id`', 'left');
		$this->db->group_by($foreign_fields[1]);
		$this->db->where($chart['ftable'] . '`.`' . $this->config['name'] . '_id`', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if ftable isn't mixed */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->get->table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->config['session_data'][$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_rel($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_rel($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->config['charts_canvas_width'] / 2, $this->config['charts_canvas_height'] / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_totals(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Check if user has READ access to total field */
		if ($chart['field_tot'] && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $chart['field_tot']))
			$chart['field_tot'] = NULL; /* Remove the total field */

		/* Check permissions for abscissa */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $chart['fields'][0]))
			return false; /* If there are no permissions to read abcissa field, the chart cannot be displayed for this user */

		/* Check permissions for the remaining fields */
		$fields_filtered = array($chart['fields'][0]);
		foreach (array_slice($chart['fields'], 1) as $field) {
			if ($this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name'], $field))
				array_push($fields_filtered, $field);
		}
		$chart['fields'] = $fields_filtered;

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Check if there's a total field set */
		if ($chart['field_tot']) {
			$this->db->select(implode(',', $chart['fields']) . ',' . $chart['field_tot'] . ' AS total');
		} else {
			$this->db->select(implode(',', $chart['fields']));
		}
		$this->db->from($this->config['name']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on $_table_row_filtering_config */
		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		/* Check if there are additional filters to append to the query WHERE component */
		if ($chart['result_query']) {
			/* Join all relationships */
			$this->table->join_rels($this->config['name'], 'left');

			/* Decode and decipher the query */
			$result_query = gzuncompress($this->encrypt->decode($this->ndphp->safe_b64decode(rawurldecode($chart['result_query']))));

			$matches = NULL;

			/* Get the WHERE component of the query */
			if (preg_match('/^.+\s+WHERE\s+(.+)\s+GROUP BY.+$/', $result_query, $matches)) {
				/* Append the WHERE component to the current query. */
				/* NOTE: The 1 = 1 is a fail safe, in case the WHERE wasn't initialized yet */
				$this->db->where('1 =', '1', false);
				$this->db->where_append(' AND (' . $matches[1] . ')');
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_totals($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_totals($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->config['charts_canvas_width'] / 2, $this->config['charts_canvas_height'] / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));

				/* Draw Legend */
				$pimage->drawLegend($this->config['charts_canvas_width'] - 80, 20, array(
					'R'		=> 255,
					'G'		=> 255,
					'B'		=> 255,
					'Alpha'	=> 0
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	private function _chart_generate_foreign_totals(&$chart) {
		/* Check if there is READ permission for this controller table */
		if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $this->config['name']))
			return false;

		/* Check if there is READ permission on foreign table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable']))
				return false;
		}

		/* NOTE: We're not checking 'field_ts' permissions. This is a feature: You can still filter by time range,
		 * even if the user cannot read the time field.
		 */

		/* Check if user has READ access to total field */
		if ($chart['field_tot'] && !$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable'], $chart['field_tot']))
			$chart['field_tot'] = NULL; /* Remove the total field */

		/* Check permissions for abscissa */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			if (!$this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable'], $chart['fields'][0]))
				return false; /* If there are no permissions to read abcissa field, the chart cannot be displayed for this user */
		}

		/* Grant that the user requesting this chart has permissions to read $entry_id on $this->config['name'] table. */
		$this->db->select('id');
		$this->db->from($this->config['name']);
		$this->db->where('id', $chart['entry_id']);

		if ($this->config['table_row_filtering']) {
			$table_fields = $this->get->table_fields($this->config['name']);

			foreach ($this->config['table_row_filtering_config'] as $field => $svar) {
				if (in_array($field, $table_fields))
					$this->db->where($field, $this->config['session_data'][$svar]);
			}
		}

		$qfilter = $this->db->get();

		if (!$qfilter->num_rows())
			return false;

		/* Check permissions for the remaining fields if this isn't a mixed table */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fields_filtered = array($chart['fields'][0]);

			foreach (array_slice($chart['fields'], 1) as $field) {
				if ($this->security->perm_check($this->config['security_perms'], $this->security->perm_read, $chart['ftable'], $field))
					array_push($fields_filtered, $field);
			}

			$chart['fields'] = $fields_filtered;
		}

		/* Check if there's anything to plot */
		if (!count($chart['fields']))
			return false;

		/* Check if there's a total field set */
		if ($chart['field_tot']) {
			$this->db->select(implode(',', $chart['fields']) . ',' . $chart['field_tot'] . ' AS total');
		} else {
			$this->db->select(implode(',', $chart['fields']));
		}
		$this->db->from($chart['ftable']);
		$this->db->where($this->config['name'] . '_id', $chart['entry_id']);

		/* Check if we need to narrow our result based on start timestamp value */
		if ($chart['start_ts']) {
			if (strstr($chart['start_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['start_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' >=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' >=', $chart['start_ts']);
			}
		}

		/* Check if we need to narrow our result based on end timestamp value */
		if ($chart['end_ts']) {
			if (strstr($chart['end_ts'], ' ')) {
				/* If there's a space in the time value, then we'll search for interval formats */
				$interval_fields = $this->get->interval_fields($chart['end_ts']);

				if (!$interval_fields)
					$this->response->code('500', NDPHP_LANG_MOD_INVALID_SEARCH_INTERVAL_FMT, $this->config['default_charset'], !$this->request->is_ajax());

				$this->db->where($chart['field_ts'] . ' <=', 'NOW() ' . $interval_fields[0] . ' INTERVAL ' . $interval_fields[1] . ' ' . $interval_fields[2]);
			} else {
				/* Otherwise, it is expected that the value is numeric and represent a timestamp since epoch (1970-01-01 00:00:00 UTC) */
				$this->db->where($chart['field_ts'] . ' <=', $chart['end_ts']);
			}
		}

		/* Also filter rows based on foreign controller $_table_row_filtering_config if table isn't mixed */
		if (substr($chart['ftable'], 0, 6) != 'mixed_') {
			$fctrl = $this->access->controller($chart['ftable']);

			if ($fctrl->config['table_row_filtering']) {
				$table_fields = $this->get->table_fields($chart['ftable']);

				/* NOTE: here, we fetch the configuration of the foreign controller, not the filtering config of $this controller  */
				foreach ($fctrl->config['table_row_filtering_config'] as $field => $svar) {
					if (in_array($field, $table_fields))
						$this->db->where($chart['ftable'] . '.' . $field, $this->config['session_data'][$svar]);
				}
			}
		}

		/* Fetch data (effectively) */
		$q = $this->db->get();

		/* Check if there's anything to generate */
		if (!$q->num_rows())
			return false;

		/* Build chart dataset object */
		$dataset = $this->chart_build_dataset_foreign_totals($chart, $q);

		/* Build chart image object */
		$pimage = $this->chart_build_image_foreign_totals($chart, $dataset);

		switch ($chart['geometry']) {
			case 'pie': {
				/* Pie charts require a specific pPie class instantiated */
				$pie = new pPie($pimage, $dataset);

				/* Draw the pie */
				$pie->draw2DPie($this->config['charts_canvas_width'] / 2, $this->config['charts_canvas_height'] / 2, array(
					'DrawLabels'	=> true,
					'Border'		=> true,
					'RecordImageMap' => true
				));
			} break;

			case 'bar': {
				/* Set the chart area within canvas */
				$pimage->setGraphArea($this->config['charts_graph_area']['X1'], $this->config['charts_graph_area']['Y1'], $this->config['charts_graph_area']['X2'], $this->config['charts_graph_area']['Y2']);

				/* Draw scale... */
				$pimage->drawScale(array(
					'DrawXLines'	=> false,
					'DrawYLines' 	=> ALL,
					'GridTicks'		=> 0,
					'GridR'			=> 200,
					'GridG'			=> 200,
					'GridB'			=> 200
				));

				/* Dar the bar chart */
				$pimage->drawBarChart(array(
					'RecordImageMap' => true
				));

				/* Draw Legend */
				$pimage->drawLegend($this->config['charts_canvas_width'] - 80, 20, array(
					'R'		=> 255,
					'G'		=> 255,
					'B'		=> 255,
					'Alpha'	=> 0
				));
			} break;

			default: {
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_GEOMETRY . ': ' . $chart['geometry'], $this->config['default_charset'], !$this->request->is_ajax());
			}
		}

		/* All good... return the image object */
		return $pimage;
	}

	protected function _chart_render_image($chart_array, $chart_id) {
		$chart_array[$chart_id]['pimage']->Stroke();
	}

	protected function _chart_generate_generic(&$chart_array, $chart_id) {
		$chart = $chart_array[$chart_id];

		/* Check if there are any custom chart types defined */
		if (in_array($chart['type'], $this->config['charts_types'])) {
			/* Grant that the required handlers to generate the chart exist */
			if (!method_exists($this, '_chart_generate_' . $chart['type']))
				$this->response->code('500', NDPHP_LANG_MOD_UNDEFINED_METHOD . ': _chart_generate_' . $chart['type'] . '().', $this->config['default_charset'], !$this->request->is_ajax());

			/* Grant that the chart type only contains alphanumeric and underscore characters */
			if (!preg_match('/^[A-Za-z0-9\_]+$/', $chart['type']))
				$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_TYPE_NAME, $this->config['default_charset'], !$this->request->is_ajax());

			/* Generate the chart based on the custom handlers */
			eval('$chart_array[$chart_id][\'pimage\'] = $this->_chart_generate_' . $chart['type'] . '($chart);');

			/* Check if the chart was generated */
			if ($chart_array[$chart_id]['pimage'] === false)
				return false;
		} else {
			/* The requested chart type is undefined or invalid */
			$this->response->code('500', NDPHP_LANG_MOD_INVALID_CHART_TYPE . ': ' . $chart['type'], $this->config['default_charset'], !$this->request->is_ajax());
		}

		/* All good */
		return true;
	}

	protected function _chart_publish_generic(&$chart_array, $chart_id = 0, $entry_id = NULL, $refresh_rand = NULL, $result_query = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		/* NOTE: The $refresh_rand argument should be a random value in order to force browsers to reload the image */

		/* Setup charts */
		$this->_charts_config();

		/* Check if the requested chart id is defined */
		if ($chart_id >= count($chart_array))
			$this->response->code('404', NDPHP_LANG_MOD_UNDEFINED_CHART_ID, $this->config['default_charset'], !$this->request->is_ajax());

		/* Set chart id to chart object */
		$chart_array[$chart_id]['id'] = $chart_id;

		/* Is this a imagemap request? */
		if ($imagemap !== NULL)
			$chart_array[$chart_id]['imagemap_request'] = true;

		/* Check if there's a result query that will apply additional filters on the chart (not available for imported nor foreign charts) */
		if ($result_query !== NULL && !$chart_array[$chart_id]['imported'])
			$chart_array[$chart_id]['result_query'] = $result_query;

		/* Check if we need to override the default start timestamp */
		if ($start_ts !== NULL)
			$chart_array[$chart_id]['start_ts'] = $start_ts;

		/* Check if we need to override the default end timestamp */
		if ($end_ts !== NULL)
			$chart_array[$chart_id]['end_ts'] = $end_ts;

		if ($entry_id !== NULL)
			$chart_array[$chart_id]['entry_id'] = $entry_id;

		/* If the chart is imported, fetch it from the foreign controller */
		if ($chart_array[$chart_id]['imported']) {
			/* TODO: FIXME: Currently, we need to perform a REST API JSON call to import the chart... This is likely to be improved in the future */
			$this->db->select('apikey AS _apikey,id AS _userid');
			$this->db->from('users');
			$this->db->where('id', $this->config['session_data']['user_id']);
			$q = $this->db->get();
			$rest_auth = json_encode($q->row_array());

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, base_url() . 'index.php/' . $chart_array[$chart_id]['imp_ctrl'] . '/chart_publish/' . $chart_array[$chart_id]['imp_id'] . '/' . mt_rand(100000, 999999));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $rest_auth);
			curl_exec($ch);
			curl_close($ch);
		} else {
			/* Otherwise... Process all data and generate all the objects for $chart_id */
			if ($this->_chart_generate_generic($chart_array, $chart_id) === false)
				return false;

			/* Render the chart and deliver it to the client */
			$this->_chart_render_image($chart_array, $chart_id);
		}
	}

	protected function _chart_render_nodata($title) {
		/* FIXME: This method shall receive the entire $chart array, not just the title.
		 * Settings such as non-default chart width and height should be retrieve from chart array.
		 */

		/* Set font sizes */
		$font_size_title  = 3;
		$font_size_nodata = 10;

		/* Set the no data warning message */
		$nodata_msg = NDPHP_LANG_MOD_EMPTY_DATA;

		/* Set content type header */
		$this->response->header('Content-Type', 'image/png');

		/* Create canvas */
		$nodata_img = imagecreate($this->config['charts_canvas_width'], $this->config['charts_canvas_height']);

		/* Set colors */
		$background_color = imagecolorallocate($nodata_img, 250, 250, 250);
		$color = imagecolorallocate($nodata_img, 180, 180, 180);

		/* Plot strings */
		imagestring($nodata_img, $font_size_title,  ($this->config['charts_canvas_width'] / 2) - ((imagefontwidth($font_size_title)  * strlen($title)) / 2), 30, $title, $color);
		imagestring($nodata_img, $font_size_nodata, ($this->config['charts_canvas_width'] / 2) - ((imagefontwidth($font_size_nodata) * strlen($nodata_msg)) / 2), ($this->config['charts_canvas_height'] / 2) - (imagefontheight($font_size_nodata) / 2), $nodata_msg, $color);

		/* Render image */
		imagepng($nodata_img);

		/* Release resources */
		imagecolordeallocate($nodata_img, $color);
		imagecolordeallocate($nodata_img, $background_color);
		imagedestroy($nodata_img);
	}

	public function chart_publish($chart_id = 0, $refresh_rand = NULL, $result_query = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		if ($result_query == 'NULL')
			$result_query = NULL;

		if ($imagemap == 'NULL')
			$imagemap = NULL;

		if ($this->_chart_publish_generic($this->config['charts'], $chart_id, NULL, $refresh_rand, $result_query, $imagemap, $start_ts, $end_ts) === false) {
			/* No data */
			$this->_chart_render_nodata($this->config['charts'][$chart_id]['title']);
		}
	}

	public function chart_foreign_publish($chart_id = 0, $entry_id = NULL, $refresh_rand = NULL, $imagemap = NULL, $start_ts = NULL, $end_ts = NULL) {
		if ($imagemap == 'NULL')
			$imagemap = NULL;

		if ($this->_chart_publish_generic($this->config['charts_foreign'], $chart_id, $entry_id, $refresh_rand, NULL, $imagemap, $start_ts, $end_ts) === false) {
			/* No data */
			$this->_chart_render_nodata($this->config['charts_foreign'][$chart_id]['title']);
		}
	}

	public function count_charts() {
		return count($this->config['charts']);
	}

	public function count_charts_foreign() {
		return count($this->config['charts_foreign']);
	}
}