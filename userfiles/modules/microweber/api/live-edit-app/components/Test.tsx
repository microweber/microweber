import React, { useEffect, useRef,useState,createContext,useContext} from 'react';

const LiveEditContext = createContext();

function Test() {
    const frameRef = useRef(null);
    const frameUrl = useRef(null);
    const frameHolderRef = useRef(null);
    const [frameSrc, setFrameSrc] = useState(null);
    const theme = useContext(LiveEditContext);
    useEffect(() => {

    },[]);

    return (
        <div>
          aaaa
        </div>
    );
}

export default Test;
