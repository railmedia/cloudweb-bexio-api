import React, {createContext} from 'react';

export const ModalContext = createContext({
    statusMsg: '',
    setStatusMsg: () => {}
});