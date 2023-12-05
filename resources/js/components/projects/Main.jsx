import React, {useState, useEffect} from 'react';
import axios from 'axios';
import Modal from './Modal';

export default function( props ) {

    let [ loading, setLoading ] = useState(true);
    let [ projects, setProjects ] = useState([]);
    let [ modal, setModal ] = useState({});

    useEffect( () => {
        if( ! projects.length ) {
            fetchProjects();
        }
    }, [projects]);

    const fetchProjects = async () => {

        setLoading(true);

        await axios.get('/projects/all/fetch', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {
            setLoading(false);
            if(response.data) {
                setProjects( response.data );
            }
        });

    }

    const hideModal = () => {
        setModal( { ...modal, visible: false } );
    }

    return (
        <>
        { modal.title && modal.type && modal.visible == true &&
            <Modal title={ modal.title } type={ modal.type } projectId={ modal.projectId } hide={ hideModal } />
        }
        { loading === true && 
            <p>Loading projects</p>
        }
        <div className="bg-white border-transparent rounded-lg shadow-xl w-full">
            <div className="p-5">
            { projects.length > 0 ? ( 
                <table className="table w-full data">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Monthly Hours</th>
                        <th scope="col">Spent</th>
                        <th scope="col">Contacts</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        { projects.map( (item, idx) => {
                            return (
                                <tr key={ item.id }>
                                    <td>{ idx + 1 }</td>
                                    <td>{ item.title }</td>
                                    <td>{ item.total_hours ? item.total_hours : 0 }</td>
                                    <td>{ item.spent_hours ? item.spent_hours : 0 }</td>
                                    <td>
                                        { item.contacts.length > 0 && 
                                        <>
                                            { item.contacts.map( contact => <p key={contact.id}>{ contact.name }</p> ) }
                                        </>
                                        }
                                    </td>
                                    <td className="flex">
                                        <ul className="flex">
                                            <li>
                                                <a className="mr-2 text-blue-600" href={ '/projects/' + item.id + '/edit' } title="Edit">
                                                    <i className="fa-solid fa-pen"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a 
                                                    className="mr-2 cursor-pointer" 
                                                    title="Project tasks"
                                                    onClick={(evt) => setModal({ title: `Tasks - ${ item.title }`, type: 'tasks', projectId: item.id, visible: true })}
                                                >
                                                    <i className="fa-solid fa-list-check text-green-600"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a 
                                                    className="mr-2 cursor-pointer load-modal-resource" 
                                                    title="Project contacts" 
                                                    onClick={(evt) => setModal({ title: `Contacts - ${ item.title }`, type: 'contacts', projectId: item.id, visible: true })}
                                                >
                                                    <i className="fa-solid fa-address-book text-green-600"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" className="text-red-600" title="Delete">
                                                    <i className="fa-solid fa-trash"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            )
                        } ) }
                    </tbody>
                </table>
            ) : (
                <p>No projects yet. Go to Bexio menu item and fetch some projects to begin with</p>
            )}
            </div>
        </div>
        </>
    )

}