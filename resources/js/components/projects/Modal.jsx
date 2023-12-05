import React, {useState, useEffect, createContext} from 'react';
import Tasks from './Tasks';
import Contacts from './Contacts';
import Status from '../messages/Status';
import { ModalContext } from './contexts/ModalContext';

export default function( { title, type, projectId, hide } ) {

    let [ loading, setLoading ] = useState(true);
    let [ close, setClose ] = useState(false);
    let [ statusMsg, setStatusMsg ] = useState('');
    let [ errorMsg, setErrorMsg ] = useState('');

    const clearStatusMsg = () => {
        setTimeout(() => {
            setStatusMsg('');
        }, 5000)
    }

    const clearErrorMsg = () => {
        setTimeout(() => {
            setErrorMsg('');
        }, 5000)
    }

    const value = { statusMsg, setStatusMsg, clearStatusMsg, errorMsg, setErrorMsg, clearErrorMsg };

    return (
        <ModalContext.Provider value={value}>
        { close === false ? (
        <div id="static-modal-1" data-modal-backdrop="static" tabIndex="-1" aria-hidden="true" className="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%)] max-h-full flex bg-mid-black">
            <div className="relative p-4 w-full max-w-4xl max-h-full">
                <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div className="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 className="text-xl font-semibold text-gray-900 dark:text-white modal-title">
                            { title }
                        </h3>
                        { statusMsg !== '' && 
                            <Status message={ statusMsg } type="success" />
                        }
                        { errorMsg !== '' && 
                            <Status message={ errorMsg } type="danger" />
                        }
                        <button type="button" className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onClick={evt => hide()} data-modal-hide="static-modal">
                            <svg className="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span className="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div className="p-4 md:p-5 space-y-4 modal-body">
                        { type == 'tasks' && 
                            <Tasks projectId={ projectId } />
                        }
                        { type == 'contacts' && 
                            <Contacts projectId={ projectId } />
                        }
                    </div>
                    <div className="flex items-center justify-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button data-modal-hide="static-modal" onClick={evt => hide()} type="button" className="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Close</button>
                    </div>
                </div>
            </div>
        </div>
        ) : (<></>) }
        </ModalContext.Provider>
    )

}