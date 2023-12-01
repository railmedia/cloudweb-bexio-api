import React from 'react';
import ReactDOM from 'react-dom/client';
import BexioContacts from './components/BexioContacts';
import BexioProjects from './components/BexioProjects';
import BexioTimesheets from './components/BexioTimesheets';

/**
 * Front end
 */

if( document.getElementById('bexio-contacts') ) {

    const container = document.getElementById('bexio-contacts');
    const root = ReactDOM.createRoot( container );

    root.render(
        <BexioContacts />
    );

}

if( document.getElementById('bexio-projects') ) {

    const container = document.getElementById('bexio-projects');
    const root = ReactDOM.createRoot( container );

    root.render(
        <BexioProjects />
    );

}

if( document.getElementById('bexio-timesheets') ) {

    const container = document.getElementById('bexio-timesheets');
    const root = ReactDOM.createRoot( container );

    root.render(
        <BexioTimesheets />
    );

}