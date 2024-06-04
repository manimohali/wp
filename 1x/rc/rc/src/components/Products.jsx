import React, { useEffect, useState } from 'react';
import DataTable from 'react-data-table-component';
import ProductService from '../services/ProductService.js';

function Products() {
  const [productsData, setProductsData] = useState([]);

  useEffect(() => {
    ProductService.getProducts()
      .then(data => {
        setProducts(data);
      });
  }, []);

  const columns = [
    { name: 'ID', selector: row => row.id, sortable: true },
    { name: 'Name', selector: row => row.name, sortable: true },
    { name: 'Price', selector: row => row.price, sortable: true }
  ];

  return (
    <DataTable
      title="Products"
      columns={columns}
      data={productsData}
      pagination
    />
  );
}

export default Products;