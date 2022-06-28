<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SimpleTest extends TestCase
{

    // Person list retrieval, expect 200 & appropriate json-structure
    public function test_the_application_with_list_retrieval()
    {
        $this->json('get', '/person')
          ->seeStatusCode(200)
          ->seeJsonStructure([
            'data' => [
              '*' => [
                // 'id',
                'name',
                'birthdate',
                'timezone',
                'isBirthday',
                'interval' => [
                  'y',
                  'm',
                  'd',
                  'h',
                  'i',
                  's',
                  '_comment',
                ],
                'message',
              ]
            ]
          ]);
    }

    // create Person, expect 422 & error-message
    public function test_the_application_with_invalid_input_on_create()
    {
        // missing all required field
        $this->json('post', '/person', [ 'name' => '', 'birthdate' => '', 'timezone' => '' ])
          ->seeStatusCode(422)
          ->seeJsonStructure([
            'errors' => [
              'name' => [ ],
              'birthdate' => [ ],
              'timezone' => [ ],
            ]
          ]);

        // missing some required field
        $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '', 'timezone' => '' ])
          ->seeStatusCode(422)
          ->seeJsonStructure([
            'errors' => [
              'birthdate' => [ ],
              'timezone' => [ ],
            ]
          ])
          ->seeJsonDoesntContains([
            'name'
          ]);

        $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '', 'timezone' => 'America/Los_Angeles' ])
          ->seeStatusCode(422)
          ->seeJsonStructure([
            'errors' => [
              'birthdate' => [ ],
            ]
          ])
          ->seeJsonDoesntContains([
            'name',
            'timezone',
          ]);

        // input-format validation
        $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '2-5', 'timezone' => 'abc' ])
          ->seeStatusCode(422)
          ->seeJsonStructure([
            'errors' => [
              'birthdate' => [ ],
              'timezone' => [ ],
            ]
          ])
          ->seeJsonDoesntContains([
            'name'
          ]);

        // successful creation
        $response = $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '2008-02-05', 'timezone' => 'America/Los_Angeles' ])
          ->seeStatusCode(200)
          ->seeJsonStructure([
            'data' => [
              // 'id',
              'name',
              'birthdate',
              'timezone',
              'isBirthday',
              'interval' => [
                'y',
                'm',
                'd',
                'h',
                'i',
                's',
                '_comment',
              ],
              'message',
            ]
          ])->response;

        // duplicate creation
        $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '2008-02-05', 'timezone' => 'America/Los_Angeles' ])
          ->seeStatusCode(422)
          ->seeJsonStructure([
            'errors' => [
              'name' => [ ],
              'birthdate' => [ ],
              'timezone' => [ ],
            ]
          ]);

        // delete created entry
        $this->json('delete', '/person/' . json_decode($response->getContent())->data->id)
          ->seeStatusCode(200);
    }

    // verify content correctness
    public function test_the_application_with_correct_content()
    {
        // create test data
        $response = $this->json('post', '/person', [ 'name' => 'Jack Sparrow', 'birthdate' => '2008-02-05', 'timezone' => 'America/Los_Angeles' ])
          ->seeStatusCode(200)
          ->seeJsonStructure([
            'data' => [
              // 'id',
              'name',
              'birthdate',
              'timezone',
              'isBirthday',
              'interval' => [
                'y',
                'm',
                'd',
                'h',
                'i',
                's',
                '_comment',
              ],
              'message',
            ]
          ])->response;

        $this->json('get', '/person', [ 'timestamp' => strtotime("2013-03-25") ])
          ->seeStatusCode(200)
          ->seeJsonContains([
                // 'id',
                'name' => 'Jack Sparrow',
                'birthdate' => '2008-02-05',
                'timezone' => 'America/Los_Angeles',
                'isBirthday' => false,
                'interval' => [
                  'y' => 0,
                  'm' => 10,
                  'd' => 11,
                  'h' => 0,
                  'i' => 0,
                  's' => 0,
                  '_comment' => 'possibly other fields...',
                ],
                'message' => 'Jack Sparrow is 6 years old in 10 months, 11 days in America/Los_Angeles',
          ]);

        $this->json('get', '/person', [ 'timestamp' => strtotime("2013-02-05 19:53:00") ])
          ->seeStatusCode(200)
          ->seeJsonContains([
                // 'id',
                'name' => 'Jack Sparrow',
                'birthdate' => '2008-02-05',
                'timezone' => 'America/Los_Angeles',
                'isBirthday' => true,
                'interval' => [
                  'y' => 0,
                  'm' => 0,
                  'd' => 0,
                  'h' => 4,
                  'i' => 6,
                  's' => 59,
                  '_comment' => 'possibly other fields...',
                ],
                'message' => 'Jack Sparrow is 5 years old today (4 hours remaining in America/Los_Angeles)',
          ]);

        // delete created entry
        $this->json('delete', '/person/' . json_decode($response->getContent())->data->id)
          ->seeStatusCode(200);
    }


}
