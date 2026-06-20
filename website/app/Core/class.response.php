<?php
class Response {
    // public function __construct() {
    // }

    public function validate(array $rules,) {
        foreach ($rules as $field => $constraints) {
            $value = $_POST[$field] ?? null;

            foreach ($constraints as $constraint => $message) {
                $parts = explode(':', $constraint);
                $ruleName = $parts[0];
                $ruleValue = $parts[1] ?? null;

                // print($ruleName);
                // print("-" . $ruleValue . "\n");

                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) $this->fail($message);
                        break;
                    case 'max':
                        if (!empty($value) && strlen($value) > (int)$ruleValue) $this->fail($message);
                        break;
                    case 'min':
                        if (!empty($value) && strlen($value) < (int)$ruleValue) $this->fail($message);
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $this->fail($message);
                        break;
                }
            }
        }
    }

    private function fail(string $response, string $test = "danger") {
        if($response) {
            respond($response, $test);
        }
        reload();
        exit;
    }
}
