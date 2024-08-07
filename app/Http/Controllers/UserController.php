<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserPartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();

        $userIds = $users->pluck('id');
        $defaultServiceCentres = DB::connection('second_db')->table('users_services_centres')
            ->whereIn('user_id', $userIds)
            ->where('default', 1)
            ->get()
            ->keyBy('user_id');

            $partnerIds = $defaultServiceCentres->pluck('user_partner_id')->toArray();
            $partners = DB::table('user_partners')->whereIn('id', $partnerIds)->get()->keyBy('id');
        
            foreach ($users as $user) {
                $defaultServiceCentre = $defaultServiceCentres->get($user->id);
                if ($defaultServiceCentre) {
                    $user->defaultServiceCentreName = $partners->get($defaultServiceCentre->user_partner_id)->full_name_ru ?? 'Не вказано';
                } else {
                    $user->defaultServiceCentreName = 'Не вказано';
                }
            }

        return view('guide.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $userPartners = UserPartner::whereHas('contracts', function($query) {
            $query->where('contract_type', 'Сервис');
        })->get();
        
        return view('guide.users.create', compact('roles', 'userPartners'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userPartners = UserPartner::whereHas('contracts', function($query) {
            $query->where('contract_type', 'Сервис');
        })->get();

        $serviceCentres = collect(DB::connection('second_db')->table('users_services_centres')
            ->where('user_id', $id)
            ->get());
            
        $defaultServiceCentre = $serviceCentres->where('default', 1)->first();
        
        $partnerIds = $serviceCentres->pluck('user_partner_id')->toArray();
        $partners = DB::table('user_partners')->whereIn('id', $partnerIds)->get()->keyBy('id');
        
        foreach ($serviceCentres as &$centre) {
            $centre->full_name_ru = $partners[$centre->user_partner_id]->full_name_ru ?? 'Не вказано';
        }

        return view('guide.users.edit', compact('user', 'roles', 'userPartners', 'serviceCentres', 'defaultServiceCentre'));
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name_ru' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'email' => 'required|email',
            'status' => 'required|boolean',
            'role_id' => 'required',
            'service_centres' => 'nullable|array',
            'service_centres.*' => 'nullable|integer',
            'default_service_centre' => 'nullable|integer',
        ]);

    
        $user = User::create([
            'first_name_ru' => $validatedData['first_name_ru'],
            'password' => $validatedData['password'],
            'email' => $validatedData['email'],
            'status' => $validatedData['status'],
            'role_id' => $validatedData['role_id'],
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'company_name' => '',
            'phone' => '',
            'last_login_ip' => '',
        ]);
    
        if (!empty($validatedData['service_centres'])) {
            foreach ($validatedData['service_centres'] as $partnerId) {
                DB::connection('second_db')->table('users_services_centres')->insert([
                    'user_id' => $user->id,
                    'user_partner_id' => $partnerId ?? null,
                    'default' => $partnerId == $validatedData['default_service_centre'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        return redirect()->route('users.index');
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'first_name_ru' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'email' => 'required|email',
            'status' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
            'service_centres' => 'array',
            'service_centres.*' => 'nullable',
            'default_service_centre' => 'nullable',
        ]);


        $user->update([
            'first_name_ru' => $validatedData['first_name_ru'],
            'password' => $validatedData['password'],
            'email' => $validatedData['email'],
            'status' => $validatedData['status'],
            'role_id' => $validatedData['role_id'],
        ]);

        DB::connection('second_db')->table('users_services_centres')->where('user_id', $user->id)->delete();

        if (!empty($validatedData['service_centres'])) {
            foreach ($validatedData['service_centres'] as $partnerId) {
                DB::connection('second_db')->table('users_services_centres')->insert([
                    'user_id' => $user->id,
                    'user_partner_id' => $partnerId ?? null,
                    'default' => $partnerId == $validatedData['default_service_centre'] ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('users.index');
    }


    public function getManagers()
    {
        $managers = User::where('role_id', 2)->get(['id', 'first_name_ru']);
        return response()->json($managers);
    }

    public function getUserPartners()
    {
        $userPartners = UserPartner::whereHas('contracts', function($query) {
            $query->where('contract_type', 'Сервис');
        })->get(['id', 'full_name_ru']);
    
        return response()->json($userPartners);
    }
}