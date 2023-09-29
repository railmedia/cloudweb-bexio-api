import React, {useState, useEffect} from 'react';
import axios from 'axios';

export default function( props ) {

    let [ loading, setLoading ] = useState(true);
    let [ requiresBexioAuth, setRequiresBexioAuth ] = useState(false);
    let [ contacts, setContacts ] = useState([]);

    useEffect( () => {
        if( ! contacts.length ) {
            refreshContacts();
        }
    }, [contacts]);

    const refreshContacts = async () => {

        await axios.get('/dashboard/bexio-contacts-fetch', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            setLoading(false);

            if( response.data.message && response.data.message == 'Unauthorized' ) {
                setRequiresBexioAuth( true );
                return false;
            }

            setContacts( response.data );

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
            <p>Loading contacts. Please wait...</p>
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
        { contacts.length && 
            <>
            <table className="table w-full">
                <thead>
                <tr>
                    <th className="p-2" scope="col">ID</th>
                    <th className="p-2" scope="col">Name</th>
                    <th className="p-2" scope="col">Address</th>
                    <th className="p-2" scope="col">E-mail / Phone</th>
                    <th className="p-2" scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                { contacts.map(( contact, idx ) => {
                    return (
                    <tr key={idx} style={{ borderBottom: '1px solid #d9d9d9' }}>
                        <td className="p-2">{ contact.id }</td>
                        <td className="p-2 w-2/5">{ contact.name_1 } { contact.name_2 }</td>
                        <td className="p-2 w-2/5">{ contact.address }{ contact.postcode ? `, ${ contact.postcode }` : '' } { contact.city }</td>
                        <td className="p-2">
                            { contact.mail && contact.mail }
                            { contact.phone_fixed && ` / ${ contact.phone_fixed }` }
                            { contact.phone_mobile && ` / ${ contact.phone_mobile }` }
                        </td>
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