import React, { useEffect, useRef , useContext, createContext} from 'react';
import { createRoot } from 'react-dom/client'
import  Test from './components/Test'
import './style.css'

export default function LiveEditApp(){

    return(
        <>
            <Test />
        </>

    );
}

if(document.getElementById('live-edit-root')){
    createRoot(document.getElementById('live-edit-root')).render(<LiveEditApp />)
}
