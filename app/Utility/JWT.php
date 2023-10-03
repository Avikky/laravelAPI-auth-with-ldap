<?php

namespace App\Utility;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class JWT {

    
    public $time;
    protected $headers;
    protected $payload;
    protected $secret;

    public function __construct() {
        $this->time = Carbon::now()->addSeconds(intval(env('JWT_LIFETIME')))->toDateTimeString();
        $this->headers = array('alg'=>'HS256','typ'=>'JWT');
        $this->payload = ['sub' => '1234567890', 'name' => 'Victor Anih', 'admin' => true, 'exp'=> $this->time]; 
        $this->secret = env('JWT_SECRETE', 'dMEAnDpORTaL');

    }


    function generate_jwt() {
        $headers_encoded = $this->base64url_encode(json_encode($this->headers));
        
        $payload_encoded = $this->base64url_encode(json_encode($this->payload));
        
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->secret, true);
        $signature_encoded = $this->base64url_encode($signature);
        
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        
        return $jwt;
    }

    function base64url_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    public static function createToken(){
        $JWTinstance = new JWT();
        return $JWTinstance->generate_jwt();
    }

    public function is_jwt_valid($jwt) {
        $secret = $this->secret;
        // split the jwt
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];
    
        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - Carbon::now()) < 0;
    
        // build a signature based on the header and payload using the secret
        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
        $base64_url_signature = $this->base64url_encode($signature);
    
        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);
        
        if ($is_token_expired || !$is_signature_valid) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function validateToken($token) {
        $JWTinstance = new JWT;
        return $JWTinstance->generate_jwt($token);
    }


    


    
}