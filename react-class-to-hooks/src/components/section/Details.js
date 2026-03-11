import React, { useState, useEffect, useContext } from 'react'
import {DataContext} from '../Context'
import {Link} from 'react-router-dom'
import Colors from './Colors'
import '../css/Details.css'


export function Details(props) {
    const context = useContext(DataContext); // MIGRATED: static contextType = DataContext + this.context → useContext
    const [product, setProduct] = useState([]); // MIGRATED: this.state = { product: [] } → useState

    const getProduct = () => { // MIGRATED: class method → const arrow function
        if(props.match.params.id){
            const res = context.products;
            const data = res.filter(item =>{
                return item._id === props.match.params.id
            })
            setProduct(data); // MIGRATED: this.setState({ product: data }) → setProduct(data)
        }
    };

    useEffect(() => { // MIGRATED: componentDidMount → useEffect with empty deps []
        getProduct();
    }, []); // eslint-disable-line react-hooks/exhaustive-deps



    render() { // MIGRATED: removed render() wrapper
        const {addCart} = context; // MIGRATED: this.context → context
        return (
            <>
                {
                    product.map(item =>( // MIGRATED: this.state.product → product
                        <div className="details" key={item._id}>
                            <img src={item.src} alt=""/>
                            <div className="box">
                                <div className="row">
                                    <h2>{item.title}</h2>
                                    <span>${item.price}</span>
                                </div>
                                <Colors colors={item.colors}/>
                                <p>{item.description}</p>
                                <p>{item.content}</p>
                                <Link to="/cart" className="cart" onClick={() => addCart(item._id)}>
                                    Add to cart
                                </Link>
                            </div>
                        </div>
                    ))
                }
            </>
        )
    }
}

export default Details