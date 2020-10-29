<?php


namespace App\Controllers;


use App\Models\SubscriberField;
use Core\BaseController;
use Symfony\Component\HttpFoundation\Request;

class SubscribersFieldsController extends BaseController
{
    /**
     * $subscriber is a $key => $value array representing the subscriber's fields to fetch
     * @param Request $queryString
     * @param array $subscriber
     * @return array
     */
    protected function get(Request $queryString, $subscriber = [])
    {
        if ($subscriber) {
            $field = SubscriberField::find([
                'subscriber_id' => $subscriber['subscriber']
            ]);

            $this->response = [
                'code' => 404,
                'message' => 'No fields were found for the given resource'
            ];

            if ($field) {
                $this->response = [
                    'code' => 200,
                    'subscriber' => $field->getAttributes()
                ];
            }

            return $this->response;
        }

        $fields = SubscriberField::findAll($queryString->query->all());

        if ($fields) {
            $this->response = [
                'code' => 200,
                'subscribers' => $fields
            ];
        }

        return $this->response;

    }

    /**
     * $subscriber is a $key => $value array representing the subscriber to post a field to
     * @param Request $request
     * @param null $subscriber
     * @return array
     */
    protected function post(Request $request, $subscriber = null)
    {
        //If there's no ID being passed, check if the subscriber is inside the request
        if (!$request->request->get('subscriber_id')) {
            if (!$subscriber) {
                $this->response = [
                    'code' => 400,
                    'message' => 'No valid subscriber was being passed'
                ];
                return $this->response;
            }
            $request->request->set('subscriber_id', $subscriber['subscriber']);
        }
        $field = new SubscriberField($request->request->all());

        $field->create();

        if ($field->id) {

            $this->response = [
                'code' => 202,
                'subscriber' => $field
            ];
        }

        return $this->response;

    }

    /**
     * $resources is a key => value array representing the subscriber and the field to update
     * @param Request $request
     * @param $resources
     * @return array
     */
    protected function put(Request $request, $resources)
    {
        if (!$resources) {
            $this->response = [
                'code' => 400,
                'message' => 'No valid subscriber or field was being passed'
            ];

            return $this->response;
        }

        $field = SubscriberField::find(
            [
                'id' => $resources['id'],
            ]
        );

        $field->fill($request->request->all());

        if ($field->update()) {
            $this->response = [
                'code' => 200,
                'message' => 'Subscriber field updated successfully',
                '$field' => $field->getAttributes()
            ];
        }

        return $this->response;
    }

    /**
     * $resources is a key => value array representing the subscriber and the field to delete
     * @param Request $request
     * @param $resources
     * @return array
     */
    protected function delete(Request $request, $resources)
    {
        if (!$resources) {
            $this->response = [
                'code' => 400,
                'message' => 'No valid subscriber or field was being passed'
            ];

            return $this->response;
        }

        $field = SubscriberField::find(
            [
                'id' => $resources['id'],
            ]
        );

        if ($field->delete()) {
            $this->response = [
                'code' => 200,
                'message' => "Subscriber's field deleted"
            ];
        }

        return $this->response;

    }
}