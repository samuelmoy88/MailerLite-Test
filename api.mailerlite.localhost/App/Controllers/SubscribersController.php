<?php


namespace App\Controllers;

use Core\BaseController;
use hbattat\VerifyEmail;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Subscriber;


class SubscribersController extends BaseController
{

    protected function get(Request $queryString, $id = null)
    {

        if ($id) {
            $subscriber = Subscriber::find([
                'id' => $id
            ]);

            if ($subscriber) {
                $this->response = [
                    'code' => 200,
                    'message' => 'Subscriber found',
                    'subscriber' => $subscriber->getAttributes()
                ];
            }

            return $this->response;
        }

        $subscribers = Subscriber::findAll($queryString->query->all());

        if ($subscribers) {
            $this->response = [
                'code' => 200,
                'message' => 'Subscribers found',
                'subscribers' => $subscribers
            ];
        }

        return $this->response;

    }

    protected function post(Request $request)
    {
        if (!$this->doesEmailExist($request->request->get('email'))) {
            return $this->response;
        }

        $subscriber = new Subscriber($request->request->all());

        $subscriber->create();

        if ($subscriber->id) {

            if ($request->request->get('fields')) {

                foreach ($request->request->get('fields') as $fields) {

                    $fields['subscriber_id'] = $subscriber->id;

                    $subscriber->addField($fields);
                }
            }


            $this->response = [
                'code' => 202,
                'message' => 'Subscriber created successfully',
                'subscriber' => $subscriber->getAttributes()
            ];
        }

        return $this->response;

    }

    protected function put(Request $request, $id)
    {
        //Check if the provided email actually exists and is valid
        if (!$this->doesEmailExist($request->request->get('email'))) {
            return $this->response;
        }

        $subscriber = Subscriber::find(['id' => $id]);

        $subscriber->fill($request->request->all());

        if ($subscriber->update()) {
            $this->response = [
                'code' => 200,
                'message' => 'Subscriber updated successfully',
                'subscriber' => $subscriber->getAttributes()
            ];
        }

        return $this->response;
    }

    protected function delete(Request $request, $id)
    {
        $subscriber = Subscriber::find(['id' => $id]);

        if ($subscriber->delete()) {
            $this->response = [
                'code' => 200,
                'message' => 'Subscriber deleted'
            ];
        }

        return $this->response;
    }

    /**
     * Checks if a provided email address is valid and exists.
     * If it doesn't exists it'll send a proper response back to the user
     * @param string $email
     * @return bool
     * @throws \Core\VerifyEmailException
     */
    private function doesEmailExist($email = '')
    {

        $verifier = new VerifyEmail($email, 'info@mailerlite.com');

        //Check if the email is valid (format wise)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response = [
                'code' => 412,
                'message' => 'Email address incorrectly formatted'
            ];

            return false;
        }

        if (!$verifier->verify()) {
            $this->response = [
                'code' => 412,
                'message' => 'Email address does not exist'
            ];

            return false;
        }

        return true;
    }
}