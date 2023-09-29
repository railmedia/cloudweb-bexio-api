import React, {useState, useEffect} from 'react';
import axios from 'axios';

export default function( props ) {

    let [ loading, setLoading ] = useState(true);
    let [ requiresBexioAuth, setRequiresBexioAuth ] = useState(false);
    let [ unauthorizedResource, setUnauthorizedResource ] = useState(false);
    let [ projects, setProjects ] = useState([]);

    useEffect( () => {
        if( ! projects.length ) {
            refreshProjects();
        }
    }, [projects]);

    const refreshProjects = async () => {

        await axios.get('/dashboard/bexio-projects-fetch', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            setLoading(false);

            if( response.data.message && response.data.message == 'Unauthorized' ) {
                setRequiresBexioAuth( true );
                return false;
            }

            if( response.data.error_code && response.data.error_code == 403 ) {
                setUnauthorizedResource( true );
                return false;
            }

            setProjects( response.data );

        });

        // await axios.get('/dashboard/bexio-contacts-relations-fetch', {
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     }
        // }).then( response => {

        //     console.log(response.data);

        //     // setLoading(false);

        //     // if( response.data.message && response.data.message == 'Unauthorized' ) {
        //     //     setRequiresBexioAuth( true );
        //     //     return false;
        //     // }

        //     // setContacts( response.data );

        // });

    }

    return (
        <>
        { loading == true &&
            <p>Loading projects. Please wait...</p>
        }
        { requiresBexioAuth == true &&
            <>
                <p>The operation requires Bexio authentication</p>
                <p className="mt-2 mb-2">
                    <a href="/dashboard/bexio-auth" className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block text-center">
                        Authenticate
                    </a>
                </p>
            </>
        }
        { unauthorizedResource == true && 
            <>
                <p>Your username is not allowed to access the projects. Please contact a system administrator to elevate your user or, if you are a system administrator, elevate the user by assigning the proper scopes in the users configuration panel.</p>
                <p className="mt-2 mb-2">
                    <a href="/dashboard/bexio-main" className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block text-center">
                        Go to main page
                    </a>
                </p>
            </>
        }
        { projects.length > 0 && 
            <>
            <table className="table w-full">
                <thead>
                <tr>
                    <th className="p-2" scope="col">ID</th>
                    <th className="p-2" scope="col">Name</th>
                    <th className="p-2" scope="col">Contact</th>
                    <th className="p-2" scope="col">Timesheets</th>
                    <th className="p-2" scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                { projects.map(( project, idx ) => {
                    return (
                    <tr key={idx} style={{ borderBottom: '1px solid #d9d9d9' }}>
                        <td className="p-2">{ project.id }</td>
                        <td className="p-2 w-2/5">{ project.name }</td>
                        <td className="p-2 w-2/5">Fetch { project.contact_id }</td>
                        <td className="p-2 w-2/5">Fetch</td>
                        <td className="p-2">Actions</td>
                    </tr>
                    ) }
                ) }
                </tbody>
            </table>
            </>
        }
        </>
    )
}