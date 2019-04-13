<?php

namespace Libraries\Tables;

class RowBuilder
{
	private $row = [];

	public function add($column)
	{
		if (empty($column) AND is_null($column))
			return;

		$this->row[] = $column;
	}

	public function data()
	{
		return $this->row;
	}
}
