import React from 'react';
import ReactDOM from 'react-dom/client';
// import BexioContacts from './components/BexioContacts';
// import BexioProjects from './components/BexioProjects';
// import BexioTimesheets from './components/BexioTimesheets';
import Projects from './components/projects/Main';

/**
 * Front end
 */

if( document.getElementById('projects-main') ) {

    const container = document.getElementById('projects-main');
    const root = ReactDOM.createRoot( container );

    root.render(
        <Projects />
    );

}