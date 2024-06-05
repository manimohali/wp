import React, { useEffect, useState } from 'react';
import DataTable from 'react-data-table-component';
import ProductService from '../services/ProductService.js';
import './Products.css';

function Products() {
  const [productsData, setProductsData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(10);
  const [totalPages, setTotalPages] = useState(0);
  const [stockStatus, setStockStatus] = useState('');
  const [productType, setProductType] = useState('');
  const [afterDate, setAfterDate] = useState('');
  const [beforeDate, setBeforeDate] = useState('');

  useEffect(() => {
    fetchProducts();
  }, [page, perPage, stockStatus, productType, afterDate, beforeDate]);

  const fetchProducts = () => {
    setLoading(true);
    ProductService.getProducts(page, perPage, stockStatus, productType, afterDate, beforeDate)
      .then(data => {
        setProductsData(data.products);
        setTotalPages(data.totalPages);
        setLoading(false);
      });
  };

  const columns = [
    { name: 'ID', selector: row => row.id, sortable: true },
    { name: 'Name', selector: row => row.name, sortable: true },
    { name: 'Price', selector: row => row.price, sortable: true }
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
      />
    </>
  );
}

export default Products;