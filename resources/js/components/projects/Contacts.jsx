import React, {useState, useEffect, useContext} from 'react';
import axios from 'axios';
import ContactForm from './forms/ContactForm';
import { ModalContext } from './contexts/ModalContext';
import Status from '../messages/Status';
import Spinner from '../ui/Spinner';

export default function({ projectId }) {

    let [ loading, setLoading ] = useState(false);
    let [ contacts, setContacts ] = useState([]);
    let [ activeContact, setActiveContact ] = useState({});
    let [ contactFormVisible, setContactFormVisible ] = useState(false);

    const { statusMsg, setStatusMsg, clearStatusMsg, errorMsg, setErrorMsg, clearErrorMsg } = useContext(ModalContext);
    
    useEffect( () => {
        if( ! contacts.length ) {
            fetchContacts();
        }
    }, [contacts]);

    const fetchContacts = async () => {

        setLoading(true);

        await axios.get('/projects/' + projectId + '/contacts/raw', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            setLoading(false);

            if( response.data ) {
                setContacts( response.data );
            }

        });

    }

    const hideContactForm = () => {
        setContactFormVisible(false);
    }

    const refreshContacts = () => {
        fetchContacts();
    }

    const deleteContact = async contact => {
        
        if( ! confirm( 'Are you sure you wish to delete the contact?' ) === true ) {
            return false;
        }

        setActiveContact( contact );

        await axios({
            method: 'DELETE',
            url: '/contacts/' + contact.id,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            if( response.data.status == 'success' ) {
                setStatusMsg( response.data.msg );
                clearStatusMsg();
            }

            if( response.data.status == 'error' ) {
                setErrorMsg( response.data.msg );
                clearErrorMsg();
            }

            fetchContacts();

        });

    }

    return (
        <>
        { loading == true && 
        <>
        <div className="flex justify-center">
            <Spinner width="8" height="8" />
        </div>
        </>
        }
        { contacts.length > 0 && 
        <>
        <table className="table w-full data">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Website</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            { contacts.map( contact => {
                return (
                    <tr key={ contact.id }>
                        <td scope="col" valign="top">{ contact.id }</td>
                        <td scope="col" valign="top">{ contact.name }</td>
                        <td scope="col" valign="top">{ contact.address }</td>
                        <td scope="col" valign="top">{ contact.email }</td>
                        <td scope="col" valign="top">{ contact.phone }</td>
                        <td scope="col" valign="top">{ contact.website }</td>
                        <td scope="col" valign="top">
                            <a 
                                className="mr-2 text-blue-600 cursor-pointer" 
                                title="Edit"
                                onClick={ evt => {
                                    setActiveContact( contact );
                                    setContactFormVisible(true)
                                }}
                            >
                                <i className="fa-solid fa-pen"></i>
                            </a>
                            <a 
                                className="mr-2 text-red-600 cursor-pointer" 
                                title="Delete"
                                onClick={ evt => {
                                    deleteContact( contact );
                                }}
                            >
                                <i className="fa-solid fa-trash"></i>
                            </a>
                            { activeContact && activeContact.id === contact.id && 
                                <Spinner />
                            }
                        </td>
                    </tr>
                )
            } ) }
            </tbody>
        </table>
        <div className="text-center">
            { statusMsg !== '' && 
                <Status message={ statusMsg } type="success" />
            }
            { errorMsg !== '' && 
                <Status message={ errorMsg } type="danger" />
            }
            <button  
                type="button" 
                onClick={evt => {
                    setActiveContact({});
                    setContactFormVisible(true);
                }}
                className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Add new contact
            </button>
            { contactFormVisible === true && ! Object.keys(activeContact).length && 
                <ContactForm contact={{}} projectId={projectId} hideContactForm={hideContactForm} refreshContacts={refreshContacts} />
            }
            { contactFormVisible === true && Object.keys(activeContact).length > 0 &&
                <ContactForm contact={ activeContact } projectId={projectId} hideContactForm={hideContactForm} refreshContacts={refreshContacts} />
            }
        </div>
        </>
        }
        </>
    )
}