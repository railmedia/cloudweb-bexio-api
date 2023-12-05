import React, {useState, useEffect, useContext} from 'react';
import axios from 'axios';
import Flatpickr from "react-flatpickr";
import { ModalContext } from '../contexts/ModalContext';
import Spinner from '../../ui/Spinner';

export default function({ task, projectId, hideTaskForm, refreshTasks }) {

    let [ loading, setLoading ] = useState( false );
    let [ taskId, setTaskId ] = useState( task.id );
    let [ description, setDescription ] = useState(task.description ? task.description : '');
    let [ timeSpentHours, setTimeSpentHours ] = useState(0);
    let [ timeSpentMinutes, setTimeSpentMinutes ] = useState(0);
    let [ date, setDate ] = useState(task.date ? task.date : '');
    let [ errors, setErrors ] = useState([]);

    const { setStatusMsg, clearStatusMsg, setErrorMsg, clearErrorMsg } = useContext(ModalContext);

    useEffect(() => {
        
        if( Object.keys(task).length > 0 ) {
            let time = task.time_spent.split(':');
            setTimeSpentHours( time[0] );
            setTimeSpentMinutes( time[1] );
        }
        
    }, [task]);

    const formatDate = date => {

        let year  = date.getFullYear();
        let month = date.getMonth() + 1; // Months start at 0!
        let day   = date.getDate();

        if (day < 10) day = '0' + day;
        if (month < 10) month = '0' + month;

        return year + '-' + month + '-' + day;
    }

    const saveTask = async () => {

        let hasErrors = validateTask();

        if( hasErrors ) {
            return false;
        }

        setLoading(true);
        let url = 'tasks';
        if( taskId ) {
            url += '/' + taskId;
        }

        await axios({
            method: taskId ? 'patch' : 'post',
            url: url,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            data: {
                projectId: projectId,
                description: description,
                hours: timeSpentHours,
                minutes: timeSpentMinutes,
                date: date
            }
        }).then( response => {

            setLoading(false);
            hideTaskForm();
            refreshTasks();

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

    const validateTask = () => {

        let fields = [
            {
                field: description,
                errorMsg: 'Description is a required field'
            },
            {
                field: date,
                errorMsg: 'Date is a required field'
            },
        ];

        let formErrors = [];
        let idx = 0;
        let max = fields.length;
        while( idx < max ) {
            if( ! fields[idx].field ) {
                formErrors.push( fields[idx].errorMsg );
            }
            idx++;
        }

        setErrors( formErrors );

        return formErrors.length;

    }

    return(
        <div className="tasks-form mt-2 mb-2 text-left">
            { loading === true && 
                <Spinner />
            }
            <div className="mb-3">
                <label htmlFor="description" className="w-full">Description*</label>
                <textarea 
                    id="description" 
                    className="form-control w-full" 
                    value={ description }
                    onChange={evt => setDescription( evt.target.value )}
                >
                </textarea>
            </div>
            <div className="mb-3 flex flex-wrap justify-between">
                <div style={{ borderBottom: '1px solid gray', margin: '10px 0' }} className="w-full">Time spent</div>
                <div className="w-[49%]">
                    <label htmlFor="time-spent-hours" className="w-full block">Hours</label>
                    <input 
                        type="number" 
                        id="time-spent-hours" 
                        className="form-control w-full" 
                        placeholder="Hours"
                        value={ timeSpentHours }
                        onChange={ evt => {
                            if( evt.target.value.match(/^[0-9]+$/) ) {
                                setTimeSpentHours( evt.target.value );
                            }
                        } }
                    />
                </div>
                <div className="w-[49%]">
                    <label htmlFor="time-spent-minutes" className="w-full block">Minutes</label>
                    <input 
                        type="number" 
                        id="time-spent-minutes" 
                        className="form-control w-full" 
                        placeholder="Minutes" 
                        value={ timeSpentMinutes }
                        onChange={ evt => {
                            if( evt.target.value.match(/^[0-9]+$/) ) {
                                setTimeSpentMinutes( evt.target.value ) 
                            } 
                        } }
                    />
                </div>
            </div>
            <div className="mb-3">
                <label htmlFor="date" className="w-full block">Date*</label>
                <Flatpickr
                    value={date}
                    className="w-full"
                    onChange={([date]) => {
                        setDate( formatDate( date ) );
                    }}
                    options={{
                        dateFormat: "Y-m-d"
                    }}
                />
            </div>
            { errors.length > 0 &&
            <div className="mb-3 text-center">
                { errors.map( ( error, idx ) => <p key={ idx } className="text-red-700">{ error }</p> ) }
            </div>
            }
            <div className="mb-3 text-center">
                { loading === true && 
                    <Spinner />
                }
                <div id="tasks-errors"></div>
                <button onClick={ evt => saveTask() } type="button" className="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save task</button>
                <button onClick={ evt => hideTaskForm() } type="button" className="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-red-800">Cancel</button>
            </div>
        </div>
    )

}