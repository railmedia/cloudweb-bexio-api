import React, {useState, useEffect, useContext} from 'react';
import axios from 'axios';
import TaskForm from './forms/TaskForm';
import { ModalContext } from './contexts/ModalContext';
import Status from '../messages/Status';
import Spinner from '../ui/Spinner';

export default function({ projectId }) {

    let [ loading, setLoading ] = useState(false);
    let [ tasks, setTasks ] = useState([]);
    let [ activeTask, setActiveTask ] = useState({});
    let [ totalTime, setTotalTime ] = useState(0);
    let [ taskFormVisible, setTaskFormVisible ] = useState(false);

    const { statusMsg, setStatusMsg, clearStatusMsg, errorMsg, setErrorMsg, clearErrorMsg } = useContext(ModalContext);
    
    useEffect( () => {
        if( ! tasks.length ) {
            fetchTasks();
        }
    }, [tasks]);

    const fetchTasks = async () => {

        setLoading(true);

        await axios.get('/projects/' + projectId + '/tasks/raw', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then( response => {

            setLoading(false);

            if( response.data ) {
                let totalTime = 0;
                let idx = 0;
                let max = response.data.length;
                while(idx < max) {
                    totalTime = totalTime + response.data[idx].minutes;
                    idx++;
                }
                setTotalTime( totalTime );
                setTasks( response.data );
            }

        });

    }

    const hideTaskForm = () => {
        setTaskFormVisible(false);
    }

    const refreshTasks = () => {
        fetchTasks();
    }

    const convertMinutesToHoursAndMinutes = n => {

        let hours = (n / 60);
        let rhours = Math.floor(hours);
        let minutes = (hours - rhours) * 60;
        let rminutes = Math.round(minutes);

        return `${rhours}:${rminutes}`;

    }

    const deleteTask = async task => {
        
        if( ! confirm( 'Are you sure you wish to delete the task?' ) === true ) {
            return false;
        }

        setActiveTask( task );

        await axios({
            method: 'DELETE',
            url: '/tasks/' + task.id,
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

            fetchTasks();

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
        { tasks.length > 0 && 
        <>
        <table className="table w-full data">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Description</th>
                <th scope="col">Time Spent (hours)</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            { tasks.map( (task, idx) => {
                return (
                <tr key={ idx }>
                    <td scope="col" valign="top">{ idx + 1 }</td>
                    <td scope="col" valign="top">{ task.description }</td>
                    <td scope="col" valign="top">{ task.time_spent }</td>
                    <td scope="col" valign="top">{ task.date }</td>
                    <td scope="col" valign="top">
                        <a 
                            className="mr-2 text-blue-600 cursor-pointer" 
                            title="Edit" 
                            onClick={ evt => {
                                setActiveTask( task );
                                setTaskFormVisible(true)
                            }}
                        >
                            <i className="fa-solid fa-pen"></i>
                        </a>
                        <a 
                            className="mr-2 text-red-600 cursor-pointer" 
                            title="Delete" 
                            onClick={ evt => {
                                deleteTask( task );
                            }}
                        >
                            <i className="fa-solid fa-trash"></i>
                        </a>
                        { activeTask && activeTask.id === task.id && 
                            <Spinner />
                        }
                    </td>
                </tr>
                )
            } ) }
            <tr>
                <td scope="col"></td>
                <td scope="col" className="text-right">Total: </td>
                <td scope="col">{ convertMinutesToHoursAndMinutes( totalTime ) }</td>
                <td scope="col"></td>
                <td scope="col"></td>
            </tr>
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
                    setActiveTask({});
                    setTaskFormVisible(true);
                }}
                className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Add new task
            </button>
            { taskFormVisible === true && ! Object.keys(activeTask).length && 
                <TaskForm task={{}} projectId={projectId} hideTaskForm={hideTaskForm} refreshTasks={refreshTasks} />
            }
            { taskFormVisible === true && Object.keys(activeTask).length > 0 &&
                <TaskForm task={ activeTask } projectId={projectId} hideTaskForm={hideTaskForm} refreshTasks={refreshTasks} />
            }
        </div>
        </>
        }
        </>
    )
}