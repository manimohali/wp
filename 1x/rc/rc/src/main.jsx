import React from 'react'
import ReactDOM from 'react-dom/client'
// import { BrowserRouter } from 'react-router-dom'
import { HashRouter } from 'react-router-dom';

import App from './App.jsx'
import './index.css'

import Header from './components/Header'

const root = ReactDOM.createRoot(document.getElementById('1x-root'))
root.render(
  // <React.StrictMode>
    <HashRouter>
      <Header />
      <App />
    </HashRouter>
  // </React.StrictMode>,
)
