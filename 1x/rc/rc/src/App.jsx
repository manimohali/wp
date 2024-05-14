import { useState } from 'react'
import './App.css'
import Home from './pages/Home'

import { useSelector, useDispatch } from 'react-redux';
import { incremented, decremented } from './store/counterSlice';


function App() {
  // const [count, setCount] = useState(0)

  const count = useSelector((state) => state.counter.value);
  const dispatch = useDispatch();
  

  return (
    <>
      <div className="card">
        <button aria-label="Increment value" onClick={() => dispatch(incremented())}>
          Increment
        </button>
        <span>{count}</span>
        <button aria-label="Decrement value" onClick={() => dispatch(decremented())}>
          Decrement
        </button>
      </div>

      {/* <div className="canvas bg">
            <h4>Our Solutions</h4>
            <div className="card">
                  <div className="img-div">
                      <img src="https://localhost.com/wp-content/uploads/2024/02/MEDICAL-LABEL_compressed-250x171.webp" />
                  </div>
            </div>
            <Home />
      </div> */}

     
    </>
  )
}

export default App
