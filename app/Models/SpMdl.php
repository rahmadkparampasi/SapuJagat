<?php

namespace App\Models;

use CodeIgniter\Model;


class SpMdl extends Model
{
    protected $table;
    protected $tableName;
    protected $useTimestamps = true;
    protected $db;
    protected $idEx;

    public function __construct($tableName, $idEx)
    {

        $this->db = \Config\Database::connect();
        $this->tableName = $tableName;
        $this->idEx = $idEx;
    }

    public function getAll($typeGet = '', $slct = '*', $idExa = [], $order = [], $join = [], $like = [], $group = [])
    {
        $builder = $this->db->table($this->tableName);
        $builder->select($slct);
        for ($i = 0; $i < count($join); $i++) {
            $builder->join($join[$i]['tableName'], $join[$i]['string'], $join[$i]['type']);
        }

        for ($i = 0; $i < count($idExa); $i++) {
            $builder->where($idExa[$i]['idEx'], $idExa[$i]['idExV']);
        }
        for ($i = 0; $i < count($like); $i++) {
            $builder->like($like[$i]['idEx'], $like[$i]['idExV']);
        }
        for ($i = 0; $i < count($group); $i++) {
            $builder->groupBy($group[$i]['id']);
        }
        for ($i = 0; $i < count($order); $i++) {
            $builder->orderBy($order[$i]['id'], $order[$i]['orderType']);
        }
        if ($typeGet === "result") {
            $query = $builder->get()->getResultArray();
        } else if ($typeGet === "row") {
            $query = $builder->get()->getRowArray();
        }else if ($typeGet === "count") {
            $query = $builder->countAll();
        }
        return $query;
    }

    public function getIdEx($idEx = '', $idExV = '', $Id = '', $l = '', $s = '')
    {
        $builder = $this->db->table($this->tableName);
        $builder->select('MAX(RIGHT(' . $idEx . ', ' . $l . ')) as max_id ');
        $builder->like($idEx, $idExV);
        $builder->orderBy($Id, 'DESC');
        $query = $builder->get()->getRowArray();
        $max_id = (int)$query['max_id'];
        $new_id_str = "";
        $new_id = $max_id + 1;
        $lengt_id = strlen((string)$new_id);
        $rolback = (int)$l - $lengt_id;
        for ($i = 0; $i < $rolback; $i++) {
            $new_id_str .= "0";
        }
        $new_id_str .= (string)$new_id;
        return $idExV.$s. $new_id_str;
    }

    public function getCostum($q = '', $p = [], $typeGet = 'result')
    {
        $builder = $this->db->query($q, $p);
        if ($typeGet === "result") {
            $query = $builder->getResultArray();
        } else if ($typeGet === "row") {
            $query = $builder->getRowArray();
        }else if ($typeGet === "count") {
            $query = $builder->countAll();
        }
        return $query;
    }
    
    public function getKode($idEx = '', $idExV = '', $Id = '', $l = '', $where = [], $like = [])
    {
        $builder = $this->db->table($this->tableName);
        $builder->select('MAX(RIGHT(' . $idEx . ', ' . $l . ')) as max_id ');
        for ($i = 0; $i < count($where); $i++) {
            $builder->where($where[$i]['idEx'], $where[$i]['idExV']);
        }
        for ($i = 0; $i < count($like); $i++) {
            $builder->like($like[$i]['idEx'], $like[$i]['idExV']);
        }
        $builder->orderBy($Id, 'DESC');
        $query = $builder->get()->getRowArray();
        $max_id = (int)$query['max_id'];
        $new_id_str = "";
        $new_id = $max_id + 1;
        $lengt_id = strlen((string)$new_id);
        $rolback = (int)$l - $lengt_id;
        for ($i = 0; $i < $rolback; $i++) {
            $new_id_str .= "0";
        }
        $new_id_str .= (string)$new_id;
        return $idExV ."-". $new_id_str;
    }

    public function insertData($data)
    {
        return $this->db->table($this->tableName)->insert($data);
    }

    public function updateData($data, $idExV = '')
    {
        return $this->db->table($this->tableName)->update($data, [$this->idEx => $idExV]);
    }

    public function deleteData($idExV = '')
    {
        return $this->db->table($this->tableName)->delete([$this->idEx => $idExV]);
    }
    public function deleteDataByV($idEx = '', $idExV = '')
    {
        return $this->db->table($this->tableName)->delete([$idEx => $idExV]);
    }
}
