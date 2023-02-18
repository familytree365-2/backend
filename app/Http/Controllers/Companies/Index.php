<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\TenantConnectionResolver;
use Illuminate\Http\Request;
use DB;

class Index extends Controller
{
//    use TenantConnectionResolver;
    public function getCompany()
    {
        $user = auth()->user();
        $company = $user->company();
//        $companies[] = $company;

        $my_companies = \LaravelEnso\Companies\Models\Company::where('created_by', $user->id)->orWhere('id', '=', $company->id)->get();
        $invited_companies = DB::table('company_person')
        ->orWhere('company_id', '!=', $company->id)
        ->where('person_id', $user->id)
        ->join('companies', 'companies.id', 'company_person.company_id')
        ->select('companies.*')->get();

//        $new_collection = $companies->merge($companies);
//        \Log::debug(array_push($companies,$companies));
        return [
            'my_comps' => $my_companies,
            'invited_comps' => $invited_companies,
        ];
    }
}
