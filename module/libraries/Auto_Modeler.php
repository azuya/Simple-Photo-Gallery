<?php
/**
* Auto_Modeler
*
* @package        Auto_Modeler
* @author         Jeremy Bush
* @copyright      (c) 2010 Jeremy Bush
* @license        http://www.opensource.org/licenses/isc-license.txt
*/
class Auto_Modeler_Core extends Model implements ArrayAccess
{
	// The database table name
	protected $table_name = '';

	// The database fields
	protected $data = array();

	// Validation rules in a 'field' => 'rules' array
	protected $rules = array();
	protected $callbacks = array();

	protected $validation = array();

	protected $validated = FALSE;

	protected $lang = 'form_errors';

	public function __construct($id = NULL)
	{
		parent::__construct();

		if ($id != NULL)
		{
			// try and get a row with this ID
			$data = db::build()->select('*')->from($this->table_name)->where('id', '=', $id)->execute($this->db)->as_array();

			// try and assign the data
			if (count($data) == 1 AND $data = $data->current())
			{
				foreach ($data as $key => $value)
					$this->data[$key] = $value;
			}
		}
	}

	// Magic __get() method
	public function __get($key)
	{
		return isset($this->data[$key]) ? $this->data[$key] : NULL;
	}

	// Magic __set() method
	public function __set($key, $value)
	{
		if (array_key_exists($key, $this->data))
		{
			$this->data[$key] = $value;
			$this->validated = FALSE;
		}
	}

	public function __sleep()
	{
		// Store only information about the object without db property
		return array_diff(array_keys(get_object_vars($this)), array('db'));
	}

	public function __wakeup()
	{
		$this->db = Database::instance($this->db);
	}

	public function as_array()
	{
		return $this->data;
	}

	// Useful for one line method chaining
	// $model - The model name to make
	// $id - an id to create the model with
	public static function factory($model = FALSE, $id = FALSE)
	{
		$model = empty($model) ? __CLASS__ : ucfirst($model).'_Model';
		return new $model($id);
	}

	// Allows for setting data fields in bulk
	// $data - associative array to set $this->data to
	public function set_fields($data)
	{
		foreach ($data as $key => $value)
		{
			if (array_key_exists($key, $this->data))
				$this->$key = $value;
		}
	}

	public function errors($lang = NULL)
	{
		return $this->validation != NULL ? $this->validation->errors($lang) : array();
	}

	public function valid($validation = NULL)
	{
		$data = $validation instanceof Validation ? $validation->copy($validation->as_array()+$this->data) : Validation::factory($this->data)->pre_filter('trim');

		foreach ($this->rules as $field => $rule)
		{
			foreach ($rule as $sub_rule)
				$data->add_rules($field, $sub_rule);
		}

		foreach ($this->callbacks as $field => $callback)
			$data->add_callbacks($field, array($this, $callback));

		if ($data->validate())
		{
			$this->validation = NULL;
			return $this->validated = TRUE;
		}
		else
		{
			$this->validation = $data;
			$errors = View::factory('form_errors')->set(array('errors' => $data->errors($this->lang)));
			return array('string' => $errors->render(), 'errors' => $data->errors($this->lang));
		}
	}

	// Saves the current object
	public function save($validation = NULL)
	{
		$status = $this->validated ? TRUE : $this->valid($validation);

		if ($status === TRUE)
		{
			if ($this->data['id']) // Do an update
			{
				//die(Kohana::debug());
				return count(db::update($this->table_name, array_diff_assoc($this->data, array('id' => $this->data['id'])), array(array('id', '=', $this->data['id'])))->execute($this->db));
			}
			else // Do an insert
			{
				$id = db::insert($this->table_name, $this->data)->execute($this->db)->insert_id();
				return ($this->data['id'] = $id);
			}
		}

		throw new Auto_Modeler_Exception('auto_modeler.validation_error', $status['string'], $status['errors']);
	}

	// Deletes the current record and destroys the object
	public function delete()
	{
		if ($this->data['id'])
		{
			return db::delete($this->table_name, array(array('id', '=', $this->data['id'])))->execute($this->db);
		}
	}

	// Fetches all rows in the table
	// $order_by - the ORDER BY value to sort by
	// $direction - the direction to sort
	public function fetch_all($order_by = 'id', $direction = 'ASC')
	{
		return db::build()->select('*')->from($this->table_name)->order_by($order_by, $direction)->execute($this->db)->as_object(inflector::singular(ucwords($this->table_name)).'_Model');
	}

	// Does a basic search on the table.
	// $where - the where clause to search on
	// $order_by - the ORDER BY value to sort by
	// $direction - the direction to sort
	// $type - pass 'or' here to do a orwhere
	public function fetch_where($where = array(), $order_by = 'id', $direction = 'ASC', $type = 'and')
	{
		$function = $type.'_where';
		return db::build()->select('*')->from($this->table_name)->$function($where)->order_by($order_by, $direction)->execute($this->db)->as_object(inflector::singular(ucwords($this->table_name)).'_Model');
	}

	// Returns an associative array to use in dropdowns and other widgets
	// $key - the key column of the array
	// $display - the value column of the array
	// $order_by - the direction to sort
	// $where - an optional where array to be passed if you don't want every record
	public function select_list($key, $display, $order_by = 'id', $where = array())
	{
		$rows = array();

		$query = empty($where) ? $this->fetch_all($order_by) : $this->fetch_where($where, $order_by);

		$array_display = is_array($display);

		foreach ($query as $row)
		{
			if ($array_display)
			{
				$display_str = array();
				foreach ($display as $text)
					$display_str[] = $row->$text;
				$rows[$row->$key] = implode(' - ', $display_str);
			}
			else
				$rows[$row->$key] = $row->$display;
		}

		return $rows;
	}

	public function has_attribute($key)
	{
		return array_key_exists($key, $this->data);
	}

	// Array Access Interface
	public function offsetExists($key)
	{
		return array_key_exists($key, $this->data);
	}

	public function offsetSet($key, $value)
	{
		$this->__set($key, $value);
	}

	public function offsetGet($key)
	{
		return $this->__get($key);
	}

	public function offsetUnset($key)
	{
		$this->data[$key] = NULL;
	}
}

class Auto_Modeler_Exception extends Kohana_User_Exception
{
	public $errors = array();

	public function __construct($title, $message, $errors)
	{
		parent::__construct($title, $message);
		$this->errors = $errors;
	}
}