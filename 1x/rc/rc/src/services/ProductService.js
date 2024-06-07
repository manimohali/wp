import React from 'react';
import axios from 'axios';

class ProductService {
  static getProducts(page = 1, perPage = 10, stockStatus = '', productType = '', afterDate = '', beforeDate = '', minPrice = '', maxPrice = '') {
    const baseUrl = import.meta.env.VITE_BASE_URL;
    const token = import.meta.env.VITE_TOKEN;

    // Constructing query parameters based on the inputs
    const params = new URLSearchParams({
      page: page,
      per_page: perPage
    });
    // Conditionally add stock status, product type, and price filters if they are provided
    if (stockStatus) params.append('stock_status', stockStatus);
    if (productType) params.append('type', productType);
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);

    // Add date filters if provided and ensure they are in ISO8601 format
    if (afterDate) params.append('after', new Date(afterDate).toISOString());
    if (beforeDate) params.append('before', new Date(beforeDate).toISOString());

    const url = `${baseUrl}/wc/v3/products?${params.toString()}`;

    const headers = {
      "Authorization": `Bearer ${token}`
    };

    return axios.get(url, { headers })
      .then(response => {
        return {
          products: response.data,
          totalPages: parseInt(response.headers['x-wp-totalpages'], 10) // Assuming the total pages header is available
        };
      })
      .catch(error => {
        console.error('Error fetching products:', error);
        return null;
      });
  }
}

export default ProductService;
