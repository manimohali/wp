import { useState } from 'react';
import './Home.css'




function Home(){
    const [ search, setSearch ] = useState('');
    const [ result, setResult ] = useState('');

    const debounce = (func, delay) => {
        let timeout;
        return (...args) =>{
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), delay);
        }
    }

    function searchProduct(e){
        let search = e.target.value;
        setSearch(search);
    }

    const debouncedSearch = debounce(searchProduct, 500);



    return(
        <>
            <div className="search-div bg">
                <input type="text" placeholder="Search Product"  onChange= {debouncedSearch} />
            </div>

            <div className="result-div bg">
                <p>Result : {search}</p>
            </div>

        </>
    )
}


export default Home;