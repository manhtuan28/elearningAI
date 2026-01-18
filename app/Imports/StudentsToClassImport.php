<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsToClassImport implements ToModel, WithHeadingRow
{
    protected $classroomId;
    public $count = 0;
    public $failed = [];

    public function __construct($classroomId)
    {
        $this->classroomId = $classroomId;
    }

    public function model(array $row)
    {
        $email = null;
        if (isset($row['email'])) $email = $row['email'];
        elseif (isset($row['email_address'])) $email = $row['email_address'];
        elseif (isset($row['mail'])) $email = $row['mail'];

        if (!$email) {
            foreach ($row as $value) {
                if (strpos($value, '@') !== false && strpos($value, '.') !== false) {
                    $email = $value;
                    break;
                }
            }
        }

        if ($email) {
            $cleanEmail = trim($email);

            $student = User::where('email', $cleanEmail)->where('role', 'student')->first();

            if ($student) {
                if ($student->classroom_id != $this->classroomId) {
                    $student->update(['classroom_id' => $this->classroomId]);
                    $this->count++;
                }
            } else {
                $this->failed[] = $cleanEmail;
            }
        }

        return null;
    }
}
