
import React, { useEffect, useRef , useContext} from 'react';
import { createRoot } from 'react-dom/client'
import  Game from './test'
import  ServiceContainer from './components/ServiceContainer'
import  LiveEditorFrame from './components/LiveEditorFrame'
import  Test from './components/Test'

import  './style.css'
import LiveEditor from "./components/LiveEditorFrame";

export default function LiveEditApp(){

    return(
        <>
        <LiveEditor />

        <Test />
        </>
    );
}

if(document.getElementById('live-edit-root')){
    createRoot(document.getElementById('live-edit-root')).render(<LiveEditApp />)
}
