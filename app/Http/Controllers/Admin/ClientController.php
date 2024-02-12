<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientCreateRequest;
use App\Http\Requests\Admin\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ClientDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.client.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientCreateRequest $request)
    {
        $client = new Client();
        $client->name = $request->name;
        $client->email = $request->email;
        $client->gst_no = $request->gst_no;
        $client->address = $request->address;
        $client->area = $request->area;
        $client->city = $request->city;
        $client->state = $request->state;
        $client->country = $request->country;
        $client->pincode = $request->pincode;
        $client->primary_name = $request->primary_name;
        $client->primary_mobile = $request->primary_mobile;
        $client->primary_whatsapp = $request->primary_whatsapp;
        $client->secondary_name = $request->secondary_name;
        $client->secondary_mobile = $request->secondary_mobile;
        $client->secondary_whatsapp = $request->secondary_whatsapp;
        $client->description = $request->description;
        $client->status = $request->status;
        $client->save();
        toastr()->success('Client Created Successfully');
        return to_route('client.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, string $id)
    {
        $client = Client::findOrFail($id);
        $client->name = $request->name;
        $client->email = $request->email;
        $client->gst_no = $request->gst_no;
        $client->address = $request->address;
        $client->area = $request->area;
        $client->city = $request->city;
        $client->state = $request->state;
        $client->country = $request->country;
        $client->pincode = $request->pincode;
        $client->primary_name = $request->primary_name;
        $client->primary_mobile = $request->primary_mobile;
        $client->primary_whatsapp = $request->primary_whatsapp;
        $client->secondary_name = $request->secondary_name;
        $client->secondary_mobile = $request->secondary_mobile;
        $client->secondary_whatsapp = $request->secondary_whatsapp;
        $client->description = $request->description;
        $client->status = $request->status;
        $client->save();
        toastr()->success('Client Updated Successfully');
        return to_route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response(['status' => 'success', 'message' => 'Client Deleted Successfully!']);
        } catch (\Exception $e) {
            return response(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
