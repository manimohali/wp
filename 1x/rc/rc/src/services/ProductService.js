import React from 'react';

class ProductService {
  static getProducts() {
    // Assuming REACT_APP_API_BASE_URL is the base URL defined in your .env file
    const baseUrl = import.meta.env.VITE_BASE_URL;
    const token = import.meta.env.VITE_TOKEN;

    // Append '/products' to the base URL
    const url = `${baseUrl}/products`;

    const headers = new Headers({
      "Authorization": `Bearer ${token}`
    });


    const requestOptions = {
      method: 'GET',
      headers: headers,
      redirect: 'follow'
    };

    return fetch(url, requestOptions)
      .then(response => response.json())
      .catch(error => console.error('Error:', error));
  }
}

export default ProductService;


