<?php


namespace App\Controllers;

use App\Models\Field;
use Core\BaseController;
use Symfony\Component\HttpFoundation\Request;

class FieldsController extends BaseController
{

    protected function get(Request $queryString, $id = null)
    {
        if ($id) {
            $field = Field::find([
                'id' => $id
            ]);

            if ($field) {
                $this->response = [
                    'code' => 200,
                    'message' => 'Field found',
                    'field' => $field->getAttributes()
                ];
            }

            return $this->response;
        }

        $fields = Field::findAll($queryString->query->all());

        if ($fields) {
            $this->response = [
                'code' => 200,
                'message' => 'Fields found',
                'fields' => $fields
            ];
        }

        return $this->response;

    }

    protected function post(Request $request)
    {
        $field = new Field($request->request->all());

        $field->create();

        if ($field->id) {

            $this->response = [
                'code' => 202,
                'message' => 'Field created successfully',
                'field' => $field->getAttributes()
            ];
        }

        return $this->response;

    }

    protected function put(Request $request, $id)
    {

        $field = Field::find(['id' => $id]);

        $field->fill($request->request->all());

        if ($field->update()) {
            $this->response = [
                'code' => 200,
                'message' => 'Field updated successfully',
                'field' => $field->getAttributes()
            ];
        }

        return $this->response;
    }

    protected function delete(Request $request, $id)
    {
        $field = Field::find(['id' => $id]);

        if ($field->delete()) {
            $this->response = [
                'code' => 200,
                'message' => 'Field deleted'
            ];
        }

        return $this->response;

    }
}