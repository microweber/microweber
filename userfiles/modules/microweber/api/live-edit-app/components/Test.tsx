import React, { useEffect, useRef,useState,createContext,useContext} from 'react';
import {LiveEditContext} from "./contexts/live-edit-context";

function Test() {
    const frameRef = useRef(null);
    const frameUrl = useRef(null);
    const frameHolderRef = useRef(null);
    const [frameSrc, setFrameSrc] = useState(null);
    const ebasi = LiveEditContext;

    useEffect(() => {

        mw.log(ebasi);

    },[]);

    return (
        <div>
          wow!
        </div>
    );
}

export default Test;
