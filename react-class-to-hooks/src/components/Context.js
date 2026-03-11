import React, { useState, useEffect } from 'react'

export const DataContext = React.createContext();

export function DataProvider(props) {

    const [products] = useState([
        {
            "_id": "1",
            "title": "Nike Shoes 01",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img1.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 23,
            "colors": ["red", "black", "crimson", "teal"],
            "count": 1
        },
        {
            "_id": "2",
            "title": "Nike Shoes 02",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img2.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 19,
            "colors": ["red", "crimson", "teal"],
            "count": 1
        },
        {
            "_id": "3",
            "title": "Nike Shoes 03",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img3.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 50,
            "colors": ["lightblue", "white", "crimson", "teal"],
            "count": 1
        },
        {
            "_id": "4",
            "title": "Nike Shoes 04",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img4.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 15,
            "colors": ["orange", "black", "crimson", "teal"],
            "count": 1
        },
        {
            "_id": "5",
            "title": "Nike Shoes 05",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img5.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 10,
            "colors": ["orange", "black", "crimson", "teal"],
            "count": 1
        },
        {
            "_id": "6",
            "title": "Nike Shoes 06",
            "src": "https://www.upsieutoc.com/images/2020/06/27/img6.jpg",
            "description": "UI/UX designing, html css tutorials",
            "content": "Welcome to our channel Dev AT. Here you can learn web designing, UI/UX designing, html css tutorials, css animations and css effects, javascript and jquery tutorials and related so on.",
            "price": 17,
            "colors": ["orange", "black", "crimson", "teal"],
            "count": 1
        }
    ]);
    const [cart, setCart] = useState([]); // MIGRATED: this.state.cart → useState
    const [total, setTotal] = useState(0); // MIGRATED: this.state.total → useState

    // MIGRATED: componentDidMount → useEffect with empty deps []
    useEffect(() => {
        const dataCart = JSON.parse(localStorage.getItem('dataCart'));
        if(dataCart !== null){
            setCart(dataCart);
        }
        const dataTotal = JSON.parse(localStorage.getItem('dataTotal'));
        if(dataTotal !== null){
            setTotal(dataTotal);
        }
    }, []);

    // MIGRATED: componentDidUpdate → useEffect with [cart, total] dependency array
    useEffect(() => {
        localStorage.setItem('dataCart', JSON.stringify(cart))
        localStorage.setItem('dataTotal', JSON.stringify(total))
    }, [cart, total]);

    const getTotal = () => {
        const res = cart.reduce((prev, item) => {
            return prev + (item.price * item.count);
        }, 0)
        setTotal(res); // MIGRATED: this.setState({ total }) → setTotal
    };

    const addCart = (id) => {
        const check = cart.every(item => {
            return item._id !== id
        })
        if(check){
            const data = products.filter(product => {
                return product._id === id
            })
            setCart([...cart, ...data]); // MIGRATED: this.setState({ cart }) → setCart
        }else{
            alert("The product has been added to cart.")
        }
    };

    const reduction = id => {
        const updatedCart = cart.map(item => {
            if(item._id === id){
                return { ...item, count: item.count === 1 ? 1 : item.count - 1 };
            }
            return item;
        });
        setCart(updatedCart); // MIGRATED: this.setState({ cart }) → setCart
        getTotal();
    };

    const increase = id => {
        const updatedCart = cart.map(item => {
            if(item._id === id){
                return { ...item, count: item.count + 1 };
            }
            return item;
        });
        setCart(updatedCart); // MIGRATED: this.setState({ cart }) → setCart
        getTotal();
    };

    const removeProduct = id => {
        if(window.confirm("Do you want to delete this product?")){
            const updatedCart = cart.filter(item => item._id !== id);
            setCart(updatedCart); // MIGRATED: this.setState({ cart }) → setCart
            getTotal();
        }
    };

    return (
        <DataContext.Provider
        value={{products, addCart, cart, reduction, increase, removeProduct, total, getTotal}}>
            {props.children}
        </DataContext.Provider>
    )
}