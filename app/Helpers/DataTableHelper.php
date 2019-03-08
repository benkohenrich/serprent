<?php
/**
 * Created            18/01/2017 17:26
 * @author            Jakub Dubec <jakub.dubec@gmail.com>
 */

namespace Helpers;


use Appendix\Core\System;
use Appendix\Libraries\Input;

abstract class DataTableHelper
{

	public static function create_filter_array()
	{
		$per_page 					= Input::instance()->get('length', System::config("admin.pagination.per_page"));

		$filter 					= [
			'offset' 				=> Input::instance()->get('start', 0) / $per_page,
			'limit' 				=> $per_page
		];

		$columns 					= Input::instance()->get('columns', []);

		foreach ($columns as $column)
		{
			if ($column['searchable'] && !is_null($column['search']['value']))
			{
				$filter[$column['name']] = $column['search']['value'];
			}
		}

		 $filters					= Input::instance()->get('filters', []);

		foreach ($filters as $f)
		{
			if (!empty($filter[$f['name']]))
			{
				if (is_array($filter[$f['name']]))
				{
					$filter[$f['name']][] 	= $f['value'];
				}
				else
				{
					$value 					= $filter[$f['name']];
					
					$filter[$f['name']] 	= [];
					$filter[$f['name']][] 	= $value;
					$filter[$f['name']][] 	= $f['value'];
				}

			}
			else
			{
				$filter[$f['name']] = $f['value'];
			}
		}

		return $filter;
	}

	public static function create_ordering_array()
	{
		$columns 					= Input::instance()->get('columns', []);
		$ordering 					= Input::instance()->get('order');

		if (empty($ordering) || !is_array($ordering) || empty($columns))
			return [];

		$ordering 					= $ordering[0];

		$ordering 					= [
			'column' 				=> $columns[$ordering['column']]['name'],
			'direction' 			=> $ordering['dir']
		];

		return $ordering;
	}

	public static function create_response($data, $filtered_records, $total_records, $filter = null, $ordering = null)
	{
		return [
			'draw' 					=> Input::instance()->get('draw'),
			'recordsTotal' 			=> $total_records,
			'recordsFiltered' 		=> $filtered_records,
			'data' 					=> $data,
			'filter' 				=> empty($filter) ? self::create_filter_array() : $filter,
			'ordering' 				=> empty($ordering) ? self::create_ordering_array() : $ordering
		];
	}

}