import React, { useState, useContext } from 'react'
import Menu from './svg/bars-solid.svg'
import Close from './svg/times-solid.svg'
import CartIcon from './svg/shopping-cart-solid.svg'
import {Link} from 'react-router-dom'
import './css/Header.css'
import {DataContext} from './Context'



export function Header() {
    const {cart} = useContext(DataContext); // MIGRATED: static contextType + this.context → useContext

    const [toggle, setToggle] = useState(false); // MIGRATED: this.state = { toggle: false } → useState

    const menuToggle = () =>{
        setToggle(prev => !prev); // MIGRATED: this.setState({ toggle: !this.state.toggle }) → functional setter
    }


    return (
        <header>
            <div className="menu" onClick={menuToggle}>
                <img src={Menu} alt="" width="20"/>
            </div>
            <div className="logo">
                <h1><Link to="/">Nike</Link></h1>
            </div>
            <nav>
                <ul className={toggle ? "toggle" : ""}>
                    <li><Link to="/">Home</Link></li>
                    <li><Link to="/product">Product</Link></li>
                    <li><Link to="/contact">Contact</Link></li>
                    <li><Link to="/about">About</Link></li>
                    <li><Link to="/login">Login / Register</Link></li>
                    <li className="close" onClick={menuToggle}>
                        <img src={Close} alt="" width="20"/>
                    </li>
                </ul>
                <div className="nav-cart">
                    <span>{cart.length}</span>
                    <Link to="/cart">
                        <img src={CartIcon} alt="" width="20"/>
                    </Link>
                </div>
            </nav>
        </header>
    )
}

export default Header