<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $contact = new Contact;

        $contact->project_id = $request->projectId;
        $contact->name       = $request->name;
        $contact->address    = $request->address;
        $contact->postcode   = $request->postcode;
        $contact->city       = $request->city;
        $contact->email      = $request->email;
        $contact->phone      = $request->phone;
        $contact->website    = $request->website;

        if( ! $contact->save() ) {
            return [
                'status' => 'error',
                'msg'    => 'There was an error adding the contact. Please try again.'
            ];
        }
        
        return [
            'status' => 'success',
            'msg'    => 'Contact added'
        ];

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact = Contact::where('id', $id)->first();

        $contact->project_id = $request->projectId;
        $contact->name       = $request->name;
        $contact->address    = $request->address;
        $contact->postcode   = $request->postcode;
        $contact->city       = $request->city;
        $contact->email      = $request->email;
        $contact->phone      = $request->phone;
        $contact->website    = $request->website;

        if( ! $contact->save() ) {
            return [
                'status' => 'error',
                'msg'    => 'There was an error adding the contact. Please try again.'
            ];
        }
        
        return [
            'status' => 'success',
            'msg'    => 'Contact added'
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if( ! Contact::destroy( $id ) ) {
            return [
                'status' => 'error',
                'msg'    => 'There was an error deleting the contact. Please try again later.'
            ];
        }

        return [
            'status' => 'success',
            'msg'    => 'Contact deleted'
        ];
        
    }

    public function getSingleContact( Request $request ) {

        return Contact::where('id', $request->contact_id)->first();

    }

}
