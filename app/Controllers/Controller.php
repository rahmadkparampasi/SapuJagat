<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UmMdl;

class Controller extends BaseController
{
    use ResponseTrait;
    protected $MdlU;

    public function __construct()
    {
        $this->MdlU = new UmMdl('table1', 'id_table1');
    }

    public function index()
    {
        $data = $this->setDB();
        dd($data)
    }

    public function getAll()
    {
        return $this->setDB('getAll');
    }

    public function insertData()
    {

        $id_table1 = 1;
        $param1 = $this->request->getPost('param1');

        $data = [
            'id_table1' => $id_table1,
            'param1' => $param1,
        ];

        $insertData = $this->MdlU->insertData($data);
        if ($insertData) {
            $data = ['status' => 200, 'response' => 'success', 'message' => 'Successfully Save Data!'];
        } else {
            $data = ['status' => 500, 'response' => 'error', 'message' => 'Failed to Save Data!'];
        }

        return $this->respond($data, $data['status']);
    }


    public function updateData($id_table1 = '')
    {
        $param1 = $this->request->getPost('param1');

        $data = [
            'param1' => $param1,
        ];
        $updateData = $this->MdlU->updateData($data, $id_table1);
        if ($updateData) {
            $data = ['status' => 200, 'response' => 'success', 'message' => 'Successfully Changed Data !'];
        } else {
            $data = ['status' => 500, 'response' => 'error', 'message' => 'Failed to Change Data!'];
        }
        return $this->respond($data, $data['status']);
    }
    
    public function deleteData($id_table1 = '')
    {
        if ($id_table1 === null || $id_table1 == '') {
            $data = ['status' => 404, 'response' => 'error', 'message' => 'No ID'];
        } else {
            $deleteData = $this->MdlU->deleteData($id_table1);
            if ($deleteData) {
                $data = ['status' => 200, 'response' => 'success', 'message' => 'Successfully Delete Data!'];
            } else {
                $data = ['status' => 500, 'response' => 'error', 'message' => 'Failed to Delete Data!'];
            }
        }
        return $this->respond($data, $data['status']);
    }

    public function setDB($request = 'getAll', $data = false)
    {
        // $data can be array, string, integer and more
        $id_table1 = 'id_table1';
        $typeGet = 'result'; //result, row, count

        $fillUpdate = 'id_table1, param1';

        if ($request == 'getAll') {
            return $this->MdlU->getAll(
                $typeGet,
                // select *
                '*',
                //where
                [
                    0 => [
                        'idEx' => $id_table1,
                        'idExV' => $data[0]
                    ]
                ],
                //order by
                [
                    0 => ['id' => 'id_table1', 'orderType' => 'DESC'],
                ],
                //join
                [
                   0 => ['tableName' => 'table2', 'string' => 'table1.table1_table2 = table2.id_table2', 'type' => 'LEFT'],
                ],
                //like
                [
                   0 => ['idEx' => 'param1', 'idExV' => $data[1],
                ],
                //group
                [
                   0 => ['id' => 'param2',
                ]
            );
        } elseif ($request == 'fillUpdate') {
            $typeGet = 'row';
            return $this->MdlU->getAll(
                //type result / row
                $typeGet,
                // select *
                $fillUpdate,
                //where
                [
                    0 => [
                        'idEx' => $id_table1,
                        'idExV' => $data
                    ]
                ],
                //order by
                [
                    0 => ['id' => 'id_table1', 'orderType' => 'ASC'],
                ],
                //join
                []
            );
        }
    }
}
