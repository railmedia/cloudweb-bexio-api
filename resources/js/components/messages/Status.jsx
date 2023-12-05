export default function ({ message, type }) {
    return (
        <div className="p-5">
            <div className={ `alert alert-${ type }` }>
                <ul style={{ margin: 0 }}>
                    <li>{ message }</li>
                </ul>
            </div>
        </div>
    )
}