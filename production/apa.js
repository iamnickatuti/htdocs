import React, { useEffect, useState } from 'react';
import axios from 'axios';

const ProductTable = () => {
    const [products, setProducts] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get('https://reports.moko.co.ke/demand/api/finishedProducts.php');
                setProducts(response.data);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        fetchData();
    }, []);

    const getTableHeaders = () => {
        if (products.length === 0) {
            return null;
        }

        const firstProduct = products[0];
        return Object.keys(firstProduct);
    };

    return (
        <div>
            <table>
                <thead>
                <tr>
                    {getTableHeaders()?.map((header) => (
                        <th key={header}>{header}</th>
                    ))}
                </tr>
                </thead>
                <tbody>
                {products.map((product, index) => (
                    <tr key={index}>
                        {getTableHeaders()?.map((header) => (
                            <td key={header}>{product[header]}</td>
                        ))}
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
};

export default ProductTable;
