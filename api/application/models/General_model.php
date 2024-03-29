<?php
defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{
	public $tableName = null;
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * @param string // Tablo Adı
	 * @param array // Where Parametreleri key => value
	 * @param array // Tablo Adı:tabloadı Eşleşeceği ID: c.1tabloid=p.2tabloid Eşleşme Tipi : Left  
	 */
	public function get($tableName = null, $select = null, $where = [], $joinTable = [], $likes = [], $wherein = [], $distinct = null, $groupBy = null)
	{
		if (!empty($select)) :
			$this->db->select($select);
		endif;
		if (!empty($joinTable)) :
			foreach ($joinTable as $key => $value) :
				$this->db->join($key, $value[0], $value[1]);
			endforeach;
		endif;
		if (!empty($distinct)) :
			$this->db->distinct();
		endif;
		$this->db->where($where);
		if (!empty($wherein)) :
			foreach ($wherein as $key => $value) :
				$this->db->where_in($key, $value);
			endforeach;
		endif;
		if (!empty($likes)) :
			$i = 0;
			$j = 0;
			foreach ($likes as $key => $value) :
				if (!is_array($value)) :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
						$this->db->like($key, $value, 'both');
						$this->db->or_like($key, strto("lower", $value), 'both');
						$this->db->or_like($key, strto("lower|upper", $value), 'both');
						$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
					else :
						$this->db->or_like($key, $value, 'both');
						$this->db->or_like($key, strto("lower", $value), 'both');
						$this->db->or_like($key, strto("lower|upper", $value), 'both');
						$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
					endif;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				else :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
					endif;
					foreach ($value as $k => $v) :
						foreach ($v as $kk => $vv) :
							// first loop
							if ($j === 0) :

								$this->db->like($kk, $vv, 'both');
								$this->db->or_like($kk, strto("lower", $vv), 'both');
								$this->db->or_like($kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucfirst", $vv), 'both');
							else :
								$this->db->or_like($kk, $vv, 'both');
								$this->db->or_like($kk, strto("lower", $vv), 'both');
								$this->db->or_like($kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucfirst", $vv), 'both');
							endif;
							$j++;
						endforeach;
					endforeach;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				endif;
			endforeach;
		endif;
		if (!empty($groupBy)) :
			$this->db->group_by($groupBy);
		endif;
		$query = $this->db->get($tableName)->row();
		return $query;
	}

	public function get_all($tableName = null, $select = null, $order = null, $where = [],  $likes = [], $joinTable = [], $limit = [], $wherein = [], $distinct = null, $groupBy = null)
	{
		if (!empty($select)) :
			$this->db->select($select);
		endif;
		if (!empty($joinTable)) :
			foreach ($joinTable as $key => $value) :
				$this->db->join($key, $value[0], $value[1]);
			endforeach;
		endif;
		if (!empty($distinct)) :
			$this->db->distinct();
		endif;
		//$this->db->where(["id!=" => null]);
		$this->db->where($where);
		if (!empty($wherein)) :
			foreach ($wherein as $key => $value) :
				$this->db->where_in($key, $value);
			endforeach;
		endif;
		if (!empty($likes)) :
			$i = 0;
			$j = 0;
			foreach ($likes as $key => $value) :
				if (!is_array($value)) :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
						$this->db->like($key, $value, 'both');
						$this->db->or_like($key, strto("lower", $value), 'both');
						$this->db->or_like($key, strto("lower|upper", $value), 'both');
						$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
					else :
						$this->db->or_like($key, $value, 'both');
						$this->db->or_like($key, strto("lower", $value), 'both');
						$this->db->or_like($key, strto("lower|upper", $value), 'both');
						$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
					endif;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				else :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
					endif;
					foreach ($value as $k => $v) :
						foreach ($v as $kk => $vv) :
							// first loop
							if ($j === 0) :

								$this->db->like($kk, $vv, 'both');
								$this->db->or_like($kk, strto("lower", $vv), 'both');
								$this->db->or_like($kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucfirst", $vv), 'both');
							else :
								$this->db->or_like($kk, $vv, 'both');
								$this->db->or_like($kk, strto("lower", $vv), 'both');
								$this->db->or_like($kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($kk, strto("lower|ucfirst", $vv), 'both');
							endif;
							$j++;
						endforeach;
					endforeach;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				endif;
			endforeach;
		endif;
		if (!empty($groupBy)) :
			$this->db->group_by($groupBy);
		endif;
		$this->db->order_by($order);
		if (!empty($limit)) :
			if (!empty($limit[1])) :
				$this->db->limit($limit[0], $limit[1]);
			else :
				$this->db->limit($limit[0]);
			endif;
		endif;
		$query = $this->db->get($tableName)->result();
		return $query;
	}

	public function add($tableName = null, $data = array())
	{
		$this->db->insert($tableName, $data);
		return $this->db->insert_id();
	}

	public function update($tableName = null, $where = [], $data = array())
	{
		return $this->db->where($where)->update($tableName, $data);
	}

	public function delete($tableName = null, $where = [])
	{
		return $this->db->where($where)->delete($tableName);
	}

	public function updateBulk($tableName = null, $wherein = null, $data = [])
	{
		return $this->db->where_in($wherein, $data)->update($tableName, $data);
	}

	public function deleteBulk($tableName = null, $wherein = null, $data = [])
	{
		return $this->db->where_in($wherein, $data)->delete($tableName);
	}

	public function rowCount($tableName = null, $where = [], $likes = [], $joins = [], $wherein = [], $distinct = null, $groupBy = null, $select = null)
	{
		if (!empty($select)) :
			$this->db->select($select);
		endif;
		if (!empty($joins)) :
			foreach ($joins as $key => $value) :
				$this->db->join($key, $value[0], $value[1]);
			endforeach;
		endif;
		$this->db->where($where);
		if (!empty($wherein)) :
			foreach ($wherein as $key => $value) :
				$this->db->where_in($key, $value);
			endforeach;
		endif;
		if (!empty($likes)) :
			$i = 0;
			foreach ($likes as $key => $value) :
				// first loop
				if ($i === 0) :
					// open bracket
					$this->db->group_start();
					$this->db->like($key, $value, 'both');
					$this->db->or_like($key, strto("lower", $value), 'both');
					$this->db->or_like($key, strto("lower|upper", $value), 'both');
					$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
					$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
					$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
				else :
					$this->db->or_like($key, $value, 'both');
					$this->db->or_like($key, strto("lower", $value), 'both');
					$this->db->or_like($key, strto("lower|upper", $value), 'both');
					$this->db->or_like($key, strto("lower|ucwords", $value), 'both');
					$this->db->or_like($key, strto("lower|capitalizefirst", $value), 'both');
					$this->db->or_like($key, strto("lower|ucfirst", $value), 'both');
				endif;

				// last loop
				if (count($likes) - 1 == $i) :
					// close bracket
					$this->db->group_end();
				endif;
				$i++;
			endforeach;
		endif;
		if (!empty($distinct)) :
			$this->db->distinct();
		endif;
		if (!empty($groupBy)) :
			$this->db->group_by($groupBy);
		endif;
		$query = $this->db->count_all_results($tableName);
		return $query;
	}

	public function replace($tableName = null, $data = [])
	{
		$this->db->replace($tableName, $data);
	}

	public function insert_batch($tableName = null, $data = [])
	{
		$this->db->insert_batch($tableName, $data);
	}

	public function countFiltered($tableName = null, $where = [], $postData = null, $select = null, $order = null,  $likes = [], $joinTable = [], $limit = [], $wherein = [], $distinct = null, $groupBy = null)
	{
		$this->_get_datatables_query($tableName, $postData, $select, $order, $likes, $joinTable, $limit, $wherein, $distinct, $groupBy);
		$query = $this->db->where($where)->get();
		return $query->num_rows();
	}
	public function getRows($tableName = null, $where = [], $postData = [], $select = null, $order = null,  $likes = [], $joinTable = [], $limit = [], $wherein = [], $distinct = null, $groupBy = null)
	{
		if (!empty($where)) :
			$this->db->where($where);
		endif;
		$this->_get_datatables_query($tableName, $postData, $select, $order, $likes, $joinTable, $limit, $wherein, $distinct, $groupBy);
		if (@$postData['start'] >= 0) :
			$this->db->limit(@$postData['perPage'], @$postData['start']);
		endif;
		return $this->db->get()->result();
	}
	private function _get_datatables_query($tableName = null, $postData = [], $select = null, $order = null,  $likes = [], $joinTable = [], $limit = [], $wherein = [], $distinct = null, $groupBy = null)
	{
		if (!empty($select)) :
			$this->db->select($select);
		endif;
		if (!empty($joinTable)) :
			foreach ($joinTable as $key => $value) :
				$this->db->join($key, $value[0], $value[1]);
			endforeach;
		endif;
		if (!empty($distinct)) :
			$this->db->distinct();
		endif;
		$this->db->where([$tableName . ".id!=" => null]);
		if (!empty($wherein)) :
			foreach ($wherein as $key => $value) :
				$this->db->where_in($key, $value);
			endforeach;
		endif;
		if (!empty($likes)) :
			$i = 0;
			$j = 0;
			foreach ($likes as $key => $value) :
				if (!is_array($value)) :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
						$this->db->like($tableName . "." . $key, $value, 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|upper", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|ucfirst", $value), 'both');
					else :
						$this->db->or_like($tableName . "." . $key, $value, 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|upper", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|ucwords", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|capitalizefirst", $value), 'both');
						$this->db->or_like($tableName . "." . $key, strto("lower|ucfirst", $value), 'both');
					endif;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				else :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
					endif;
					foreach ($value as $k => $v) :
						foreach ($v as $kk => $vv) :
							// first loop
							if ($j === 0) :

								$this->db->like($tableName . "." . $kk, $vv, 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|ucfirst", $vv), 'both');
							else :
								$this->db->or_like($tableName . "." . $kk, $vv, 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|upper", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|ucwords", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|capitalizefirst", $vv), 'both');
								$this->db->or_like($tableName . "." . $kk, strto("lower|ucfirst", $vv), 'both');
							endif;
							$j++;
						endforeach;
					endforeach;

					// last loop
					if (count($likes) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
					$i++;
				endif;
			endforeach;
		endif;
		$this->db->from($tableName);
		$i = 0;
		// loop searchable columns
		if (!empty($this->get_field($tableName))) :
			foreach ($this->get_field($tableName) as $item) :
				// if datatable send POST for search

				if (!empty($postData['searchTerm'])) :
					// first loop
					if ($i === 0) :
						// open bracket
						$this->db->group_start();
						$this->db->like($tableName . "." . $item, $postData['searchTerm'], 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|upper", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|ucwords", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|capitalizefirst", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|ucfirst", $postData['searchTerm']), 'both');
					else :
						$this->db->or_like($tableName . "." . $item, $postData['searchTerm'], 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|upper", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|ucwords", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|capitalizefirst", $postData['searchTerm']), 'both');
						$this->db->or_like($tableName . "." . $item, strto("lower|ucfirst", $postData['searchTerm']), 'both');
					endif;
					// last loop
					if (count($this->get_field($tableName)) - 1 == $i) :
						// close bracket
						$this->db->group_end();
					endif;
				endif;
				$i++;
			endforeach;
		endif;
		if (!empty($groupBy)) :
			$this->db->group_by($groupBy);
		endif;
		if (isset($postData['sort'])) :
			foreach ($postData['sort'] as $sKey => $sValue) :
				if ($sValue["type"] != "none") :
					$this->db->order_by($sValue["field"], $sValue["type"]);
				endif;
			endforeach;
		endif;
		$this->db->order_by($order);
		if (!empty($limit)) :
			if (!empty($limit[1])) :
				$this->db->limit($limit[0], $limit[1]);
			else :
				$this->db->limit($limit[0]);
			endif;
		endif;
	}

	function get_field($tableName = null)
	{
		$result = $this->db->list_fields($tableName);
		foreach ($result as $field) :
			$data[] = $field;
		endforeach;
		return $data;
	}
}
