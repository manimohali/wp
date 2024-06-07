import React, { useEffect, useState } from 'react';
import DataTable from 'react-data-table-component';
import ProductService from '../services/ProductService.js';
import './Products.css';
import Checkbox from '@mui/material/Checkbox';
import ArrowDownward from '@mui/material';

import ArrowDownward from '@material-ui/icons/ArrowDownward';


function Products() {
  const [productsData, setProductsData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(2);
  const [totalPages, setTotalPages] = useState(0);
  const [stockStatus, setStockStatus] = useState('');
  const [productType, setProductType] = useState('');
  const [afterDate, setAfterDate] = useState('');
  const [beforeDate, setBeforeDate] = useState('');
  const [minPrice, setMinPrice] = useState('');
  const [maxPrice, setMaxPrice] = useState('');

  useEffect(() => {
    fetchProducts();
  }, [page, perPage]);

  const fetchProducts = () => {
    setLoading(true);
    ProductService.getProducts(page, perPage, stockStatus, productType, afterDate, beforeDate, minPrice, maxPrice)
      .then(data => {
        setProductsData(data.products);
        setTotalPages(data.totalPages);
        setLoading(false);
      });
  };

  const US_formatter = new Intl.NumberFormat( 'en-US', 
              {  style: 'currency',
                 currency: 'USD',
                 minimumFractionDigits: 2,
                 maximumFractionDigits: 2
              });

  let dateOptions = {  year: 'numeric',weekday: 'short', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
  
  const columns = [
    { name: 'ID', selector: row => row.id, sortable: true },
    { name: 'Name', selector: row => row.name, sortable: true },
    { name: 'Date', selector: row => new Date(row.date_created).toLocaleDateString("en-IN",dateOptions ), sortable: true },
    { name: 'Stock Status', selector: row => row.stock_status, sortable: true },
    { name: 'SKU', selector: row => row.sku, sortable: true },
    { name: 'Price', selector: row => US_formatter.format(row.price), sortable: true },
    { name: 'Regular Price', selector: row => US_formatter.format(row.regular_price), sortable: true },
    { name: 'Sale Price', selector: row => US_formatter.format(row.sale_price), sortable: true }
  ];

  const handlePageChange = page => {
    setPage(page);
  };

  const handlePerRowsChange = async (newPerPage, page) => {
    setPerPage(newPerPage);
    setPage(page);
  };

  if (loading) {
    return <div className='loader'></div>;
  }

  return (
    <>
      <div className="filters">
        <div className="common-div">
          <label htmlFor="stockStatus">Stock Status</label>
          <select value={stockStatus} onChange={e => setStockStatus(e.target.value)}>
            <option value="">All Stock Statuses</option>
            <option value="instock">In Stock</option>
            <option value="outofstock">Out of Stock</option>
            <option value="onbackorder">On Backorder</option>
          </select>
        </div>
        <div className="common-div">
          <label htmlFor="productType">Product Type</label>
          <select value={productType} onChange={e => setProductType(e.target.value)}>
            <option value="">All Types</option>
            <option value="simple">Simple</option>
            <option value="grouped">Grouped</option>
            <option value="external">External</option>
            <option value="variable">Variable</option>
          </select>
        </div>
        <div className="common-div">
          <label htmlFor="afterDate">After Date</label>
          <input type="date" value={afterDate} onChange={e => setAfterDate(e.target.value)} />
        </div>
        <div className="common-div">
          <label htmlFor="beforeDate">Before Date</label>
          <input type="date" value={beforeDate} onChange={e => setBeforeDate(e.target.value)} />
        </div>
        <div className="common-div">
          <label htmlFor="minPrice">Min Price</label>
          <input type="number" value={minPrice} onChange={e => setMinPrice(e.target.value)} />
        </div>
        <div className="common-div">
          <label htmlFor="maxPrice">Max Price</label>
          <input type="number" value={maxPrice} onChange={e => setMaxPrice(e.target.value)} />
        </div>
        <div className="common-div">
          <button onClick={fetchProducts}>Apply Filters</button>
        </div>
      </div>

      <DataTable
        title="Products"
        columns={columns}
        data={productsData}
        pagination
        paginationServer
        paginationTotalRows={totalPages * perPage}
        onChangeRowsPerPage={handlePerRowsChange}
        onChangePage={handlePageChange}
        responsive={true}
      />
      
    </>
  );
}

export default Products;