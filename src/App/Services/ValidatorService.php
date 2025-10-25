<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
    RequiredRule,
    EmailRule,
    MinRule,
    InRule,
    UrlRule,
    MatchRule,
    LengthMaxRule,
    NumericRule,
    DateFormatRule
};

class ValidatorService
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->validator->add('required', new RequiredRule());
        $this->validator->add('email', new EmailRule());
        $this->validator->add('min', new MinRule());
        $this->validator->add('in', new InRule());
        $this->validator->add('url', new UrlRule());
        $this->validator->add('match', new MatchRule());
        $this->validator->add('lengthMax', new LengthMaxRule());
        $this->validator->add('numeric', new NumericRule());
        $this->validator->add('dateFormat', new DateFormatRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            'fullName' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'address' => ['required'],
            'password' => ['required'],
            'confirmPassword' => ['required', 'match:password'],
            'tos' => ['required']
        ]);
    }

    public function validateLogin(array $formData)
    {
        $this->validator->validate($formData, [

            'username' => ['required'],
            'password' => ['required']

        ]);
    }

    public function validateStudent(array $formData)
    {
        $this->validator->validate($formData, [
            'username' => ['required'],
            'password' => ['required'],
            'full_name' => ['required'],
            'student_number' => ['required'],
            'course' => ['required'],
            'year_level' => ['required']



        ]);
    }

    public function validateCourse(array $formData)
    {
        $this->validator->validate($formData, [
            'courseName' => ['required'],
            'courseCode' => ['required'],
            'courseDescription' => ['required']
        ]);
    }

    public function validateSubject(array $formData)
    {
        $this->validator->validate($formData, [
            'subject_code' => ['required'],
            'units' => ['required'],
            'subject_name' => ['required']

        ]);
    }

    public function validateEnrollment(array $formData)
    {
        $this->validator->validate($formData, [
            'student_id' => ['required', 'numeric'],
            'schedule_id' => ['required', 'numeric']
        ]);
    }
}
