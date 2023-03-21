
import React from 'react';
import { createRoot } from 'react-dom/client'
import  Game from './test'
import  './style.css'

export default function LiveEditApp(){
    return(
        <Game />
    );
}

if(document.getElementById('live-edit-root')){
    createRoot(document.getElementById('live-edit-root')).render(<LiveEditApp />)
}
