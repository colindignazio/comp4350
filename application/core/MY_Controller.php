<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    private static $HTTP_STATUS_CODES = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        500 => 'Internal Server Error',
        501 => 'Not Implemented'
    ];

    private $response = [];

    public function __construct() {
        parent::__construct();
    }

    // Function: requireParams
    // Description: Ensures the required POST parameters are given and valid. Ensures that optional parameters
    //  are valid. Sends a 400 response and returns FALSE if any checks fail. If FALSE is returned the caller
    //  should not make a call to sendResponse, as this function already handles that
    // Returns: FALSE if any parameters are not given or invalid and TRUE otherwise
    protected function requireParams($required) {
        $params = $this->getParams();

        foreach($required as $param => $type) {
            // Ensure required parameters are given
            if(endsWith($type, '*')) {
                if(isset($params[$param])) $type = rtrim($type, "*"); // Remove optional param indicator for validation
                else continue; // Continue to next iteration if optional param is not given
            }
            else if(!isset($params[$param])) {
                $this->sendResponse(400, ['details' => 'Parameter(s) missing']);
                return FALSE;
            }
            
            // Ensure parameters are valid
            $paramsValid = TRUE;
            switch($type) {
                case 'int':
                    $tmp = $params[$param];
                    if(startsWith($tmp, '-')) $tmp = substr($tmp, 1);
                    if(!ctype_digit($tmp)) $paramsValid = FALSE;
                    break;
                case 'num':
                    if(!is_numeric($params[$param])) $paramsValid = FALSE;
                    break;
                case 'str':
                    if(!is_string($params[$param])) $paramsValid = FALSE;
                    break;
                case 'arr':
                    if(!is_array($params[$param])) $paramsValid = FALSE;
                    break;
                case 'email':
                    if(!isEmail($params[$param])) $paramsValid = FALSE;
                    break;
                case 'bool':
                    if(!in_array($params[$param], ['true', 'false'])) $paramsValid = FALSE;
                    break;
                default:
                    die('Invalid parameter type specified'); // *DEV PURPOSES*
            }

            if(!$paramsValid) {
                $this->sendResponse(400, array('details' => 'Invalid parameter(s)'));
                return FALSE;
            }
        }

        return TRUE;
    }

    // Function: requestParams
    // Description: Returns all POST parameters with XSS filtering enabled. Intended to serve
    //  as a shorthand utility function
    protected function getParams() {
        return $this->input->post(NULL, TRUE); // Returns all POST params with XSS filter
    }

    // Function: sendResponse
    // Description: sends a response to the client with the status $statusCode, the corresponding
    //  msg, and the contents of $arr
    protected function sendResponse($statusCode, $arr = NULL) {
        $this->response['status'] = $statusCode;
        $this->response['msg'] = self::$HTTP_STATUS_CODES[$statusCode];

        // add $arr to the end of the response array
        is_null($arr) OR $this->response += $arr;

        // *FOR DEV PURPOSES* Statuses 400 and 500 must provide a 'details' key
        (in_array($statusCode, array(400, 500)) && !isset($this->response['details'])) AND die("No error details given");
        if($statusCode == 403 && !array_key_exists("details", $this->response)) $this->response['details'] = "You do not have access to the requested resource.";

        // Send response
        header('Access-Control-Allow-Origin:*'); // Necessary for cross-domain AJAX requests
        header('Content-Type: application/json');
        echo json_encode($this->response);
    }
} 
?>