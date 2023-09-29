import React, {useState, useEffect} from 'react';
import axios from 'axios';
import "flatpickr/dist/themes/material_green.css";
import Flatpickr from "react-flatpickr";

export default function( props ) {

    let [ loadingProjects, setLoadingProjects ] = useState(false);
    let [ requiresBexioAuth, setRequiresBexioAuth ] = useState(false);
    let [ unauthorizedResource, setUnauthorizedResource ] = useState(false);
    let [ timesheets, setTimesheets ] = useState([]);
    let [ projectsSearchTerm, setProjectsSearchTerm ] = useState('');
    let [ projects, setProjects ] = useState(0);
    let [ dateFiltersVisibility, setDateFiltersVisibility ] = useState( false );
    let [ timesheetFilterDateFrom, setTimesheetFilterDateFrom ] = useState('');
    let [ timesheetFilterDateTo, setTimesheetFilterDateTo ] = useState('');
    let [ exportButtonVisbility, setExportButtonVisbility ] = useState(false);

    useEffect( () => {
        if( ! timesheets.length ) {
            refreshTimesheets();
        }
    }, [timesheets]);

    const refreshTimesheets = async () => {

        return false;

        await axios.get('/dashboard/bexio-timesheets-fetch', {
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

            setTimesheets( response.data );

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

    const onSearchInputChange = evt => {
        setProjectsSearchTerm( evt.target.value );
    }

    const onSearchInputKeyPress = evt => {
        if( evt.key == 'Enter' ) {
            performProjectSearch();
        }
    }

    const performProjectSearch = async searchTerm => {
        // console.log( projectsSearchTerm );

        let term = searchTerm || projectsSearchTerm;

        setLoadingProjects(true);

        await axios({
            url: '/dashboard/bexio-projects-search',
            method: 'post',
            data: { searchTerm: term },
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            // console.log(response.data);

            setLoadingProjects(false);

            if( response.data.message && response.data.message == 'Unauthorized' ) {
                setRequiresBexioAuth( true );
                return false;
            }

            if( response.data.error_code && response.data.error_code == 403 ) {
                setUnauthorizedResource( true );
                return false;
            }

            setProjects( response.data );

            // setProjects( response.data );

        });

    }

    const onPopularSearch = searchTerm => {

        // let searchTerm = evt.target.getAttribute('data-searchterm');
        setProjectsSearchTerm( searchTerm );

        performProjectSearch( searchTerm );

    }

    const onDeleteProject = evt => {

        let newProjects = [...projects];
        newProjects.splice(evt.target.getAttribute('data-index'), 1);
        setProjects( newProjects );

    }

    const fetchAllProjectsTimesheets = async ( idx = 0 ) => {

        if( projects[ idx ] ) {
            // console.log('fetched ' + idx);
            await fetchProjectTimesheets( projects[ idx ].id, idx );
            let nextIdx = idx + 1;
            fetchAllProjectsTimesheets( nextIdx );
        }

        // console.log('fetched all');
    }

    const fetchProjectTimesheets = async ( projectId, index ) => {

        await axios({
            url: '/dashboard/bexio-project-fetch-timesheets',
            method: 'post',
            data: { projectId: projectId },
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            // console.log(response.data);

            // setLoadingProjects(false);

            if( response.data.message && response.data.message == 'Unauthorized' ) {
                setRequiresBexioAuth( true );
                return false;
            }

            if( response.data.error_code && response.data.error_code == 403 ) {
                setUnauthorizedResource( true );
                return false;
            }

            let timesheets = response.data;

            if( timesheetFilterDateFrom && timesheetFilterDateTo ) {

                timesheets = timesheets.filter((timesheet) => {
                    return new Date(timesheet.date).getTime() >= new Date(timesheetFilterDateFrom).getTime() && new Date(timesheet.date).getTime() <= new Date(timesheetFilterDateTo).getTime();
                });

            } else if( timesheetFilterDateFrom && ! timesheetFilterDateTo ) {

                timesheets = timesheets.filter((timesheet) => {
                    return new Date(timesheet.date).getTime() >= new Date(timesheetFilterDateFrom).getTime()
                });

            } else if ( ! timesheetFilterDateFrom && timesheetFilterDateTo ) {

                timesheets = timesheets.filter((timesheet) => {
                    return new Date(timesheet.date).getTime() <= new Date(timesheetFilterDateTo).getTime()
                });

            }

            let timesheetsDurations = timesheets.map(timesheet => {

                if( timesheet.duration ) {

                    let duration = timesheet.duration.split(':');
                    let hours = parseInt(duration[0]) * 60;
                    let minutes = parseInt(duration[1]);

                    return hours + minutes;

                }

                return 0;

            });

            let totalDurationMinutes = timesheetsDurations.reduce((accumulator, currentValue) => {
                return accumulator + currentValue
            }, 0 );
            let totalTime = '';
            if( totalDurationMinutes ) {
                let hours = Math.floor( totalDurationMinutes / 60 );  
                let minutes = totalDurationMinutes % 60;
                minutes = minutes < 10 ? minutes * 10 : minutes;
                totalTime = hours + ':' + minutes;    
            }

            let newProjects = [...projects];
            newProjects[index]['timesheets'] = timesheets;
            newProjects[index]['timesheetsDuration'] = timesheetsDurations;
            newProjects[index]['timesheetsTotalTime'] = totalTime;

            setProjects( newProjects );

        });

    }

    const clearTimesheetDateFilters = () => {
        setTimesheetFilterDateFrom('');
        setTimesheetFilterDateTo('');
    }

    const fetchProjectContacts = async ( contactId, index ) => {

        await axios({
            url: '/dashboard/bexio-project-fetch-contacts',
            method: 'post',
            data: { contactId: contactId },
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            console.log(response.data);

            // setLoadingProjects(false);

            if( response.data.message && response.data.message == 'Unauthorized' ) {
                setRequiresBexioAuth( true );
                return false;
            }

            if( response.data.error_code && response.data.error_code == 403 ) {
                setUnauthorizedResource( true );
                return false;
            }

            let newProjects = [...projects];
            newProjects[index]['contacts'] = response.data;
            
            setProjects( newProjects );

        });

    }

    const fetchAllProjectsContacts = async ( idx = 0 ) => {

        if( projects[ idx ] ) {
            // console.log('fetched ' + idx);
            await fetchProjectContacts( projects[ idx ].id, idx );
            let nextIdx = idx + 1;
            fetchAllProjectsContacts( nextIdx );
        }

        // console.log('fetched all');
    }

    const selectAllProjects = evt => {
        
        let inputs = document.querySelectorAll('input[type="checkbox"]');
        for(let i = 0; i < inputs.length; i++) {
            inputs[i].checked = evt.target.checked;   
        }

        // setExportButtonVisbility( evt.target.checked );
        checkHasSelectedProject();

    }

    const checkHasSelectedProject = () => {
        
        let inputs = document.querySelectorAll('input[type="checkbox"]');
        for(let i = 0; i < inputs.length; i++) {
            if( inputs[i].checked == true ) {
                setExportButtonVisbility(true);
                return false;
            }
        }

        setExportButtonVisbility( false );

    }

    const deselectSelectedProjects = () => {
        
        let inputs = document.querySelectorAll('input[type="checkbox"]');
        
        for(let i = 0; i < inputs.length; i++) {
            inputs[i].checked = false;
        }

    }

    const addProjectsToExportBasket = async () => {

        let inputs = document.querySelectorAll('input[type="checkbox"]');
        let add = {};
        for(let i = 0; i < inputs.length; i++) {

            if( inputs[i].checked == true ) {

                let projectId = inputs[i].getAttribute('data-projectid');
                let theProject = projects.filter(project => {
                    return project.id == projectId
                });

                if( theProject.length ) {

                    for( let j = 0; j < theProject.length; j++ ) {

                        let project = theProject[j];

                        add[ project.id ] = {
                            'id': project.id,
                            'name': project.name,
                            'timesheets': project.timesheets ? project.timesheets : [],
                            'timesheetsDuration': project.timesheetsDuration ? project.timesheetsDuration : [],
                            'timesheetsTotalTime': project.timesheetsTotalTime ? project.timesheetsTotalTime : [],
                            'contacts': project.contacts ? project.contacts : []
                        }

                    }
                    
                }
                
            }

        }
        
        if( add ) {

            await axios({
                url: '/dashboard/downloads-basket-add',
                method: 'post',
                data: { downloads: add },
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then( async response => {
    
                alert('The selected projects have been added to the basket');

                deselectSelectedProjects();
                setExportButtonVisbility( false );

                await axios({
                    url: '/dashboard/downloads-basket-detect-items-number',
                    method: 'get',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then( response => {
        
                    console.log(response.data);
        
                });
    
            });
            
        }

    }

    return (
        <>
        { loadingProjects == true &&
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
                <p>Your username is not allowed to access the timesheets. Please contact a system administrator to elevate your user or, if you are a system administrator, elevate the user by assigning the proper scopes in the users configuration panel.</p>
                <p className="mt-2 mb-2">
                    <a href="/dashboard/bexio-main" className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded block text-center">
                        Go to main page
                    </a>
                </p>
            </>
        }
        <div className="flex flex-wrap">
            <div className="w-full mb-4">
                Popular searches: <button type="button" className="bg-gray-400 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center" onClick={evt => onPopularSearch('marketing')}>marketing</button>
            </div>
            <div className="flex flex-wrap justify-center items-center mr-2">
                <input id="search-project-by-name" className="mr-2" type="text" defaultValue={ projectsSearchTerm } onChange={ onSearchInputChange } onKeyDown={ onSearchInputKeyPress } placeholder="Search project by name" />
                <button className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center" onClick={ evt => performProjectSearch() }>Search</button>
            </div>
            { projects.length > 0 &&
                <>
                <button type="button" className="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center mr-2" onClick={ evt => fetchAllProjectsContacts() }>Fetch contacts</button>
                <button type="button" className="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center mr-2" onClick={ evt => fetchAllProjectsTimesheets() }>Fetch timesheets</button>
                <button type="button" className="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center mr-2">Clear search</button>
                { exportButtonVisbility &&
                <button className="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block flex self-center mr-2" onClick={evt => addProjectsToExportBasket() }>Add to export basket</button>
                }
                </>
            }
        </div>
        <hr className="mt-4 mb-4" />
        <div className="flex flex-wrap">
            <button className="text-blue-500 mt-4 mb-4" onClick={ evt => setDateFiltersVisibility( ! dateFiltersVisibility ) }>Timesheets date filters</button>

            { dateFiltersVisibility == true && 
                <div className="flex w-full items-center">
                    <div className="mr-2">
                    <p>From</p>
                    <Flatpickr
                        value={ timesheetFilterDateFrom }
                        onChange={([date]) => {
                            setTimesheetFilterDateFrom( date );
                        }}
                    />
                    </div>
                    <div className="mr-2">
                    <p>To</p>
                    <Flatpickr
                        value={ timesheetFilterDateTo }
                        onChange={([date]) => {
                            setTimesheetFilterDateTo( date );
                        }}
                    />
                    </div>
                    { ( timesheetFilterDateFrom || timesheetFilterDateTo ) &&
                        <div>
                        <button type="button" className="text-blue-500" onClick={clearTimesheetDateFilters}>Clear date filters</button>
                        </div>
                    }
                </div>
            }
        </div>
        <hr className="mt-4 mb-4" />
        { projects.length > 0 && 
            <>
            <table className="table w-full">
                <thead>
                <tr>
                    <th scope="col">
                        <input type="checkbox" value="all" onChange={ evt => selectAllProjects( evt ) } />
                    </th>
                    <th className="p-2" scope="col">ID</th>
                    <th className="p-2 w-1/6" scope="col">Name</th>
                    <th className="p-2 w-3/6" scope="col">Timesheets</th>
                    <th className="p-2 w-1/6" scope="col">Contacts</th>
                </tr>
                </thead>
                <tbody>
                { projects.map(( project, idx ) => {
                    return (
                    <tr key={idx} style={{ borderBottom: '1px solid #d9d9d9' }} data-index={idx}>
                        <td>
                            <input type="checkbox" value={ project.id } data-projectid={ project.id } onChange={ evt => checkHasSelectedProject()} />
                        </td>
                        <td className="p-2" valign="top">{ project.id }</td>
                        <td className="p-2" valign="top">{ project.name }</td>
                        <td className="p-2" valign="top">
                            <a href="#!" title="Fetch timesheets" onClick={ evt => fetchProjectTimesheets( project.id, idx ) }>
                                <i className="fa-solid fa-code-pull-request text-green-600"></i>
                            </a>
                            { project.timesheets && 
                            <table className="w-full">
                                <thead>
                                <tr>
                                    <th className="w-4/6" scope="col">Task</th>
                                    <th className="w-1/6" scope="col">Date</th>
                                    <th className="w-1/6" scope="col">Duration</th>
                                </tr>
                                </thead>
                                <tbody>
                                { project.timesheets.map( (timesheet, idx) => {
                                    return (
                                        <tr key={idx} style={{ borderBottom: '1px solid #d9d9d9' }}>
                                            <td className="p-1">{ timesheet.text }</td>
                                            <td className="p-1">{ timesheet.date }</td>
                                            <td className="p-1">{ timesheet.duration }</td>
                                        </tr>
                                    );
                                } ) }
                                { project.timesheetsTotalTime && 
                                    <tr>
                                        <td className="p-1"></td>
                                        <td className="p-1">Total time</td>
                                        <td className="p-1 project-duration-total" data-projectid={ project.id }>{ project.timesheetsTotalTime }</td>
                                    </tr>
                                }
                                </tbody>
                            </table>
                            }
                        </td>
                        <td className="p-2" valign="top">
                            <a href="#!" title="Fetch contacts" onClick={ evt => fetchProjectContacts( project.contact_id, idx ) }>
                                <i className="fa-solid fa-address-book text-green-600"></i>
                            </a>
                            { project.contacts && 
                                <>
                                { project.contacts.map( ( contact, idx ) => {
                                    return (
                                        <p key={idx} className="project-contact" data-projectid={ project.id }>{ contact.name_1 } { contact.name_2 } </p>
                                    );
                                } ) }
                                </>
                            }
                        </td>
                        <td className="p-2">
                            <button type="button" onClick={(evt) => onDeleteProject(evt)} data-index={idx}><i class="fa-solid fa-trash text-red-500"></i></button>
                        </td>
                    </tr>
                    ) }
                ) }
                </tbody>
            </table>
            </>
        }
        { timesheets.length > 0 && 
            <>
            <table className="table w-full">
                <thead>
                <tr>
                    <th className="p-2" scope="col">ID</th>
                    <th className="p-2" scope="col">Name</th>
                    <th className="p-2" scope="col">Contact</th>
                    <th className="p-2" scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                { timesheets.map(( project, idx ) => {
                    return (
                    <tr key={idx} style={{ borderBottom: '1px solid #d9d9d9' }}>
                        <td className="p-2">{ project.id }</td>
                        <td className="p-2 w-2/5">{ project.name }</td>
                        <td className="p-2 w-2/5">{ project.contact_id }</td>
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