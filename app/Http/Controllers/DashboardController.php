<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\View;
use Jumbojett\OpenIDConnectClient;
use App\Models\UserMeta;
// use App\Models\User;

class DashboardController extends Controller
{

    public function dashboard() {
        return view( 'dashboard' );
    }

    public function bexioAuth() {

        $user = Auth::user();

        if( $user->bexio_refresh_token ) {
            $this->bexioAuthRefreshToken();
        }

        $oidc = new OpenIDConnectClient( 'https://idp.bexio.com', env( 'BEXIO_CLIENT_ID' ), env( 'BEXIO_CLIENT_SECRET' ) );
        $oidc->setRedirectURL( route('bexio.auth') );
        $oidc->addScope( array_keys( $user->user_scopes ) );
        $auth = $oidc->authenticate();
        $response = $oidc->getTokenResponse();

        if( $response ) {

            $access_token = isset( $response->access_token ) && $response->access_token ? $response->access_token : null;
            $refresh_token = isset( $response->refresh_token ) && $response->refresh_token ? $response->refresh_token : null;

            if( $access_token && $refresh_token ) {
                $this->saveUserTokens( $user->id, $access_token, $refresh_token );
                return redirect()->route('bexio.main');
            }

        }

    }

    public function bexioAuthRefreshToken() {

        $user = Auth::user();

        if( $user->bexio_refresh_token ) {
            
            $oidc = new OpenIDConnectClient( 'https://idp.bexio.com', env( 'BEXIO_CLIENT_ID' ), env( 'BEXIO_CLIENT_SECRET' ) );
            $oidc->setRedirectURL( route('bexio.auth.refresh') );
            $oidc->addScope( array_keys( $user->user_scopes ) );
            $refresh_token = $oidc->refreshToken( $user->bexio_refresh_token );

            $access_token = isset( $refresh_token->access_token ) && $refresh_token->access_token ? $refresh_token->access_token : null;
            $refresh_token = isset( $refresh_token->refresh_token ) && $refresh_token->refresh_token ? $refresh_token->refresh_token : null;

            if( $access_token && $refresh_token ) {
                //Save access and refresh tokens to user and redirect to dashboard
                $this->saveUserTokens( $user->id, $access_token, $refresh_token );
                return redirect()->route('dashboard');

            } else {
                //Delete access and refresh tokens from user and redirect them to the auth route
                $delete_access_token = UserMeta::where( ['user_id' => $user->id, 'meta_key' => 'bexio_access_token'] )->first();
                $delete_access_token->delete();

                $delete_refresh_token = UserMeta::where( ['user_id' => $user->id, 'meta_key' => 'bexio_refresh_token'] )->first();
                $delete_refresh_token->delete();

                return redirect()->route('bexio.auth');

            }

        } else {
            //Redirect to main auth
            return redirect()->route('bexio.auth');
        }

    }

    public function saveUserTokens( $user_id, $access_token, $refresh_token ) {

        $user_access_token_meta = UserMeta::where(['user_id' => $user_id, 'meta_key' => 'bexio_access_token'])->first();
        if( $user_access_token_meta ) {
            $user_access_token_meta->meta_value = $access_token;
            $user_access_token_meta->save();
        } else {
            $user_access_token_meta = new UserMeta;
            $user_access_token_meta->user_id = $user_id;
            $user_access_token_meta->meta_key = 'bexio_access_token';
            $user_access_token_meta->meta_value = $access_token;
            $user_access_token_meta->save();
        }

        $user_refresh_token_meta = UserMeta::where(['user_id' => $user_id, 'meta_key' => 'bexio_refresh_token'])->first();
        if( $user_refresh_token_meta ) {
            $user_refresh_token_meta->meta_value = $refresh_token;
            $user_refresh_token_meta->save();
        } else {
            $user_access_token_meta = new UserMeta;
            $user_access_token_meta->user_id = $user_id;
            $user_access_token_meta->meta_key = 'bexio_refresh_token';
            $user_access_token_meta->meta_value = $refresh_token;
            $user_access_token_meta->save();
        }

    }

    public function bexioMain() {

        return view( 'bexio.main' );

    }

    public function bexioContacts() {
        
        return view( 'bexio.contacts' );

    }

    public function bexioContactsFetch() {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json'
        ])->get( 'https://api.bexio.com/2.0/contact' );

        $response = $request->body();

        return $response;

    }

    public function bexioContactsRelationsFetch() {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json'
        ])->get( 'https://api.bexio.com/2.0/timesheet' );

        $response = $request->body();

        return $response;

    }

    public function bexioProjects() {
        return view( 'bexio.projects' );
    }

    public function bexioProjectsFetch() {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json',
        ])->get( 'https://api.bexio.com/2.0/pr_project?limit=10' );

        $response = $request->body();

        return $response;

    }

    public function bexioProjectsSearch( Request $request ) {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withBody(
            json_encode([
                [
                  'field' => 'name',
                  'value' => $request->searchTerm
                ]
            ])
        )->withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json',
            'Content-Type'  => 'application/json'
        ])->post('https://api.bexio.com/2.0/pr_project/search?limit=2000');


        $response = $request->body();

        return $response;

    }

    public function bexioProjectFetchTimesheets( Request $request ) {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withBody(
            json_encode([
                [
                  'field' => 'pr_project_id',
                  'value' => $request->projectId
                  //'criteria' => 'like' //Like is added by default. Check documentation for more details: https://docs.bexio.com/#section/API-basics/Search
                ]
            ])
        )->withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json',
            'Content-Type'  => 'application/json'
        ])->post('https://api.bexio.com/2.0/timesheet/search?limit=2000');


        $response = $request->body();

        return $response;

    }

    public function bexioProjectFetchContacts( Request $request ) {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withBody(
            json_encode([
                [
                  'field' => 'id',
                  'value' => $request->contactId
                ]
            ])
        )->withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json',
            'Content-Type'  => 'application/json'
        ])->post('https://api.bexio.com/2.0/contact/search?limit=2000');


        $response = $request->body();

        return $response;

    }

    public function bexioTimesheets() {
        return view( 'bexio.timesheets' );
    }

    public function bexioTimesheetsFetch() {

        $user = Auth::user();
        $msg  = ['message' => ''];

        if( ! $user->bexio_access_token ) {
            $msg['message'] = 'Unauthorized';
        }

        if( $msg['message'] ) {
            return $msg;
        }

        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->bexio_access_token,
            'Accept' => 'application/json',
        ])->get( 'https://api.bexio.com/2.0/timesheet', ['limit' => 2000] );

        //https://api.bexio.com/3.0/users/me

        $response = $request->body();

        return $response;

    }

    public function downloadsBasket() {

        $user = Auth::user();

        $basket = UserMeta::getUserMeta( $user->id, 'downloads_basket' );
        $basket = $basket ?? [];

        return view('downloads.basket')
               ->with('downloads_basket', json_decode($basket));

    }

    public function downloadsBasketAdd( Request $request ) {
        
        $user = Auth::user();

        UserMeta::updateUserMeta( $user->id, 'downloads_basket', json_encode( $request->downloads ) );

        return $request->downloads;

    }

    public function exportDownloadsBasketToCSV( Request $request ) {

        $user = Auth::user();

        $downloads = UserMeta::getUserMeta( $user->id, 'downloads_basket' );

        if( $downloads ) {

            $downloads = json_decode( $downloads );

            $fileName = 'bexio-export.csv';

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array( 'ID', 'Project', 'Client', 'Hours total' );

            $file = fopen('php://output', 'w');

            fputcsv( $file, $columns );

            foreach( $downloads as $download_id => $download ) {

                $client = '';
                if( $download->contacts ) {
                    foreach( $download->contacts as $contact ) {
                        $client .= $contact->name_1 . ' ' . $contact->name_2;
                    }
                }

                fputcsv(
                    $file, 
                    array(
                        $download_id, 
                        $download->name, 
                        $client, 
                        $download->timesheetsTotalTime ? $download->timesheetsTotalTime : ''
                    )
                );

            }

            fclose($file);

            return response()->make('', 200, $headers);

        }

    }

}
