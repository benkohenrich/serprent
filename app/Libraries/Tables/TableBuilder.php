<?php

namespace Libraries\Tables;

class TableBuilder
{
	private $table = [];

	public function add($row)
	{
		if (empty($row) AND is_null($row))
			return;

		$this->table[] = $row;
	}

	public function data()
	{
		return $this->table;
	}
}
