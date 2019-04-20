<?php
/**
 * Created            15/04/16 15:02
 * @author            Jakub Dubec <jakub.dubec@gmail.com>
 */

namespace Helpers;


use ActiveRecord\DateTime;
use Appendix\Core\I18n;
use Appendix\Core\Model;
use Models\Summarizable;

abstract class ModelHelper
{

	/**
	 * Ako vstup ziska pole modelov. Ulohou metody je pripravit retazec z IDciek do nasledovneho formatu: 14,15,16,17
	 * Vyuzitie je pri stavani prikazu WHERE unicorn_id IN (14, 15, 16, 17)
	 *
	 * @param Model[] $items
	 *
	 * @return string
	 */
	public static function to_id_string($items)
	{
		$IDs = [];

		if (empty($items))
		{
			$items = [];
		}

		foreach ($items as $item)
		{
			$IDs[] = $item->id;
		}
		return implode(',', $IDs);
	}

	/**
	 * Ako vstup ziska pole modelov. Ulohou je pripravit pole z IDciek
	 * napr.: [10, 12, 666]
	 * @param Model[] $items
	 *
	 * @return array
	 */
	public static function to_id_array($items)
	{
		$IDs = [];

		if (empty($items))
		{
			$items = [];
		}

		foreach ($items as $item)
			$IDs[] = $item->id;

		return $IDs;
	}

	/**
	 * @return array
	 */
	public static function prepare($object) {
		if (is_array($object)) {
			$result = [];
			foreach  ($object as $record) {
				$result[] = $record->summary();
				$record = null;
			}
			$object = null;
			return $result;
		}
		else {
			return $object != null ? $object->summary() : null;
		}
	}

	/**
	 *
	 * Metoda extrahuje z pola modelov alebo z modelu zadany stlpec a vrati ho ako array
	 *
	 * @param string $column
	 * @param Model[]|Model $objects
	 *
	 * @return array
	 */
	public static function create_array_from_column($column, $objects)
	{
		$result = [];

		if (is_array($objects))
		{
			foreach ($objects as $object)
			{
				if ($object instanceof Model)
				{

					if (array_key_exists($column, $object->attributes()))
					{
						$result[] = $object->$column;
					}

				}
			}
		}
		elseif ($objects instanceof Model)
		{
			$result[] = $objects->$column;
		}

		return $result;
	}

	public static function prepare_for_select($keys, $localization_prefix)
	{
		$result = [];

		foreach ($keys as $key)
		{
			$result[] 				= [
				'id' 				=> $key,
				'name' 				=> I18n::load($localization_prefix . '.' . $key)
			];
		}

		return $result;
	}

	public static function diff($old, $new)
	{
		$difference = [];

		foreach ($old->attributes() as $key => $old_attributes)
		{
			foreach ($new->attributes() as $key2 => $new_attributes)
			{
				if ($key === $key2)
				{
					if ($new_attributes instanceof DateTime AND $old_attributes instanceof  DateTime)
					{
						if ($new_attributes->format('Y-m-d H:i:s') !== $old_attributes->format('Y-m-d H:i:s'))
						{
							$difference['old'][$key] = $old_attributes->format('Y-m-d H:i:s');
							$difference['new'][$key] = $new_attributes->format('Y-m-d H:i:s');
						}
					}
					else
					{
						if ($new_attributes !== $old_attributes)
						{
							$difference['old'][$key] = $old_attributes;
							$difference['new'][$key] = $new_attributes;
						}
					}

				}
			}
		}

		return $difference;
	}

}