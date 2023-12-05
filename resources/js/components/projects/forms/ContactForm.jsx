import React, {useState, useEffect, useContext} from 'react';
import axios from 'axios';
import { ModalContext } from '../contexts/ModalContext';
import Spinner from '../../ui/Spinner';

export default function({ contact, projectId, hideContactForm, refreshContacts }) {

    let [ loading, setLoading ] = useState( false );
    let [ contactId, setContactId ] = useState( contact.id );

    let [ name, setName ] = useState(contact.name ? contact.name : '');
    let [ address, setAddress ] = useState(contact.address ? contact.address : '');
    let [ postcode, setPostcode ] = useState(contact.postcode ? contact.postcode : '');
    let [ city, setCity ] = useState(contact.city ? contact.city : '');
    let [ email, setEmail ] = useState(contact.email ? contact.email : '');
    let [ phone, setPhone ] = useState(contact.phone ? contact.phone : '');
    let [ website, setWebsite ] = useState(contact.website ? contact.website : '');

    let [ errors, setErrors ] = useState([]);

    const { setStatusMsg, clearStatusMsg, setErrorMsg, clearErrorMsg } = useContext(ModalContext);

    const saveContact = async () => {

        let hasErrors = validateContact();

        if( hasErrors ) {
            return false;
        }

        setLoading(true);
        let url = 'contacts';
        if( contactId ) {
            url += '/' + contactId;
        }

        await axios({
            method: contactId ? 'patch' : 'post',
            url: url,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                projectId: projectId,
                name: name,
                address: address,
                postcode: postcode,
                city: city,
                email: email,
                phone: phone,
                website: website
            }
        }).then( response => {

            setLoading(false);
            hideContactForm();
            refreshContacts();

            if( response.data.status == 'success' ) {
                setStatusMsg( response.data.msg );
                clearStatusMsg();
            }

            if( response.data.status == 'error' ) {
                setErrorMsg( response.data.msg );
                clearErrorMsg();
            }

        });

    }

    const validateContact = () => {

        let fields = [
            {
                field: name,
                errorMsg: 'Name is a required field'
            },
            {
                field: address,
                errorMsg: 'Address is a required field'
            }
        ];

        let formErrors = [];
        let idx = 0;
        let max = fields.length;
        while( idx < max ) {
            if( ! fields[idx].field ) {
                formErrors.push( fields[idx].errorMsg );
            }
            if( fields[idx].policy ) {
                switch( fields[idx].policy ) {
                    case 'email':
                        let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,10})$/;
                        if( reg.test( fields[idx].field ) === false ) {
                            formErrors.push( 'E-mail is invalid' );
                        }
                    break;
                }
            }
            idx++;
        }

        setErrors( formErrors );

        return formErrors.length;

    }

    return(
        <div className="contacts-form mt-2 mb-2 text-left">
            { loading === true && 
                <Spinner />
            }
            <div className="mb-3">
                <label htmlFor="name" className="w-full block">Name*</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="Name"
                    value={ name }
                    onChange={ evt => {
                        setName( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="address" className="w-full block">Address*</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="Address"
                    value={ address }
                    onChange={ evt => {
                        setAddress( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="postcode" className="w-full block">Postcode</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="Postcode"
                    value={ postcode }
                    onChange={ evt => {
                        setPostcode( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="city" className="w-full block">City</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="City"
                    value={ city }
                    onChange={ evt => {
                        setCity( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="email" className="w-full block">E-mail</label>
                <input 
                    type="email" 
                    className="form-control w-full" 
                    placeholder="E-mail"
                    value={ email }
                    onChange={ evt => {
                        setEmail( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="phone" className="w-full block">Phone</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="Phone"
                    value={ phone }
                    onChange={ evt => {
                        setPhone( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3">
                <label htmlFor="website" className="w-full block">Website</label>
                <input 
                    type="text" 
                    className="form-control w-full" 
                    placeholder="Website"
                    value={ website }
                    onChange={ evt => {
                        setWebsite( evt.target.value );
                    } }
                />
            </div>
            <div className="mb-3 text-center">
                { loading === true && 
                    <Spinner />
                }
                { errors.length > 0 &&
                <div className="mb-3 text-center">
                    { errors.map( ( error, idx ) => <p key={ idx } className="text-red-700">{ error }</p> ) }
                </div>
                }
                <button 
                    onClick={ evt => saveContact() } 
                    type="button" 
                    className="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                >
                    Save contact
                </button>
                <button 
                    onClick={ evt => hideContactForm() } 
                    type="button" 
                    className="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-red-800"
                >
                    Cancel
                </button>
            </div>
        </div>
    )

}