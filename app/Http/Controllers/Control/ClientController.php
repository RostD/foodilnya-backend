<?php

namespace App\Http\Controllers\Control;

use App\Order\Client;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    public function clients(Request $request)
    {
        if (Gate::denies('client-see'))
            abort(401);

        $filterName = $request->input('name');
        $filterLogin = $request->input('login');

        $clients = User::clients()->withTrashed();

        if ($filterName)
            $clients->where('name', 'like', '%' . $filterName . '%');

        if ($filterLogin)
            $clients->where('email', 'like', '%' . $filterLogin . '%');


        $data['clients'] = [];
        foreach ($clients->get() as $model) {
            $data['clients'][] = new Client($model);
        }


        return view('control.order.client.clients', $data);
    }

    public function formAdd()
    {
        if (Gate::denies('client-add'))
            abort(401);

        return view('control.order.client.formAdd');
    }

    public function add(Request $request)
    {
        if (Gate::denies('client-add'))
            abort(401);

        $this->validate($request, [
            'name' => 'required|max:255',
            'login' => 'required|max:255|unique:users,email',
        ]);

        $name = $request->input('name');
        $login = $request->input('login');
        $password = 'cLient6184Op';
        $address = $request->input('address');
        $phone = $request->input('phone');

        DB::beginTransaction();

        $client = Client::create($name, $login, $password);
        if ($client) {
            $client->address = $address;
            $client->phone = $phone;
            DB::commit();
        }
        DB::rollBack();

        return back();
    }

    public function formEdit($id)
    {
        if (Gate::denies('client-edit'))
            abort(401);

        $data['client'] = Client::find($id);
        return view('control.order.client.formEdit', $data);
    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('client-add'))
            abort(401);

        $client = Client::find($id);

        if ($client) {

            $validate['name'] = 'required|max:255';

            if ($client->login != $request->input('login'))
                $validate['login'] = 'required|max:255|unique:users,email';

            $this->validate($request, $validate);

            $name = $request->input('name');
            $login = $request->input('login');
            $address = $request->input('address');
            $phone = $request->input('phone');

            DB::beginTransaction();

            $client->name = $name;
            $client->login = $login;
            $client->address = $address;
            $client->phone = $phone;

            DB::commit();


            return back();
        }

        abort(404);
    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('client-delete'))
            abort(401);

        $client = Client::find($id);

        if ($client) {
            $client->destroy();
            return response('', 200);
        }
        abort(404);
    }
}
