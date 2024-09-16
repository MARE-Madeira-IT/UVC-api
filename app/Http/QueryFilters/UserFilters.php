<?php

namespace App\Http\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;
use App\Traits\OrderFilter;
use App\Models\User;

class UserFilters extends QueryFilters
{
    use OrderFilter;

    public function search($string)
    {
        //converting male and female to m and f
        $genderString = (strtolower($string) == 'male' ? 'm' : (strtolower($string) == 'female' ? 'f' : 'random string'));

        $this->query->where(function ($query) use ($string, $genderString) {
            $query->where('email', 'like', '%' . $string . '%')
                ->orWhere('note', 'like', '%' . $string . '%');
        });
    }

    public function active($int)
    {
        $this->query->where('active', $int);
    }

    public function type($string)
    {
        $userTypes = User::types();
        $this->query->where('user_type', $userTypes[$string]);
    }
}
